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

  /**
   * The default Figlet font
   *
   * @var string
   */
  protected $figletFont = 'small';

  /**
   * @var string|object
   */
  protected $str = 'Illuminate\Support\Str';

  /**
   * Gets the default extension name
   *
   * @return string
   */
  public function getName()
  {
      return 'builder';
  }

  /**
   * Gets the default Figlet font
   *
   * @return string
   */
  public function getDefaultFigletFont()
  {
      return $this->figletFont;
  }

  /**
   * Extension functions
   *
   * @return array
   */
  public function getFunctions()
  {
      return [
        new \Twig_SimpleFunction(
            'str_*',
            function ($text) {
                $arguments = array_slice(func_get_args(), 1);
                return call_user_func_array([$this->str, camel_case($text)], $arguments);
            }
        )
      ];
  }
  /**
   * Extension filters
   *
   * @return array
   */  public function getFilters()
  {
    return [
        new \Twig_SimpleFilter('camel_case', [$this->str, 'camel']),
        new \Twig_SimpleFilter('snake_case', [$this->str, 'snake']),
        new \Twig_SimpleFilter('studly_case', [$this->str, 'studly']),
        new \Twig_SimpleFilter('humanize', [$this->str, 'humanize']),
        new \Twig_SimpleFilter(
            'str_*',
            function ($name) {
                $arguments = array_slice(func_get_args(), 1);
                return call_user_func_array([$this->str, camel_case($name)], $arguments);
            }
        ),
        new \Twig_SimpleFilter('figlet',
        /**
         * Converts text to a figlet text using with selected font
         *
         * <pre>
         *   {{ module.name|figlet("shadow") }}
         *   {{  [name, ' ','Module']|join|upper|figlet}}
         * </pre>
         *
         * Figlet fonts:
         * banner, big, block, bubble, digital, ivrit, lean, mini, script, shadow, slant,
         * small, smscript, smshadow, smslant, speed, standard, term,
         *
         * @param Twig_Environment                               $env      A Twig_Environment instance
         * @param DateTime|DateTimeInterface|DateInterval|string $text     A string
         * @param string|null                                    $font   The target font, null to use the default
         *
         * @return string The formatted text
         */
        function (\Twig_Environment $env, $text, $font = null) {
          if ($font === null) {
              $font = $env->getExtension('builder')->getDefaultFigletFont();
          }
          return \Packaged\Figlet\Figlet::create($text, $font);
        }, ['needs_environment' => true])
    ];
  }
}
