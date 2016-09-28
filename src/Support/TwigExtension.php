<?php namespace Websemantics\BuilderExtension\Support;

/**
 * Class Twig Extension for the Builder .
 *
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
 class TwigExtension extends \Twig_Extension
{
  public function getName()
  {
      return 'Twig_Extension_Builder';
  }


  public function getFunctions()
  {
      return [
          new \Twig_SimpleFunction('figlet', [[], 'get'])
      ];
  }
