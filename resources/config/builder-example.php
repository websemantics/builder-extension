<?php

return [

	/* 

	This is an example builder file.

	*/

	/*
	|--------------------------------------------------------------------------
	| Namespaces
	|--------------------------------------------------------------------------
	| A list of all the Stream Namespaces used in this Module.
	|
	| More controel over code generated can be established per assigment, 
	| for example, hide / show table columns and form fields per stream assignment, 
	| also table column field view / template.
	|
	| for example: 
	|
	|	'namespaces' => [
	|		'namespace' => [
	|			'field_slug' => [
	|				'hide_column' => true,  *optional, (false by default)
	|				'hide_field'  => fale,  *optional, (false by default)
	|				'column_template'    => 
	|     '<span class="label label-default">{value}</span> *optional, ('{value}' by default)
	|			]
	|		]
	| ]
	|
	| Boolean Field Type:
	| ------------------
	| For boolean type there's a default column_template if it's set to empty ''
	|  			'column_template'    =>  ''
	| You can also have your own markup, for that there's two variables {class} & {value}
	| For example, '<span class="label label-{class}">{value}</span>'
	| 
	|	*/

	'namespaces' => [
		'namespace' => [
			// 'field_slug_1' => [
			// 	'column_template'    => '<span class="label label-info">{value}</span>'
			// ],
			// 'field_slug_2' => [
			// 	'hide_column'        => true
			// ]
		]
	],
	
	/*
	|--------------------------------------------------------------------------
	| Relationship 'null' value
	|--------------------------------------------------------------------------
	| The default view for a null relationship entry
	|
	*/

	'null_relationship_entry' => '<span class="label label-default">null</span>',

	/*
	|--------------------------------------------------------------------------
	| Namespaces folder
	|--------------------------------------------------------------------------
	| Group all streams in one folder
	|
	*/

	'namespace_folder' => true,

	/*
	|--------------------------------------------------------------------------
	| Repository Super Class
	|--------------------------------------------------------------------------
	| Extend all generated repositories to a common super class, if any!
	| For example: 'Websemantics\NamedModule\Common\CommonRepository'
	|
	*/

	'extends_repository' => '',

	/*
	|--------------------------------------------------------------------------
	| Avoid Overwrite
	|--------------------------------------------------------------------------
	|
	| For developement and to avoid overwriting on code added, list all the files 
	| that should not be overwritten by the builder. Use the last part of the 
	| file name, ..
	| 
	| for example: 
	|   -PostModel.php 		  -> 'Model.php'
	|   -FileTableColumns.php -> 'TableColumns.php'
	|
	| If you require the builder to generate a fresh copy of the file, either remove it from
	| here or delete it form the module.
	|
	*/

	'avoid_overwrite' => [
		'Model.php',
		'Repository.php',
		// 'TableColumns.php', /* uncomment this if you no longer want this to be overwritten */
	    // 'FormBuilder.php'
		// 'ModuleSeeder.php'
	],

	/*
	|--------------------------------------------------------------------------
	| Docblock
	|--------------------------------------------------------------------------
	|	Docblock text to include with generated files
	|
	*/

	'docblock' =>
' * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>'
];