# Entity Builder Extension

Scaffold your PyroCMS Modules in style.

This Module extends PyroCMS core/streams to generate entity components. 

An Entity builder to to be used with Pyro Modules. An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities. Code generated includes the Entity Model and Repository, Plugin, Contracts, Table and Form Builders.

Notice:
=======

Make sure that the 'src' folder, the Module, the Module Service Provider and the 'addon.php' english language files have write permissions.

To Use:
=======

1- Create a config file in your module resources/config/builder.php

2- Specify a list of stream namespaces that you wanted to generate entitie for.
```
	'namespaces' => ['coffee']
```
3- Specify if you want the entities generated grouped in a namespace folder
```
	'namespace_folder' => true,
```
4- Specify your project docblock
```
	'docblock' =>
' * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>'
```
