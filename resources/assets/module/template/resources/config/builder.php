<?php

return [

  /*
  |--------------------------------------------------------------------------
  | This file contains all Entity Builder configs
  |--------------------------------------------------------------------------
  */

  /*
  |    _________   _____     _____  __________
  |   |         \==\    \   /    /=|          \\================\
  |   |    __    |  \    \ /    /  |    __     |                |
  |   |   |__)   |   \    V    /   |   |__)    |                |
  |   |      ___/     \       /    |          <                ┌┴┐
  |   |     |          |     |     |     |\    \              /  |
  |   |     |          |     |     |     | \    \            |    \_
  |   |_____|          |_____|     |_____|  \____\        __  \     `-_
  |    ___    _  _    _    ___    _    ___   __ __       /   \  ''-.    \
  |   | __|  | \| |  | |  |_ _|  | |  |_ _|  \ V /      /    /      \    \
  |   | _|   | \\ |  | |   | |   | |   | |    \ /       |   |        |   |
  |   |___|  |_|\_|  |_|   |_|   |_|   |_|    |_|       \    \      /    /
  |    ___    _ _    _    _      __     ___    ___       \    `-..-'    /
  |   | o )  | | |  | |  | |    |  \   | __|  | o \       '-_        _-'
  |   | o \  | U |  | |  | |_   | o )  | _|   |   /          `------'
  |   |___/  |___|  |_|  |___|  |__/   |___|  |_|\_\
  |
  |   Scaffolds modules and streams into working code   - -- --------- -- -
  |
  */

	/*
	|--------------------------------------------------------------------------
	| Namespaces
	|--------------------------------------------------------------------------
	| A list of all the Stream Namespaces used in this Module.
	|
	| More control over code generated can be established per assignment,
	| for example, hide / show table columns and form fields per stream assignment,
	| also table column field view / template.
	|
	| for example:
	|
	|	'namespaces' => [
	|		'namespace' => [
	|			'field_slug' => [
	|				'hide_column' => true,  *optional, (false by default)
	|				'hide_field'  => false,  *optional, (false by default)
	|				'column_template'    =>
	|     '<span class="label label-default">{value}</span> *optional, ('{value}' by default)
	|			]
	|		]
	| ]
	|
	| Boolean Field Type:
	| ------------------
	| For boolean type there's a default column_template if it's value is set to,
	|  			'column_template'    =>  'default'
	| You can also have your own markup, for that there's two variables {class} & {value}
	| For example, '<span class="label label-{class}">{value}</span>'
	|
	|	*/

	'namespaces' => [
		'{namespace}' => [
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
	| Seeding
	|--------------------------------------------------------------------------
	| Allow to seed the module after it has installed by:
	|
	| (1) The Entity Builder : 'yes'
	| (2) Disable seeding : 'no' (*default)
	|
	*/

	'seeding' => 'no',

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
	| Group all entities (streams) in one folder
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

	'extends_repository' => null,

	/*
	|--------------------------------------------------------------------------
	| Avoid Overwrite
	|--------------------------------------------------------------------------
	|
	| For development and to avoid overwriting on code added, list all the files
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
		'Repository.php'/* ,
    'TableColumns.php',
    'FormBuilder.php'
    'ModuleSeeder.php' */
	],

	/*
	|--------------------------------------------------------------------------
	| Docblock
	|--------------------------------------------------------------------------
	|	Docblock text to include with entity generated files
	|
	*/

	'docblock' =>
' * @link      http://websemantics.ca
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar <adnan@websemantics.ca>'
];
