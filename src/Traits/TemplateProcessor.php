<?php namespace Websemantics\EntityBuilderExtension\Traits;

/*
 * Class TemplateProcessor.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
trait TemplateProcessor
{
    use FileProcessor;

    /**
     * process the table columns or form template and add fields to it.
     *
     * @param string $file,       a php file to modify
     * @param string template   file location
     * @param string $data        used to replace placeholders inside all template files
     * @param string $startNeedle used to locate where to add data
     * @param string $endNeedle   used to locate where to add data
     * @param string $fallback   a fallback template in case the one provided is not supported yet
     */
    public function processTemplate($file, $template, $data, $startNeedle, $endNeedle, $fallback)
    {
        /* if the template is not supported, fall back to a common template */
        if (!file_exists($template)) {
          $template = $fallback;
        }

        $template = $this->parser->parse($this->files->get($template), $data);
        $content = $this->get($file);

        /* extract content between start and end needle */
        $start = strpos($content, $startNeedle) + strlen($startNeedle);
        $end = strrpos($content, $endNeedle);
        $columns = substr($content, $start, $end - $start);

        /* insert column template at the needle */
        $columns = $columns.$template;

        /* reinsert into the file */
        if (strpos($content, $template) === false) {
            $content = substr_replace(
                $content,
                $columns,
                $start,
                $end - $start
            );
        }

        $this->put($file, $content);
    }
}
