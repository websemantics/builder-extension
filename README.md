```

                                                 /'\
                                                /   \
                                               /     \
                             _                /   ..' \_                      /\/'\
                            / \              / './       \                   /     \
                          _/   \            /             \        _        / oO    \
                /'\      /  '._'\          /               \      / \      /      /''\
               /   \    /        \        /      /          \    /   \    / \_ ..'    \
              /     \  /          \__    /      /            \  /     \__/             \
                     \/              \  /      /              \/__     \                \
                      \               \/    __/                   \     \                \
                       \              /                            \     \
                                              ))))                        \__
                 _________ _______     __ooO_(O o)_Ooo____
                |          \      \   /     | (_)         \\===========\===========\
                |     __    \      \ /      |     __      |            |           |
                |    |__)    \      V      /|    |__)     |            |           |
                |            |\           / |            <            ┌┴┐         ┌┴┐
                |        ___/  |         |  |             \          _ \ \       /  |
                |       |      |         |  |      |\      \        | \_| |     |    \_
                |       |      |         |  |      | \      \        \___/  __  \      `-_
                |_______|      |_________|  |______|  |______|             /   \  ''-.    \
                 ____   __ __  ____  _     ___      ___  ____             /    /      \    \
                |    \ |  |  ||    || |   |   \    /  _||    \            |   |        |   |
                | ()  )|  |  | |  | | |\\\\    \  /  |_ | ()  )           \    \      /    /
                |     ||  |  | |  | |  ( oo) () ||    _||    /             \    `-..-'    /
                | ()  ||  :  | |  | |   (_)     ||   |_ |    \       - --   '-_        _-'
                |_____| \__,_||____||____||_____||_____||__|\_|                `------'
                Addons scaffolder for maximum coding pleasure            - -- --------- -- -

```
> This PyroCMS 3 extension once installed becomes your coding companion to scaffold Pyro modules, themes, extensions and [stream entities](#stream-entities).

## Motivation

Here are some of the thoughts and reasons behind building the builder extension,

From the prospective of a developer, Pyro 3 reduces complexity and introduces simplicity and structure into building large scale web applications with its [modular design](https://en.wikipedia.org/wiki/Modular_design), Modules, Extension, etc and data structure abstraction ([ATD](https://en.wikipedia.org/wiki/Abstract_data_type)), realized in the [Stream Platform](https://github.com/anomalylabs/streams-platform),

However, with structure comes repetition and the need to use boilerplate code combined with predefined set of steps to integrate that with your application. Usually this is all covered in the documentation, but who has time for that?

This extension tries to remove some of that tedious work so that the developer can focus on writing business logic of the application. To achieve that, the builder offers a number of commands and provide scaffolders for the different aspects of building a Pyro application,

For examples, once installed, the extension will scaffold a software structure called [Entity](#stream-entities) for every stream migration generated with the `make:stream` command. The code generated includes `Models`, `Repositories`, `Table Builders`, `Form Builders`, `Admin Controllers`, `Seeders` to name a few. And with the help of a [PHP parser](https://github.com/nikic/PHP-Parser), it will configure the module classes with routes, bindings, language file entries and even seeding and even helper functions.

## Install

In your project folder, require the extension with `composer` and then install as follows,

```bash
composer require websemantics/builder-extension
php artisan extension:install websemantics.extension.builder
```

## Getting Started

The first command to use when developing a Pyro application with the builder extension is `make:addon`. The default `make:addon` command has been extended to enable [*template-based*](#make-template) code scaffolding.

## Make Addon

Use the `make:addon` command as per usual, [documentation](https://www.pyrocms.com/documentation) to create modules, themes and other addon types. However, there are two new concepts that this extension introduces,

1- A [default template](#environment-variables) is prescribed for each addon type (if [available](#list-templates)) that the builder downloads and uses to generate code.

2- Each template come with a schema (a list of variable, for example, `vendor`, `slug` etc) that the builder uses to scaffold code. These variables have default values but the user will be ask to change their values interactively unless the `--defaults` option is used to [skip](#make-addon) the process.

#### Make Module

```bash
php artisan make:addon vendor.module.name
```

The previous command creates a module based on the [default module](https://github.com/pyrocms-templates/default-module) template. The downloaded module template will be cached  at `storage/streams/default/builder/default-module`.

**NOTE**: A fields migration file will not be generated as per Pyro 3 default `make:addon` command behaviour. The builder extension generates a separate fields migration file for each [stream migration](#make-stream) instead.

#### Make Theme

There are two templates to generate a theme addon, an [*admin*](https://github.com/pyrocms-templates/pyrocms-theme) and a [*front-end*](https://github.com/pyrocms-templates/starter-theme).

```bash
php artisan make:addon vendor.theme.name --force --admin
```

The `--force` option forces the extension to download a fresh copy of the template and `--admin` to create an *admin* theme. Omit `--admin` option to create a *front-end* theme.

#### Make Addon

The previous uses of `make:addon` make assumptions about the template to use when creating the addon ([default module](https://github.com/pyrocms-templates/default-module), [pyrocms-theme](https://github.com/pyrocms-templates/pyrocms-theme) etc). This behaviour can be changed by providing an optional template name, `default-module` as follows,

```bash
php artisan make:addon websemantics.module.name default-module --defaults
```

The `defaults` options disables the user interactive mode and forces the template defaults. This command also supports `--shared` and `--migrate` options.

#### Make Template

A template is like a recipe that the extension uses to scaffold code for an addon type, or any software package for that matter,

```bash
php artisan make:addon websemantics.module.name --template
```

The previous command will create a template for the specified addon type, `defaults` and uses `vendor` and `slug` provided as default values for the template schema. More details about templates and how to use them locally can be found at [template-template documentation](https://github.com/pyrocms-templates/template-template).

## List Templates

This command will list all available addon templates from the [registry](#the-registry),

```bash
php artisan builder:list
```

Result,

```
 ____   __ __  ____  _     ___      ___  ____
|    \ |  |  ||    || |   |   \    /  _||    \
| ()  )|  |  | |  | | |\\\\    \  /  |_ | ()  )
|     ||  |  | |  | |  ( oo) () ||    _||    /
| ()  ||  :  | |  | |   (_)     ||   |_ |    \
|_____| \__,_||____||____||_____||_____||__|\_|
Addons scaffolder for maximum coding pleasure

Builder Commands:
- make:addon Create an addon from a template if avilable
- builder:list List available templates from the registry
- builder:clear Clear cache and locally stored templates

Retrieving a list of available templates
+-------------------+-----------------------------------------------------------------------+---+
| Name              | Description                                                           | ★ |
+-------------------+-----------------------------------------------------------------------+---+
| default-module    | The default module template for Pyro Builder                          | 2 |
| default-skin      | The default skin template for PyroCMS Plus Theme                      | 0 |
| pyrocms-theme     | A customizable release of the official PyroCMS admin theme            | 2 |
| starter-theme     | A customizable release of PyroCMS started theme                       | 4 |
| template-template | The default Pyro Builder template for building Pyro Builder templates | 3 |
+-------------------+-----------------------------------------------------------------------+---+
```

Notice how each template has one star only! :/ Well, you can change that by liking these repos [here](https://github.com/pyrocms-templates) :)

## Clear Cache

To clear the builder cache and delete all locally downloaded templates run the following command,

```bash
php artisan builder:clear
```

## Make Stream

The builder introduces new abilities to `make:stream` command, for example, to specify stream properties and fields,

Using the example provided in [Auto-pyro](https://github.com/websemantics/auto-pyro) to create a [todo module](https://github.com/websemantics/auto-pyro/blob/1.0.0/local.properties#L97), the following will create a `Task` stream and all of it's assignments,

```bash
php make:stream 'task:tc(name),name,description:t(anomaly.field_type.textarea),completed:t(anomaly.field_type.boolean)' websemantics.module.todo
```

Instead of specifying the stream `slug` only, `make:stream` command now accepts a comma-separated list of fields following the stream slug and properties.

The following is a complete list of options available to use with streams and field. Option values - if applicable - are provided between parentheses (as in the example above),

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
# First, generate the module
php artisan make:addon vendor.module.name

# Then generate the stream migration files
php artisan make:stream 'comment:title_column(name),name:trans' vendor.module.name

# This can also be expressed as
php artisan make:stream 'comment:title_column(name),name:translatable(true)' vendor.module.name

# Or as,
php artisan make:stream 'comment:title_column(name):translatable(true),name:type(anomaly.field_type.text):translatable(true)' vendor.module.name
```

The previous will generate a stream migration as follows,

```php
  protected $stream = [
			'slug'                        => 'comment',
			'title_column'                => 'name',
			'translatable'                => true
	];

  protected $assignments = [
			'name'                          => [
					'translatable'              => true
			]
	];
```

If the `type` property of a field is not set, the builder extension will assume `anomaly.field_type.text` as default.

#### Environment Variables

All environment variables used by the extension are listed here,

|  Variable        | Description     | Default |
| ------------- |-------------|-------------|
| *BUILDER_REGISTRY* | The default Builder Extension registry | `pyrocms-templates` |
| *BUILDER_DEFAULT_TEMPLATE* | Template of templates to create an addon template | `template-template` |
| *BUILDER_DEFAULT_MODULE* | Default template for modules | `default-module` |
| *BUILDER_DEFAULT_ADMIN* | Default template for admin themes | `pyrocms-theme` |
| *BUILDER_DEFAULT_FRONT* | Default template for front-end themes | `starter-theme` |
| *BUILDER_TTL* | Time used to cache an API call | `60` minutes |
| *BUILDER_PATH* | Path to the local storage to cache templates | `builder` |
| *BUILDER_ARCHIVE_URL* | Url to the compressed file of the template repo| `url-template` |
| *BUILDER_TMP* | Temporary folder to uncompress zip files | `folder` |
| *MIGRATION_PADDING* | Padding between arrays `(key,value)`s in migration file | `30` chars |

Configuration file can be found at, `./resources/config/config.php`. Overridden in project `.env` file to change the default behaviour.

## Stream Entities

In addition to scaffolding addons from builder templates, the builder offers generating stream entities from migrations. An Entity is a representation of an [Object Type](https://en.wikipedia.org/wiki/Object_type_(object-oriented_programming)) which may correspond with a Stream. For example, a **Person**, a **Company** or an **Animal** can all be represented by Streams and Entities.

The code generated for an entity includes an `Entity Model` and `Repository`, `Plugin`, `Seeder`, `Contracts`, `Table Builder` and `Form Builder`.

Please read the following section for a better understanding of this process.

## Building a Blog

The source code of this example is available [here](https://github.com/websemantics/blog). The idea is to build a module with the least amount of effort and experience how the builder extension does its work,

* Create and install a new PyroCMS project and name it `blogger`,

```bash
# first, create a fresh pyro project and change folder,
composer create-project pyrocms/pyrocms --prefer-dist blogger
cd blogger

# install from the command line,
php artisan install
```

* Install the builder extension,

```bash
composer require websemantics/builder-extension
php artisan extension:install websemantics.extension.builder
```

* Create a new module, `blog`

```bash
php artisan make:addon websemantics.module.blog
```

* Create `posts` stream and its fields,

```bash
php artisan make:stream 'posts:tc(title),title:r:u,content:r' websemantics.module.blog
```

The result,

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

This will also generate a seeder template for this entity (see below),

* Add seeder data to, `blog/addons/default/websemantics/blog-module/resources/seeders/post.php` (singular file name)

```php
  ['title' => 'Laravel', 'content' => 'PHP framework'],
  ['title' => 'PyroCMS', 'content' => 'PHP CMS']
```

The content must be a list of entry values without `<?php`. This will be added to the Entity Seeder class when the code is (re)generated. This process will be enhanced in the near future with better code parsing.

* Apply the changes by install/reinstall the module,

```bash
php artisan module:reinstall websemantics.module.blog
```

Check the admin panel to see the new Module in action, url: `admin/blog/posts`.

## The Registry

The idea is to have a collection of reusable Pyro 3 addon templates that can be scaffolded from the command line, similar to what [vue-cli](https://github.com/vuejs/vue-cli) offers.

For a full list of what's available through the Builder extension, check the registry at, [PyroCMS Templates](https://github.com/pyrocms-templates) or use [`builder:list`](#list-templates) artisan command.

## Development

Once the addon code have been generated and entity files per stream have been created and working correctly with Pyro, you might want to modify and develop the classes individually adding custom code,

The extension provides a configuration options to make your work easy, for example to list the files that you don't want to overwrite accidentally when re-installing a module to regenerating the entity code,

Another example, if you have edited the `blog-module/src/Blog/Post/PostModel.php`, make sure to list that in the builder config file, `blog-module/resources/config/builder.php` so that the extension will avoid overwriting these files if they exist.

Here's an example,

```
  'avoid_overwrite' => [
    'Model.php',
    'Repository.php',
    'TableColumns.php'
  ],
```

Notice how only the last part of the file name (omitted the entity name) is used so that this can be applied globally to all generated entities of the same type,

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

When code is generated for an entity, the builder extension saves it under `src/{namespace}` by default. This is useful when your module handles streams from different namespaces,

To change this behaviour and store under the module `src/` folder directly, set the `group` option to `false`,

```
  'group' => true,
```

* To seed after a successful install, set the `seed` option in `builder.php`,

```
  'seed' => true
```

Turn on and off with `true` / `false` values as needed.

* To generate fields and streams permissions set `permissions` to true,

```
  'permissions' => true
```

* To generate sitemap, set the stream_slug for the module main stream, for example `pages` stream,

```
	'sitemap' => [ 'stream_slug' => 'pages',
                 'url_method' => '->getPath()',
                 'entries_method' => '->accessible()->visible()',
                 'priority' => 0.5,
                 'frequency' => 'monthly',
                 'images' => '[]',
                 'title' => 'null'
               ],
```

The `url_method` is used to retrieve the resource path, and `entries_method` should return a filtered collection of stream entries.

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

Once that's done, create your streams migration files as usual. The extension will kick in when it receives the events listed above,

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

By default, this folder structure would be generated in a subfolder at `src`. The name of the subfolder is the *namespace* of the created stream as explained above.

The extension will then generate a controller per entity at `blog-module/src/Http/Controller/Admin/BlogController.php` and modify `Module`, `ServiceProvider`, `Seeder` classes and language files to setup the entity to work correctly with the module.

* For *AssignmentWasCreated* event, the extension will modify two files, `BlogTableColumns.php` and `BlogFormBuilder.php` and add a `field` slug per stream assignment.

* For *ModuleWasInstalled* event, this will add `routes` and `sections` to the module and service provider. It will also seed the module if the builder config file was set accordingly.

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

## Support

Need help or have a question? post a questions at [StackOverflow](https://stackoverflow.com/questions/tagged/builder-extension)

*Please don't use the issue trackers for support/questions.*

## Contribution

Well finally, we are more than happy to accept external contributions to the project in the form of feedback, bug reports and even better - pull requests :)

## Resources

- [Auto Pyro](https://github.com/websemantics/auto-pyro), a PyroCMS deploy tool for faster development experience.
- [Template Template](https://github.com/pyrocms-templates/template-template), The default Pyro Builder template for building Pyro Builder templates.
- [Registry](https://github.com/pyrocms-templates),a list of all Pyro Builder available template.
- [Awesome PyroCMS](https://github.com/websemantics/awesome-pyrocms), a curated list of PyroCMS addons and resources.
- [PyroCMS Cheatsheet](http://websemantics.github.io/pyrocms-cheatsheet), A list of commands and features for PyroCMS 3.
- [PyroCMS](https://github.com/pyrocms/pyrocms), an MVC PHP Content Management System built to be easy to use, theme and develop with. It is used by individuals and organizations of all sizes around the world.

## License

[MIT license](http://opensource.org/licenses/mit-license.php)
Copyright (c) Web Semantics, Inc.
