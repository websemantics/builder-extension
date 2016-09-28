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
   * The Twig instance
   *
   * @var Bridge
   */
  protected $twig;

  /**
   * Create a new TwigParser instance
   *
   * @param UrlGenerator $url
   * @param Engine       $parser
   * @param Request      $request
   */
  public function __construct()
  {
    $this->twig = new \Twig_Environment(new \Twig_Loader_String);
    $this->twig->addExtension(new TwigExtension());
  }

  /**
   * Parse data into the content
   *
   * @param  string $content
   * @param  array $data
   * @return string
   */
  public function parse($content, $data = [])
  {
      return $this->parser->render($content, $data);
  }
}
