## Entity Builder Extension

This is an extension to PyroCMS core/streams to generate entity components.

### What is an Entity

An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities. Code generated includes the Entity Model and Repository, Plugin, Contracts, Table and Form Builders.

### Scaffold your PyroCMS Modules in style

This extension once installed, works in the background to generate entities for all streams migrations in your modules. It will also configure your modules with routes, bindings, language file entries etc, so you don't have to lift a finger.

#### Usage:

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
6- Set the `on_module` flag in the `resources/config/builder.php` config file (*true* by default). This changes the behaviour of the Entity Builder such that, entities are generated only when the *ModuleWasInstalled* event is recieved. This would allow for better generated code since the streams and assginements are already installed. The off/false mode, will generate an `Entity` per *StreamWasCreated* event and modifiy the Table/Form Builder for each assignment.

7- If you have content for the Model and Repository of a Stream, place in your module here, `builder/EntityName/EntityModle.php`, `builder/EntityName/EntityRepository.php`.

for example:

```
	BlogModule/builder/Post/PostModle.php
	BlogModule/builder/Post/PostRepository.php
```

8- By default the Table and Form Builders will have a list of all assignment slugs of a Stream. However, if you want to provide the content for the *EntityTableColumns*, `$builder->setColumns()`, or *EntityFormBuilder*, `protected $fields = []` as in previous point, create a file with the custom content in `builder/EntityName/EntityTableColumns.php`, `builder/EntityName/EntityFormFields.php`

9- If you have seed data for a particular Entity/Model, place that in, `builder/EntityName/EntitySeederData.php`. The content must be a list of entry values, for example 

```
['name' => 'jo', 'age' => 30], ['name' => 'kate', 'age' => 25]
```

Also, make sure that the Module Seeder class has the following structure:

```
<?php namespace {{vendor_name}}\\{{module_name}}Module;
use Anomaly\Streams\Platform\Database\Seeder\Seeder;
class {{module_name}}ModuleSeeder extends Seeder
{
	protected $seeders = [];
    /**
     * Seed the localization module.
     */
    public function run()
    {   
    		foreach ($this->seeders as $seeder) {
        	    $this->call($seeder);
    		}             
    }
}
```

Refere to [example-module](https://github.com/websemantics/example-module)  or use [Boxed](http://websemantics.github.io/boxed/) to scaffold your module's code.

10- Finally, install your module

---

#### Notice:

Make sure that the `src` folder, the `Module`, the `Module Service Provider`, `ModuleSeeder` and the `addon.php` english language file have write permissions.

