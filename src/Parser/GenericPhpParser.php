<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Websemantics\EntityBuilderExtension\PhpParser\Helper;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Parser;
use PhpParser\Lexer;

/**
 * Class GenericPhpParser
 *
 * This helper class parse the service provider content to allow modifiying the 
 * content of some properties (for example, route, bindings etc), no messing about 
 * with regular expressions
 *
 * Parsing code is never easy, hence, this class isn't doing great job at being
 * easy to follow, .. 
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class GenericPhpParser
{   
    protected $data;

    protected $helper;

    protected $code = null;

    protected $parser;

    protected $phpParser;

    protected $prettyPrinter;

    /**
     * Parse the PHP code of the ServiceProvider to get the named property (i.e. bindings)
     * @param string $phpCode, the content of a php file,
     * @param string $data used to replace placeholders inside template file
     * @param string $parser text parser
     */
    public function __construct($phpCode, $data , $parser)
    {
        $this->data = $data;

        $this->parser = $parser;

        $this->phpParser = new Parser(new Lexer());

        $this->prettyPrinter = new Standard();

        $this->helper = new Helper();

        $this->code = $phpCode ? $this->phpParser->parse($phpCode) : null;
    }

    /**
     * Parse 
     *
     * @param string $name the name of the property
     * @param string $templateFile the file contains the property template data
     * @return array
     */
    public function parse($name, $templateFile)
    {   
        if(is_null($this->code))
            return;

        // (1) Parse the template to get its content as an associativ earray
        $template = file_get_contents($templateFile);                               
        $template = $this->parser->parse($template, $this->data);

        $templateNode = $this->phpParser->parse($template);

        // Reading template file
        if(count($templateNode) > 0 && $templateNode[0]->getType() !== 'Expr_Array')
            throw new \Exception("Incorrect template formatting", 1);
       
        // (2) Parse the php code for the named property 
        // '$name' (i.e. 'route', 'bindings' etc), get its array value
        // then merge with the template array
        // TO DO: abstract to a better logic
        if($this->code[0]->getType() === 'Stmt_Namespace'){
            $this->helper->parseClassProperty($this->code[0], $name, $templateNode[0]);
        }

        // (3) Parse the addon.php language file for the section list
        // TO DO: abstract to a better logic
        if($this->code[0]->getType() === 'Stmt_Return' && 
           $this->code[0]->expr->getType() === 'Expr_Array'){
            $this->helper->parseReturnArray($this->code[0], $name, $templateNode[0]);
        }

    }

    /**
     * Get the phpCode content
     *
     * @return string
     */
    public function prettyPrint()
    {
        if(is_null($this->code))
            return null;

        $content = $this->prettyPrinter->prettyPrintFile($this->code);
        
        return str_replace("\\\\", "\\", $content);
    }
}