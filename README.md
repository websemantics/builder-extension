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
      /      ____________     ______      ______     _____________             \                       /
    _/      |            \====\      \   /     /====|             \\==========\================\        /   
   /        |     __      |    \      \ /     /     |      __      |          |    \          |           
            |    |__)     |     \      V     /      |     |__)     |          |     \         |            
            |             |      \          /       |             <          ┌┴┐             ┌┴┐
            |         ___/        |        |        |              \        _ \ \           /  |
            |        |            |        |        |       |\      \      | \_| |         |    \_
            |        |            |        |        |       | \      \      \___/      __   \     `-_
            |________|            |________|        |_______|  \______\               /   \  ''-.    \
             _______  __   __  ___   ___      ______   _______  ________             /    /      \    \
            |  _    ||  | |  ||   | |   |    |      | |       ||    _   |            |   |        |   |
            | |_|   ||  | |  ||   | |   |    |  _    ||    ___||   | |  |            \    \      /    /
            |       ||  |_|  ||   | |   |    | | |   ||   |___ |   |_| <              \    `-..-'    /
            |  _   | |       ||   | |   |___ | |_|   ||    ___||       |               '-_        _-'
            | |_|   ||       ||   | |       ||       ||   |___ |   |\   \    - -- -       `------'
            |_______||_______||___| |_______||______| |_______||___| \___\     

            Addons scaffolder for maximum coding pleasure                            - -- --------- -- -
```                                                                                             
> This PyroCMS 3 extension once installed becomes your coding companion to scaffold Pyro modules, themes, extensions and [stream entities](#stream-entities).


## Motivation

From the prospective of a developer, Pyro 3 reduces complexity and introduces simplicity and structure into building large scale web applications with its [modular design](https://en.wikipedia.org/wiki/Modular_design), Modules, Extension, etc and data structure abstraction ([ATD](https://en.wikipedia.org/wiki/Abstract_data_type)), realized in the [Stream Platform](https://github.com/anomalylabs/streams-platform).

However, with structure comes repetition and the need to use boilerplate code combined with predefined set of steps to integrate that with your application. Usually this is all covered in the documentation, but who has time for that?

This extension tries to remove some of that tedious work so that the developer can focus on writing business logic of the application. To achieve that, the Builder extension offers a number of commands and provide scaffolders for the different aspects of building a Pyro application.

For examples, once installed, the extension will scaffold a software structure called [Entity](#stream-entities) for every stream migration generated with the `make:stream` command. The code generated includes `Models`, `Repositories`, `Table Builders`, `Form Builders`, `Admin Controllers`, `Seeders` to name a few. And with the help of a [PHP parser](https://github.com/nikic/PHP-Parser), it will configure the module classes with routes, bindings, language file entries and even seeding and even helper functions.


## Install

In your project folder, require the extension with `composer` and then install,

```bash
composer require websemantics/builder-extension
php artisan extension:install websemantics.extension.builder
```

## Getting Started

The first command to use when developing a Pyro application with the Builder extension is `make:addon`. The extension override the core `make:addon` command to add essential files and features to the generated modules. Use the `make:addon` as per the [documentation](https://www.pyrocms.com/documentation),

#### Make Module

Provide the vendor and module name, that's all,

```bash
php artisan make:addon vendor.module.name
```

The builder extension will download the [default module template](https://github.com/pyrocms-templates/default-module) from a [registry](#the-registry) and generate all the module files. The downloaded module template is cached at `app/storage/streams/default/builder/default-module`. To download a fresh copy of the template, use `--force` option.

```bash
php artisan make:addon vendor.module.name --force
```

Note that, a fields migration with not be generated by default as per Pyro 3 default `make:addon` command behaviour as with the Builder extension, fields migrations are generated per stream migrations.

#### List Templates

This command will list all available addon templates from the registry,

```bash
php artisan builder:list
```

Result,

```
+----------------+----------------------------------------------------+
| Name           | Description                                        |
+----------------+----------------------------------------------------+
| default-module | Default module template for Pyro Builder Extension |
+----------------+----------------------------------------------------+
```

#### Make Template

This command will download and scaffold the selected addon template. If the template is already cached then it will be used by default unless the `--force` option is specified to force a fresh copy of the template.

```bash
php artisan builder:make default-module --force
```

This command also supports `--shared` and `--migrate` options. More addon templates will be available shortly.

#### Clear

This command will clear the Builder cache and delete all downloaded templates and local cache.

```bash
php artisan builder:clear
```

#### Make Stream

This is an enhanced version of Pyro 3 default `make:stream`. It introduces the ability to specify stream fields and properties.

Using the example in [Auto-pyro](https://github.com/websemantics/auto-pyro) to create a [todo module](https://github.com/websemantics/auto-pyro/blob/1.0.0/local.properties#L97), the following make:stream will create a Task stream ,

```bash
php make:stream 'task:tc(name),name,description:t(anomaly.field_type.textarea),completed:t(anomaly.field_type.boolean)' websemantics.module.todo
```

Instead of just the stream slug, the new command accepts a comma-separated list of the stream as first instance, and its fields to follow.

A colon-separated list of options following the stream or field slugs can now be specified to customize the generated stream / fields migration files. Values if applicable are provided between parentheses.

| Property        | Shorthand           | Inheritable |
| ------------- |:-------------:|:-------------:|
| title_column | `tc` | :white_medium_square: |
| type | `t` | :white_medium_square: |
| required | `r` | :white_medium_square: |
| unique | `u` | :white_medium_square: |
| searchable | `s` | :white_medium_square: |
| locked | `l` | :white_medium_square: |
| translatable | `trans` | :white_check_mark: |
| trashable | `trash` | :white_check_mark: |
| sortable | `sor` | :white_check_mark: |

The `Inheritable` column indicates the properties a stream would inherit if one of its fields has it set to true.  For example,

```bash
php make:stream 'comment:title_column(name),name:trans' vendor.module.name
# This can also be expressed as
php make:stream 'comment:title_column(name),name:translatable(true)' vendor.module.name
# Or
php make:stream 'comment:title_column(name):translatable(true),name:type(anomaly.field_type.text):translatable(true)' vendor.module.name
```

The previous will generate a streams migration with as follows,

```php
protected $stream = [
    'slug'                        => 'comment',
    'title_column'                => 'name',
    'translatable'                => true,
];

protected $assignments = [
    'name'                      
];
```

If the `type` property of a field is not set, the Builder extension will assume, `anomaly.field_type.text` as default.

## Stream Entities

In addition to scaffolding addons from Builder templates, this extension offers generating stream entities from migrations (i.e. working code). An Entity is a representation of an [Object Type](https://en.wikipedia.org/wiki/Object_type_(object-oriented_programming)) which may correspond with a Stream. For example, a **Person**, a **Company** or an **Animal** can all be represented by Streams and Entities.

The code generated for an entity includes an `Entity Model` and `Repository`, `Plugin`, `Seeder`, `Contracts`, `Table Builder` and `Form Builder`.

Please follow the next section for a better understanding of the process.

## Build a Blog

The following example is also available here, [blog](https://github.com/websemantics/blog). The idea is to build a module with the least amount of effort and understad how the Builder extension does its work.

**TODO**, use Builder commands here, `builder:list`, `builder:make` and `builder:clear`.

* Create and install a new PyroCMS project, let's call it `blogger`

```bash
# create a fresh pyro project,
composer create-project pyrocms/pyrocms --prefer-dist blogger

cd blogger

# initiate the install process from the command line,
php artisan install
```

* Install this extension at, `addons/default/websemantics/builder-extension`

```bash
# first clone,
git clone https://github.com/websemantics/builder-extension  addons/default/websemantics/builder-extension

# then install
php artisan extension:install websemantics.extension.entity_builder
```

* Create a new module, `blog`

```bash
php artisan make:addon websemantics.module.blog
```

**Note:** Builder extension overrides the core `make:addon` command for type `module` only in order to generate all the required files.

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


## The Registry

The idea is to have a collection of reusable Pyro 3 addon templates that can be scaffolded from the command line, similar to what [vue-cli](https://github.com/vuejs/vue-cli) offers.

For a full list of what's available through the Builder extension, check the registry at, [PyroCMS Templates](https://github.com/pyrocms-templates) or use `builder:list` artisan command.


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

The Builder extension offers many configuration options to fine-tune and enhance your development experience which can be found at `blog-module/resources/config/builder.php`.

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

- `yes`, Builder extension will seed the module after install,
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

More settings are detailed in the [builder.php](https://github.com/websemantics/builder-extension/blob/master/resources/assets/module/template/resources/config/builder.php) file.


## Inner Working

Once installed, this extension listens mainly to three event types, *StreamWasCreated*, *AssignmentWasCreated* and *ModuleWasInstalled*.

To enable this extension for your current module, create a config file at `resources/config/builder.php` and list the namespaces you would like the extension to generate code for. You can listen/generate code to multiple namespaces.

```php
    'namespaces' => ['blogger', 'navigation', 'etc']
```

Here's an example of the [builder config file](https://github.com/websemantics/example-module/blob/master/resources/config/builder.php) taken from [Boxed](http://websemantics.github.io/boxed) example module github repo.

Once that's done, create your Streams migration files as usual. The extension will kick in when it receives the events listed above,

* For *StreamWasCreated* event, the extension will generate an entity folder for the stream from the template stored at `builder-extension/resources/assets/entity/code`. The folder map of the entity is as follows:

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

Need help or have a question? post a questions at [StackOverflow](https://stackoverflow.com/questions/tagged/builder-extension)

*Please don't use the issue trackers for support/questions.*


## Contribution

Well finally, we are more than happy to accept external contributions to the project in the form of feedback, bug reports and even better - pull requests :)


## Screencasts

To generate screen recordings, follow these steps,

- Install [Asciinema](https://asciinema.org/) and [Asciinema 2 Gif](https://github.com/tav/asciinema2gif)

```
brew install asciinema
brew install asciinema2gif
```

Record a screencast and convert it to a gif image,

```
asciinema rec -y -t "title"
asciinema2gif --size small --speed 3 https://asciinema.org/api/asciicasts/{{id}}
```

The `asciinema` command will generate a url to the recording, for example,  `https://asciinema.org/a/e32g9nqayq1dqb9txd063ez8m`. Replace the `{{id}}` in the url provided for `asciinema2gif` with the one unique id from `asciinema` url, `e32g9nqayq1dqb9txd063ez8m`. Read the documentations for more details regarding [valid url formats](https://github.com/tav/asciinema2gif#url-format).

Copy the generated file `asciicast.gif` from the current directory to the desired location.

## Links

[PyroCMS](https://github.com/pyrocms/pyrocms), an MVC PHP Content Management System built to be easy to use, theme and develop with. It is used by individuals and organizations of all sizes around the world.

[Awesome PyroCMS](https://github.com/websemantics/awesome-pyrocms), A curated list of PyroCMS addons and resources.

[PyroCMS Cheatsheet](http://websemantics.github.io/pyrocms-cheatsheet), A list of commands and features for PyroCMS 3.

[Auto Pyro](https://github.com/websemantics/auto-pyro), PyroCMS deploy tool for faster and more pleasurable development experience.


## License

[MIT license](http://opensource.org/licenses/mit-license.php)
Copyright (c) Web Semantics, Inc.
