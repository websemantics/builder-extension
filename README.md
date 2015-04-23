## Entity Builder Extension

Scaffold your PyroCMS Modules in style. This extension once installed, works silently in the background to generate entities for all your streams. It will also configure your module with routes, bindings, language file entries etc, so you don't have to lift a finger

### What is an Entity

An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities. 

Code generated for an entity includes the Entity Model and Repository, Plugin, Seeder, Contracts, Table and Form Builders.

#### Step by Step Usage:

1- Starting with an empty module, use [Boxed](http://websemantics.github.io/boxed)

2- Create your streams migration files

3- Create/edit the builder config file within your module at `resources/config/builder.php` to specify a list of stream namespaces that you wanted to generate entities for,

```
  'namespaces' => ['coffee']
```

4- Specify if you want the entities generated grouped in a folder (named after the current namespace)

```
  'namespace_folder' => true,
```

5- Specify your project docblock to be included with the generated code
```
'docblock' =>
' * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>'
```

6- If you have seed data for a particular Entity/Model (abc), place that in, `xyz-module/seeders/abc.php`. The content must be a list of entry values, for example:

```
  ['name' => 'jo', 'age' => 30], 
  ['name' => 'kate', 'age' => 25]
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

7- Finally, install your module

#### Inner Working:

Once installed, this extension listens mainly to two types of events *StreamWasCreated* and *AssignmentWasCreatedHandler*. To enable this extension for your current module, creat a config file at `resources/config/builder.php` and list the namespaces you would like the extension to generate code for. You can listen/generate code to multiple namespaces.

```
    'namespaces' => ['blogger', 'navigatin', 'etc']
```

Here's an example of the [builder config file](https://github.com/websemantics/example-module/blob/master/resources/config/builder.php) taken from [Boxed](http://websemantics.github.io/boxed) example module github repo. Once that's done, create your Streams migration files as usual. The extension will kick in when it recieives either of the two events mentioned above:

- *StreamWasCreated*
- *AssignmentWasCreatedHandler* 

1- For *StreamWasCreated* event, the extension will generate an entity folder for this stream from the template stored at `entity_builder-extension/resources/assets/entity/code`. The folder map of this entity follows the following structure:

```
AbcEntity
  |
  +-- Contract
  |      |
  |      +--- AbcInterface.php
  |      |
  |      +--- AbcRepositoryInterface.php
  |
  |
  +-- Form
  |    |
  |    +--- AbcFormBuilder.php
  |
  |
  +-- Table
  |     |
  |     +--- AbcTableBuilder.php
  |     |
  |     +--- AbcTableColumns.php
  |
  |
  +---- AbcModle.php
  |
  +---- AbcRepository.php
  |
  +---- AbcSeeder.php
  |
  +---- AbcPlugin.php
```

By default, this folder structure would be generated in a subfolder at `src`. The name of the subfolder is the namespace of the current stream. For example, if the stream is called *Blog* and the namespace Blogger then the Stream model will be: `src\Blogger\BlogModle.php`. This behaviour can be changed from the builder config file by setting `namespace_folder` to *false*:

```
    'namespace_folder' => false,
```

The extension then will generate a controller per stream at `xyz-module/src/Http/Controller/Admin/AbcController.php` and modify the *Module*, *ServiceProvider*, *Seeder* and language files to setup the entity to work correctly with the module. 

2- For *AssignmentWasCreatedHandler* event, the extension will modify two files, `AbcTableColumns.php` and `AbcFormBuilder.php` and add a field slug per stream assignmenet.

Once the entity files have been created and working correctly with Pyro, you might want to modify and develop the classes indivisually. The extension provides a configuration option to list the files you don't want to overwrite accedentaly (when re-installing a module). For example, if you have edited the `AbcModle.php`, make sure to list that in the builder config file so that the extension will avoid overwrite if it exists. Here's an example, 

```
  'avoid_overwrite' => [
    'Model.php',
    'Repository.php',
    'TableColumns.php'
  ],
```

Notice that, the name of the entity has been omitted.


#### Notice:

Make sure that the following folders/files have write permission:

- `src` 
- `src/XyzModule.php`
- `src/XyzServiceProvider.php`
- `src/XyzModuleSeeder.php` 
- `resources/lang/en/addon.php`
- `src/Http/Controller/Admin`