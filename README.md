```
  _____       _   _ _           ____        _ _     _           
 | ____|_ __ | |_(_) |_ _   _  | __ ) _   _(_) | __| | ___ _ __ 
 |  _| | '_ \| __| | __| | | | |  _ \| | | | | |/ _` |/ _ \ '__|
 | |___| | | | |_| | |_| |_| | | |_) | |_| | | | (_| |  __/ |   
 |_____|_| |_|\__|_|\__|\__, | |____/ \__,_|_|_|\__,_|\___|_|   
                        |___/   Extension v0.7                                 
```                                                                                             
> Last update: 25 Feb 2016

Scaffold your PyroCMS Modules in style. This extension once installed, works silently in the background to generate entities for all your streams. It will also configure your module with routes, bindings, language file entries etc, so you don't have to lift a finger

### What is an Entity

An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities. 

Code generated for an entity includes the Entity Model and Repository, Plugin, Seeder, Contracts, Table and Form Builders.

#### Step by Step Usage:

1- Create a new Pyro project and install @ folder `builder-blog-example`

```
composer create-project pyrocms/pyrocms=3.0-beta3 --prefer-dist builder-blog-example
```
```
php builder-blog-example/artisan install
```

Or, use an exsting one.

2- Clone and install this extension, 

```
git clone https://github.com/websemantics/entity_builder-extension builder-blog-example/addons/default/websemantics/entity_builder-extension
```
```
php builder-blog-example/artisan extension:install websemantics.extension.entity_builder
```

3- Start with a new module, 'Blog' (namespace = `blog` by defaul)

```
php builder-blog-example/artisan make:module websemantics blog
```

Or use [Boxed](http://websemantics.github.io/boxed)

Make sure that main module files have write permissions '777'

```
src/BlogModule.php
src/BlogModuleServiceProvider.php
```

This step will also create fields migration file located at `builder-blog-example/addons/default/websemantics/blog-module/migrations`

4- Use the module's fields migration file created at the previous step, or create a new one

```
php builder-blog-example/artisan make:migration create_module_fields --addon=websemantics.module.blog
```

Change class content to:

```
    protected $fields = [
        'title'                      => 'anomaly.field_type.text',
        'content'                    => 'anomaly.field_type.text'
    ];
```

Also, make sure the class extends,

```
Anomaly\Streams\Platform\Database\Migration\Migration
```

5- Create your module streams migration files

```
php builder-blog-example/artisan make:stream posts websemantics.module.blog

```

Change class content to:

```
    protected $stream = [
        'slug'         => 'posts',
        'title_column' => 'title'
    ];

    protected $assignments = [
        'title'        => [
            'required' => true,
            'unique'   => true
        ],
        'content'     => [
            'required' => true,
        ]
    ];
```

6- Create or edit the builder config file within your module at `builder-blog-example/addons/default/websemantics/blog-module/resources/config/builder.php` to specify a list of stream namespaces that you wanted to generate entities for,

```
  'namespaces' => [
    'blog' => [

    ]
  ],

```

7- Specify if you want the streams entities generated grouped in a folder (named after the current namespace)

```
  'namespace_folder' => true,
```

8- Specify your project docblock to be included with the generated code
```
'docblock' =>
' * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>'
```

More settings are detailed in the `builder.php` file.

9- If you have seed data for a particular Entity/Model (abc), place that in, `builder-blog-example/addons/default/websemantics/blog-module/seeders`. 

In this example, create post.php (singular file name) at `builder-blog-example/addons/default/websemantics/blog-module/seeders/post.php`

The content must be a list of entry values without the <?php, for example:

```
  ['title' => 'Laravel', 'content' => 'PHP framework'], 
  ['title' => 'PyroCMS', 'content' => 'PHP CMS']
```

This will be added to the Entity Seeder class.

10- Install your module,

```
php builder-blog-example/artisan module:install websemantics.module.blog
```

This will install and automatically seed your module, horray!

You are done. Go to admin panel and check your beautiful new Module in action `admin/blog/posts`

10- Make changes, regenerate and test

After making changes to your migration files, adding / removing streams, adding / removing fields, run a reinstall module command and watch how your module's entities get rebuilt with fresh code scaffolded before your eyes,

```
php builder-blog-example/artisan module:reinstall websemantics.module.blog
```

Have fun, 

#### Inner Working:

Once installed, this extension listens mainly to two types of events *StreamWasCreated* and *AssignmentWasCreated*. To enable this extension for your current module, creat a config file at `resources/config/builder.php` and list the namespaces you would like the extension to generate code for. You can listen/generate code to multiple namespaces.

```
    'namespaces' => ['blogger', 'navigatin', 'etc']
```

Here's an example of the [builder config file](https://github.com/websemantics/example-module/blob/master/resources/config/builder.php) taken from [Boxed](http://websemantics.github.io/boxed) example module github repo. Once that's done, create your Streams migration files as usual. The extension will kick in when it recieives either of the two events mentioned above:

- *StreamWasCreated*
- *AssignmentWasCreated* 

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

2- For *AssignmentWasCreated* event, the extension will modify two files, `AbcTableColumns.php` and `AbcFormBuilder.php` and add a field slug per stream assignmenet.

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

#### Install:

- Download the code or clone this repo into your addon folder at 
`addons/default/websemantics/entity_builder-extension`
- Login to your admin
- Install this extension from Addons/Extensions

### Change Log
All notable changes to this project will be documented in this section.

#### [0.7] - 2016-02-25
##### Changed
- Improved documentation
- Automatically seeds modules after install,
- Support for more field types
- Module example

#### [0.6] - 2016-02-12
##### Changed
- Add common methods to Repository class
- Generate language files for stream, fields and section
- fixing few bugs with Entity Seeders
- Code updates due to changes in Pyro

#### [0.5] - 2015-11-17
##### Changed
- Add new command to create a module

#### [0.4] - 2015-11-5
##### Changed
- Customize table columns and form fields,
- More control on templates
- Example builder config file
- Detailed documentation
- Fixing bugs

####[0.2] - 2015-04-5
##### Changed
- Creates streams entities
- Allow to group in namespace folder
- Seeding streams


