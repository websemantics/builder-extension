# Entity Builder Extension

This is an extension to PyroCMS core/streams to generate entity components.

## Scaffold your PyroCMS Modules in style

This extension once installed, works in the background to generate entities for all streams migrations in your modules. It will also configure your modules with routes, bindings, language file entries etc, so you don't have to lift a finger.

## What is an Entity

An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities. Code generated includes the Entity Model and Repository, Plugin, Contracts, Table and Form Builders.

### Notice:

Make sure that the `src` folder, the `Module`, the `Module Service Provider` and the `addon.php` english language file have write permissions.

### To Use:

1- Create a config file in your module `resources/config/builder.php`

2- Specify a list of stream namespaces that you wanted to generate entities for
```
	'namespaces' => ['coffee']
```
3- Specify if you want the entities generated grouped in a folder (named after the current Namespace)
```
	'namespace_folder' => true,
```
4- Specify your project docblock to be included with the generated code
```
'docblock' =>
' * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>'
```
5- Install your module

6- Enjoy
