<?php namespace Websemantics\BuilderExtension\Support;

use Websemantics\BuilderExtension\Support\Helper;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Lexer;
use PhpParser\ParserFactory;

/**
 * Class GenericPhpParser.
 *
 * This helper class parse the service provider content to allow modifiying the
 * content of some properties (for example, routes, bindings etc), no need messing about
 * with regular expressions, oh yeah!
 *
 * Parsing code is never easy, hence, this class isn't doing a great job at being
 * easy to follow, .. go figure!
 *
 * 25 Sept 2016, looking at this again, disaster!
 *
 * @link      http://websemantics.ca
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
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
     * Parse the PHP code of the ServiceProvider to get the named property (i.e. bindings).
     *
     * @param string $phpCode, the content of a php file,
     * @param string $data     used to replace placeholders inside template file
     * @param string $parser   text parser
     */
    public function __construct($phpCode, $data, $parser)
    {
        $this->data = $data;

        $this->parser = $parser;

        // $this->phpParser = new \PhpParser\Parser\Php7(new Lexer());
        $this->phpParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        $this->prettyPrinter = new Standard(['shortArraySyntax' => true]);

        $this->helper = new Helper();

        $this->code = $phpCode ? $this->phpParser->parse($phpCode) : null;
    }

    /**
     * Parse.
     *
     * @param string $name         the name of the property
     * @param string $templateFile the file contains the property template data
     * @param bool   $front,       set location to the front of the array
     *
     * @return array
     */
    public function parse($name, $templateFile, $front = false)
    {
        if (is_null($this->code)) {
            return;
        }

        // (1) Parse the template to get its content as an associative array
        $template = file_get_contents($templateFile);
        $template = $this->parser->parse($template, $this->data);

        $templateNode = $this->phpParser->parse($template);

        // Reading template file
        if (count($templateNode) > 0 && $templateNode[0]->expr->getType() !== 'Expr_Array') {
            throw new \Exception('Incorrect template formatting', 1);
        }

        // (2) Parse the php code for the named property
        // '$name' (i.e. 'route', 'bindings' etc), get its array value
        // then merge with the template array
        // TO DO: abstract to a better logic
        if ($this->code[0]->getType() === 'Stmt_Namespace') {
            $this->helper->parseClassProperty($this->code[0], $name, $templateNode[0]->expr, $front);
        }

        // (3) Parse the addon.php language file for the section list
        // TO DO: abstract to a better logic
        if ($this->code[0]->getType() === 'Stmt_Return' &&
           $this->code[0]->expr->getType() === 'Expr_Array') {
            $this->helper->parseReturnArray($this->code[0], $name, $templateNode[0]->expr, $front);
        }
    }

    /**
     * Get the phpCode content.
     *
     * @return string
     */
    public function prettyPrint()
    {
        if (is_null($this->code)) {
            return;
        }

        $content = $this->prettyPrinter->prettyPrintFile($this->code);

        return str_replace('\\\\', '\\', $content);
    }
}
