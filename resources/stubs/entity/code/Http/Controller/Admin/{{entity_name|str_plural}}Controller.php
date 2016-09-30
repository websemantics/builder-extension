<?php namespace {{vendor|studly_case}}\{{module_slug|studly_case}}Module\Http\Controller\Admin;

use {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Form\{{entity_name}}FormBuilder;
use {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Table\{{entity_name}}TableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Illuminate\Routing\Redirector;

/**
 * Class {{entity_name}}Controller
 *
{{config.docblock}}
 * @package   {{vendor|studly_case}}\{{module_slug|studly_case}}Module\Http\Controller\Admin
 */

class {{entity_name|str_plural}}Controller extends AdminController
{

    /**
     * Redirect to {{entity_name|lower|str_plural}}.
     *
     * @param Redirector $redirector
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Redirector $redirector)
    {
        return $redirector->to('admin/{{module_name|lower}}/{{entity_name|lower|str_plural}}');
    }

    /**
     * Return an index of existing entries.
     *
     * @param {{entity_name}}TableBuilder $table
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function index({{entity_name}}TableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Return a form for a new entry.
     *
     * @param {{entity_name}}FormBuilder $form
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function create({{entity_name}}FormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Return a form for an existing entry.
     *
     * @param {{entity_name}}FormBuilder $form
     * @param                     $id
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function edit({{entity_name}}FormBuilder $form, $id)
    {
        return $form->render($id);
    }
}
