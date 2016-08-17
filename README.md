```

                                             /'\
                                            /   \
                                           /     \
                         _                /   ..' \_                      /\/'\
                        / \              / './       \                   /     \
                      _/   \            /             \        _        /       \
\           /'\      /  '._'\          /               \      / \      /      /''\
 \         /   \    /        \        /      /          \    /   \    / \_ ..'    \
  \    /'./     \  /          \__    /      /            \  /     \__/             \     /'\
   \  /          \/              \  /      /              \/__     \                \   /   \
    \/            \               \/    __/                   \     \                \_/      \     /'\
    /   /          \              /    /                       \     \                /        \   /   \
       /                              /                               \__            /          \ /
      /      __________     _____      _____     ___________             \                       /
    _/      |          \====\     \   /    /====|           \\========\================\         /   
   /        |     __    |    \     \ /    /     |     __     |        |    \          |           
            |    |__)   |     \     V    /      |    |__)    |        |     \         |            
            |       ___/       \        /       |           <        ┌┴┐             ┌┴┐
            |      |            |      |        |      |\    \      _ \ \           /  |
            |      |            |      |        |      | \    \    | \_| |         |    \_
            |______|            |______|        |______|  \____\    \___/      __   \     `-_
             _____     _  _     _     ___     _     ___    __ __              /   \  ''-.    \
            |   __|   | \| |   | |   |_ _|   | |   |_ _|   \ V /             /    /      \    \
            |   _|    | \\ |   | |    | |    | |    | |     \ /              |   |        |   |
            |_____|   |_|\_|   |_|    |_|    |_|    |_|     |_|              \    \      /    /
             _____     _ _     _     _       __      ___     ___              \    `-..-'    /
            |   o )   | | |   | |   | |     |  \    | __|   | o \              '-_        _-'
            |   o \   | U |   | |   | |_    | o )   | _|    |   /   - -- -        `------'
            |_____/   |___|   |_|   |___|   |__/    |___|   |_|\_\    

            Modules & Streams Scaffolder for Maximum Coding Pleasure         - -- --------- -- -


```                                                                                             
> Last update: 16 August 2016

This PyroCMS extension once installed becomes your coding companion to generate modules and [Stream Entities](#what-is-an-entity) for all of your projects coding needs. It will also help configure your module classes with routes, bindings, language file entries, seeding and much more, so you do not have to lift a finger. This extension will also scaffold your modules with helper functions, default home page, language and other essential files.


## What is an Entity

An Entity is a representation of an [Object Type](https://en.wikipedia.org/wiki/Object_type_(object-oriented_programming)) which may correspond with a Stream. For example, a **Person**, a **Company** or an **Animal** can all be represented by Streams and Entities.

The code generated for an entity includes an `Entity Model` and `Repository`, `Plugin`, `Seeder`, `Contracts`, `Table Builder` and `Form Builder`.


## Getting Started

The following example is also available here, [blog](https://github.com/websemantics/blog),

* Create and install a new PyroCMS project, let's call it `blogger`

```bash
# create a fresh pyro project,
composer create-project pyrocms/pyrocms --prefer-dist blogger

cd blogger

# initiate the install process from the command line,
php artisan install
```

* Install this extension at, `addons/default/websemantics/entity_builder-extension`

```bash
# first clone,
git clone https://github.com/websemantics/entity_builder-extension  addons/default/websemantics/entity_builder-extension

# then install
php artisan extension:install websemantics.extension.entity_builder
```

* Create a new module, `blog`

```bash
php artisan make:addon websemantics.module.blog
```

**Note:** Entity Builder overrides the core `make:addon` command for type `module` only in order to generate all the required files.

* Add `title` and `content` fields to the module's fields migration file at `blog/addons/default/websemantics/blog-module/migrations`,

```php
    protected $fields = [
        'title'                      => 'anomaly.field_type.text',
        'content'                    => 'anomaly.field_type.text'
    ];
```

* Create `posts` stream,

```bash
php artisan make:stream posts websemantics.module.blog
```

This command will also generate a seeder template for this entity (see below).

* Edit `posts` stream migration file generated at `blog/addons/default/websemantics/blog-module/migrations`, and add the following to `$stream` and `$assignments` arrays,

```php
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

* Add seeder data to, `blog/addons/default/websemantics/blog-module/resources/seeders/post.php` (singular file name)

```php
  ['title' => 'Laravel', 'content' => 'PHP framework'],
  ['title' => 'PyroCMS', 'content' => 'PHP CMS']
```

The content must be a list of entry values without `<?php`. This will be added to the Entity Seeder class when the code is (re)generated.

* Apply the changes by install/reinstall the module,

```bash
php artisan module:reinstall websemantics.module.blog
```

Check the admin panel to see the new Module in action, url: `admin/blog/posts`.

Have fun, ..


## Development

Once the entity files have been created and working correctly with Pyro, you might want to modify and develop the classes individually adding custom code.

The extension provides a configuration option to list the files that you don't want to overwrite accidentally when re-installing a module to regenerating the entity code.

For example, if you have edited the `blog-module/src/Blog/Post/PostModel.php`, make sure to list that in the builder config file, `blog-module/resources/config/builder.php` so that the extension will avoid overwriting these files if they exist.

Here's an example,

```
  'avoid_overwrite' => [
    'Model.php',
    'Repository.php',
    'TableColumns.php'
  ],
```

Notice that we used the last part of the file name (omitted the entity name) so that this can be applied globally to all generated entities of the same type.


## Configuration

The Entity Builder offers many configuration options to fine-tune and enhance your development experience which can be found at `blog-module/resources/config/builder.php`.

* Add a list of namespaces supported,

```php
  'namespaces' => [
    'blog' => [],
    'discussion' => []
  ]
```

This indicates to the builder extension the stream `namespaces` that your module supports. Add namespaces to this list as appropriate.

* Group entities code under a namespace folder,

When code is generated for an entity, the builder extension saves it under `src/{namespace}` by default. This is useful when your module handles streams from different namespaces.

To change this behaviour and store under the module `src/` folder directly, set `namespace_folder` to `false`,

```
  'namespace_folder' => true,
```

* There are two settings to the seeding option in `builder.php`,

- `yes`, Entity Builder will seed the module after install,
- `no`, Seeding is disabled

```
  'seeding' => 'yes'
```

* Specify your project docblock to be included with the generated classes,

```
'docblock' =>
' * @link      http://yourcompany.com
 * @author    name, <name@email.com>'
```

More settings are detailed in the [builder.php](https://github.com/websemantics/entity_builder-extension/blob/master/resources/assets/module/template/resources/config/builder.php) file.


## Inner Working

Once installed, this extension listens mainly to three event types, *StreamWasCreated*, *AssignmentWasCreated* and *ModuleWasInstalled*.

To enable this extension for your current module, create a config file at `resources/config/builder.php` and list the namespaces you would like the extension to generate code for. You can listen/generate code to multiple namespaces.

```php
    'namespaces' => ['blogger', 'navigation', 'etc']
```

Here's an example of the [builder config file](https://github.com/websemantics/example-module/blob/master/resources/config/builder.php) taken from [Boxed](http://websemantics.github.io/boxed) example module github repo.

Once that's done, create your Streams migration files as usual. The extension will kick in when it receives the events listed above,

* For *StreamWasCreated* event, the extension will generate an entity folder for the stream from the template stored at `entity_builder-extension/resources/assets/entity/code`. The folder map of the entity is as follows:

```
BlogEntity
  |
  +-- Contract
  |      |
  |      +--- BlogInterface.php
  |      |
  |      +--- BlogRepositoryInterface.php
  |
  |
  +-- Form
  |    |
  |    +--- BlogFormBuilder.php
  |
  |
  +-- Table
  |     |
  |     +--- BlogTableBuilder.php
  |     |
  |     +--- BlogTableColumns.php
  |
  |
  +---- BlogModule.php
  |
  +---- BlogRepository.php
  |
  +---- BlogSeeder.php
  |
  +---- BlogPlugin.php
```

By default, this folder structure would be generated in a subfolder at `src`. The name of the subfolder is the namespace of the created stream as explained above.

The extension will then generate a controller per entity at `blog-module/src/Http/Controller/Admin/BlogController.php` and modify `Module`, `ServiceProvider`, `Seeder` classes and language files to setup the entity to work correctly with the module.

* For *AssignmentWasCreated* event, the extension will modify two files, `BlogTableColumns.php` and `BlogFormBuilder.php` and add a `field` slug per stream assignment.

* For *ModuleWasInstalled* event, this will add `routes` and `sections` to the module and service provider. It will also seed the module if the builder config file was set accordingly.


## Support

Need help or have a question? post a questions at [StackOverflow](https://stackoverflow.com/questions/tagged/entity_builder-extension)

*Please don't use the issue trackers for support/questions.*


## Contribution

Well finally, we are more than happy to accept external contributions to the project in the form of feedback, bug reports and even better - pull requests :)


## Links

[PyroCMS](https://github.com/pyrocms/pyrocms), an MVC PHP Content Management System built to be easy to use, theme and develop with. It is used by individuals and organizations of all sizes around the world.

[Awesome PyroCMS](https://github.com/websemantics/awesome-pyrocms), A curated list of PyroCMS addons and resources.

[PyroCMS Cheatsheet](http://websemantics.github.io/pyrocms-cheatsheet), A list of commands and features for PyroCMS 3.

[Auto Pyro](https://github.com/websemantics/auto-pyro), PyroCMS deploy tool for faster and more pleasurable development experience.


## License

[MIT license](http://opensource.org/licenses/mit-license.php)
Copyright (c) Web Semantics, Inc.
