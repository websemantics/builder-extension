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
     * @param string $templates   file location
     * @param string $data        used to replace placeholders inside all template files
     * @param string $startNeedle used to locate where to add data
     * @param string $endNeedle   used to locate where to add data
     */
    protected function processTemplate($file, $template, $data, $startNeedle, $endNeedle)
    {

        if (file_exists($template)) {
            $template = $this->parser->parse($this->files->get($template), $data);

            $content = $this->files->get($file);

            // Extract content between start and end neeles,
            $start = strpos($content, $startNeedle) + strlen($startNeedle);
            $end = strrpos($content, $endNeedle);
            $columns = substr($content, $start, $end - $start);

            // Insert column template at the ned,
            $columns = $columns.$template;

            // Reinsert into the file,
            if (strpos($content, $template) === false) {
                $content = substr_replace(
                    $content,
                    $columns,
                    $start,
                    $end - $start
                );
            }

            $this->files->put($file, $content);
        } else {
            dd('Missing template: '.$template);
        }
    }
}
