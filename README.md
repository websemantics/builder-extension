```

             __________     _____      _____     ___________
            |          \====\     \   /    /====|           \\========================\
            |     __    |    \     \ /    /     |     __     |                        |
            |    |__)   |     \     V    /      |    |__)    |                        |
            |       ___/       \        /       |           <                        ┌┴┐
            |      |            |      |        |      |\    \                      /  |
            |      |            |      |        |      | \    \                    |    \_
            |______|            |______|        |______|  \____\               __   \     `-_
             _____     _  _     _     ___     _     ___    __ __              /   \  ''-.    \
            |   __|   | \| |   | |   |_ _|   | |   |_ _|   \ V /             /    /      \    \
            |   _|    | \\ |   | |    | |    | |    | |     \ /              |   |        |   |
            |_____|   |_|\_|   |_|    |_|    |_|    |_|     |_|              \    \      /    /
             _____     _ _     _     _       __      ___     ___              \    `-..-'    /
            |   o )   | | |   | |   | |     |  \    | __|   | o \              '-_        _-'
            |   o \   | U |   | |   | |_    | o )   | _|    |   /                 `------'
            |_____/   |___|   |_|   |___|   |__/    |___|   |_|\_\    

            Modules & Streams Scaffolder for Maximum Coding Pleasure         - -- --------- -- -


```                                                                                             
> Last update: 25 August 2016

This PyroCMS extension once installed becomes your coding companion to generate modules and [stream entities](#what-is-an-entity) for all your projects coding needs. It also configure your module classes with routes, bindings, language file entries, seeding and much more so you do not have to lift a finger.


### What is an Entity

An Entity is a representation of an [Object Type](https://en.wikipedia.org/wiki/Object_type_(object-oriented_programming)) which may correspond with a Stream. For example, a Person, a Company or an Animal can all be represented by Streams and Entities.

Code generated for an entity includes an `Entity Model` and `Repository`, `Plugin`, `Seeder`, `Contracts`, `Table Builder` and `Form Builder`.


#### Getting Started

The following example is also available here, [blog](https://github.com/websemantics/blog),

1. Create a new PyroCMS project and store at folder `blog`

```bash
composer create-project pyrocms/pyrocms --prefer-dist blogger

cd blogger

php artisan install
```

2. Install this extension at `addons/default/websemantics`

```bash
git clone https://github.com/websemantics/entity_builder-extension  addons/default/websemantics/entity_builder-extension

php artisan extension:install websemantics.extension.entity_builder
```

3. Create a new module, 'Blog' (namespace = `blog` by default)

```
php artisan make:addon websemantics.module.blog
```

This step will also create fields migration file at `blog/addons/default/websemantics/blog-module/migrations`

4. Edit the module's fields migration file created at the previous step, or create a new one

```
php artisan make:migration create_module_fields --addon=websemantics.module.blog
```

Add the following fields to the `fields` array,

```
    protected $fields = [
        'title'                      => 'anomaly.field_type.text',
        'content'                    => 'anomaly.field_type.text'
    ];
```

5. Create a new stream for `Posts`,

```
php artisan make:stream posts websemantics.module.blog
```

Edit the generated file and add the following to `$stream` and `$assignments` arrays,

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

6. Edit the builder config file at `blog/addons/default/websemantics/blog-module/resources/config/builder.php` to specify a list of stream namespaces that you wanted to generate entities for,

```
  'namespaces' => [
    'blog' => [

    ]
  ]
```

7. Specify if you want the streams entities generated grouped in a folder (named after the current namespace)

```
  'namespace_folder' => true,
```

8. Specify automatic seeding after a module has installed

There are two settings to the seeding option in builder.php,
- `yes`, Entity Builder will seed the module after install,
- `no`, seeding is disabled

```
  'seeding' => 'yes'
```

9. Specify your project docblock to be included with the generated code
```
'docblock' =>
' * @link      http://yourcompany.com
 * @author    Your company, Inc. <info@websemantics.ca>'
```

More settings are detailed in the `builder.php` file.

10. If you have seed data for a particular Entity/Model (abc), place that in, `blog/addons/default/websemantics/blog-module/resources/seeders`.

In this example, create post.php (singular file name) at `blog/addons/default/websemantics/blog-module/resources/seeders/post.php`

The content must be a list of entry values without the <?php, for example:

```
  ['title' => 'Laravel', 'content' => 'PHP framework'],
  ['title' => 'PyroCMS', 'content' => 'PHP CMS']
```

This will be added to the Entity Seeder class when the code is generated.

11. Install (ore reinstall) your module,

```
php artisan module:install websemantics.module.blog
```

This will install your module, hooray!

You are done. Go to admin panel and check your beautiful new Module in action `admin/blog/posts`

12. Making changes

After adding changes to your migration files, adding or removing streams, adding or removing fields, run a reinstall module command and watch how your module's entities get rebuilt with fresh code scaffolded before your eyes,

```
php artisan module:reinstall websemantics.module.blog
```

Have fun, ..

#### Inner Working

Once installed, this extension listens mainly to three event types, *StreamWasCreated*, *AssignmentWasCreated* and *ModuleWasInstalled*. To enable this extension for your current module, create a config file at `resources/config/builder.php` and list the namespaces you would like the extension to generate code for. You can listen/generate code to multiple namespaces.

```
    'namespaces' => ['blogger', 'navigation', 'etc']
```

Here's an example of the [builder config file](https://github.com/websemantics/example-module/blob/master/resources/config/builder.php) taken from [Boxed](http://websemantics.github.io/boxed) example module github repo. Once that's done, create your Streams migration files as usual. The extension will kick in when it receives the events listed above,

1- For *StreamWasCreated* event, the extension will generate an entity folder for the stream from the template stored at `entity_builder-extension/resources/assets/entity/code`. The folder map of this entity is as follows:

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
  +---- AbcModule.php
  |
  +---- AbcRepository.php
  |
  +---- AbcSeeder.php
  |
  +---- AbcPlugin.php
```

By default, this folder structure would be generated in a subfolder at `src`. The name of the subfolder is the namespace of the created stream. For example, if the stream is called *Blog* and the namespace *Blogger* then the Entity model will be: `src\Blogger\BlogModle.php`. This behaviour can be changed from the builder config file by setting `namespace_folder` to `false`,

```
    'namespace_folder' => false,
```

The extension then will generate a controller per entity at `xyz-module/src/Http/Controller/Admin/AbcController.php` and modify *Module*, *ServiceProvider*, *Seeder* class and language files to setup the entity to work correctly with the module.

2- For *AssignmentWasCreated* event, the extension will modify two files, `AbcTableColumns.php` and `AbcFormBuilder.php` and add a field slug per stream assignment.

3- For *ModuleWasInstalled* event, this will add routes and sections to the module and service provider. It will also seed the module if the builder config file was set accordingly.


#### Development

Once the entity files have been created and working correctly with Pyro, you might want to modify and develop the classes individually.

The extension provides a configuration option to list the files that you don't want to overwrite accidentally when re-installing a module and regenerating entity code.

For example, if you have edited the `AbcModle.php`, make sure to list that in the builder config file so that the extension will avoid overwrite it if it exists.

Here's an example,

```
  'avoid_overwrite' => [
    'Model.php',
    'Repository.php',
    'TableColumns.php'
  ],
```

Notice that, the name of the entity has been omitted so that this can be applied globally.


## Contribution

We are more than happy to accept external contributions to the project in the form of feedback, bug reports and even better - pull requests :)


## Support

Need help or have a question? post a questions at [StackOverflow](https://stackoverflow.com/questions/tagged/entity_builder-extension)

*Please don't use the issue trackers for support/questions.*


## Links

[PyroCMS](https://github.com/pyrocms/pyrocms), an MVC PHP Content Management System built to be easy to use, theme and develop with. It is used by individuals and organizations of all sizes around the world.

[Awesome PyroCMS](https://github.com/websemantics/awesome-pyrocms), A curated list of PyroCMS addons and resources.

[PyroCMS Cheatsheet](http://websemantics.github.io/pyrocms-cheatsheet), A list of commands and features for PyroCMS 3.

[Auto Pyro](https://github.com/websemantics/auto-pyro), PyroCMS deploy tool for faster and more pleasurable development experience.


## License

[MIT license](http://opensource.org/licenses/mit-license.php)
Copyright (c) Web Semantics, Inc.
