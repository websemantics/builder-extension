#### [1.1.7] - 2017-90-11
##### Changed
  -  Add support for new field types (credits @edster)

#### [1.1.6] - 2017-90-11
##### Changed
  -  Fix issues related to recent PyroCMS API updates

#### [1.1.5] - 2017-90-10
##### Changed
  -  Upgrade to Twig v4

#### [1.1.4] - 2017-90-10
##### Changed
  -  Upgrade to PHP-Parser v4

#### [1.1.3] - 2017-03-17
##### Changed
  - Add support for theme skin addon type.
  - Fix issue #27

#### [1.1.2] - 2016-10-30
##### Changed
  - Fix issue with override `make:addon` and `make:stream` commands, currently realized using singleton.
  - Fix issue #23

#### [1.1.1] - 2016-10-12
##### Changed
  - Fix landing page routes

#### [1.1.0] - 2016-10-03
##### Changed
  - Generate sitemap
  - Generate fields and streams permissions
  - Fix for issue #13,

#### [1.0.9]  2016-10-03
##### Changed
  - Fix, `Cannot declare class Websemantics\BuilderExtension\Console\MakeAddon because the name is already in use`

#### [1.0.8]  2016-10-02
##### Changed
  - Remove `builder:make` command and replace with command `make:addon`.
  - Improved documentation.

#### [1.0.7]  2016-09-31
##### Changed
  - Detect text files for Twig parsing,
  - Extend `make:addon` command to scaffold a starter theme (by default) or an admin theme with `--admin` option,
  - Extend `make:addon` command to scaffold templates with `--template` option.
  - Add an option `--default` to `make:addon` command to skip user interactivity.

#### [1.0.6]  2016-09-30
##### Changed
  - Use Twig parser with filters and functions,
  - Add support to `anomaly.field_type.language` and `anomaly.field_type.select` for generated entities,
  - Make module landing page optional (work in progress),
  - Add module icon per the builder config file,
  - Fix an issue with module entity section entry by adding href attribute (occurs @ 1st section),
  - Show repository stars when using `builder:list`,
  - Support for `choice` and `confirm` question types when using `options` in builder schema,
  - Refactor Entity templates to use filters and functions to produce all needed derivatives,
  - Rename some builder config variables, `seeding => seed`, `namespace_folder => group`,

#### [1.0.4] - 2016-09-19
##### Changed
  - Add stream slug to filename of fields migrations.
  - Fix bug when stream migration file is first in order before fields migration file due to matching timestamps by introducing a 10 second delay between generating the two.

#### [1.0.1] - 2016-09-17
##### Changed
  - Extend `make:migration` command to accept field schemas to generate stream assignments,.
  - Change order of creating fields/stream migrations to creating fields migration first.
  - Prevent command `make:addon` from creating a fields migration by default, fields migrations are now created per stream migration.
  - Fix issues [#8](https://github.com/websemantics/builder-extension/issues/8) and [#11](https://github.com/websemantics/builder-extension/issues/11).
  - Publish as composer package.

#### [1.0.0] - 2016-09-11
##### Changed
  - Rebrand as `Pyro Builder`, a generic Addons & Entities scaffolder,
  - Link to Builder Addon templates registry via Github,
  - Add `builder:clear` command to clear Builder cache and stored templates,
  - Add `builder:list` command to list available Builder addon templates,
  - Add `builder:make` command to create a PyroCMS addon from a Builder template,
  - Cache Api calls to avoid Github Api Rate Limit,
  - Remove references to `SelfHandling` interface for Laravel 5.3/Pyro 3.1,
  - Other fixes for Laravel 5.3

#### [0.9.1] - 2016-08-16
##### Changed
  - Generate entity seeder when a stream is created,
  - Better documentation and simpler examples to follow,
  - Update logo, again!

#### [0.9.0] - 2016-08-15
##### Changed
  - Major code cleaning and refactoring,
  - Enhanced generated code,
  - Generic helper functions for generated modules,
  - Ensure all necessary module files are generated on `make:addon` command,
  - Use [Figlet](https://github.com/packaged/figlet) font to print module name in README.md,
  - Detailed instructions in generated module's README.md,
  - Update the extension ascii logo,
  - Show the module README.md on the module Admin home page,
  - Fall back template to unrecognized field types for table and form builder
  - Many bug fixes

#### [0.8.0] - 2016-02-26
##### Changed
  - Generates seeder command for automatic module seeding after install,
  - Enables / disables seeding from builder config

#### [0.7.0] - 2016-02-25
##### Changed
  - Improved documentation,
  - Automatically seeds modules after install,
  - Support for more field types,
  - Module example

#### [0.6.0] - 2016-02-12
##### Changed
  - Add common methods to Repository class,
  - Generate language files for stream, fields and section,
  - fixing few bugs with Entity Seeders,
  - Code updates due to changes in Pyro

#### [0.5.0] - 2015-11-17
##### Changed
  - Add new command to create a module

#### [0.4.0] - 2015-11-5
##### Changed
  - Customize table columns and form fields,
  - More control on templates,
  - Example builder config file,
  - Detailed documentation,
  - Fixing bugs

#### [0.2.0] - 2015-04-5
##### Changed
  - Creates streams entities,
  - Allow to group entity code in namespace folder,
  - Seeding streams
