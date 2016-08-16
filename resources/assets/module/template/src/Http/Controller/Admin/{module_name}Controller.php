<?php namespace {vendor_name}\{module_name}Module\Http\Controller\Admin;

use Anomaly\Streams\Platform\Http\Controller\AdminController;

/**
 * Class {module_name}Controller
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\Http\Controller\Admin
 */

class {module_name}Controller extends AdminController
{
  /**
   * Return admin home view.
   *
   * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
   */
  public function index()
  {
    return moduleView('admin.home', ['readme' => strip_tags(file_get_contents(modulePath('README.md')))]);
  }
}
