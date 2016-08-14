<?php namespace {vendor_name}\{module_name}Module\Http\Controller\Admin;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;

/**
 * Class {module_name}Controller
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\Http\Controller\Admin
 */

class {module_name}Controller extends AdminController
{
  protected $namespace = '{vendor_name_lower}.module.{module_name_lower}';

 /**
   * Return master admin view.
   *
   * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection
   * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
   */
  public function index(ModuleCollection $moduleCollection)
  {
    $module = $moduleCollection->get($this->namespace);

    return view($module->getNamespace('admin.home'),
          ['readme' => strip_tags(file_get_contents($module->getPath('README.md')))]);
  }
}
