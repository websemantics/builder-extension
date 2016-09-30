<?php 
['admin/{{module_name|lower}}/{{entity_name|lower|str_plural}}'            => '{{vendor|studly_case}}\{{module_slug|studly_case}}Module\Http\Controller\Admin\{{entity_name|str_plural}}Controller@index',
'admin/{{module_name|lower}}/{{entity_name|lower|str_plural}}/create'     => '{{vendor|studly_case}}\{{module_slug|studly_case}}Module\Http\Controller\Admin\{{entity_name|str_plural}}Controller@create',
'admin/{{module_name|lower}}/{{entity_name|lower|str_plural}}/edit/{id}'  => '{{vendor|studly_case}}\{{module_slug|studly_case}}Module\Http\Controller\Admin\{{entity_name|str_plural}}Controller@edit'];
