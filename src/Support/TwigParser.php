<?php namespace Websemantics\BuilderExtension\Support;

use Websemantics\BuilderExtension\Support\TwigExtension;

/**
 * Class TwigParser.
 *
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class TwigParser
{

  /**
   * Create a new TwigParser instance and parse data into the content
   *
   * @param  string $content
   * @param  array $data
   * @return string
   */
  public function parse($content, $data = [])
  {
    $twig = new \Twig_Environment(new \Twig_Loader_Array(array()));
    $twig->addExtension(new TwigExtension());
    $template = $twig->createTemplate($content);
    $subject = $template->render($data);
  }
}
