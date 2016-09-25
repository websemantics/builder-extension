1.0.5
  date: 2016-09-25
  changes:
  - Add support to Language filed type for Table and Form fields,
  - Make module landing page optional,
  - Fix an issue with module entity section entry by adding href attribute (occurs @ 1st section),

1.0.4
  date: 2016-09-19
  changes:
  - Add stream slug to filename of fields migrations.
  - Fix bug when stream migration file is first in order before fields migration file due to matching timestamps by introducing a 10 second delay between generating the two.

1.0.1
  date: 2016-09-17
  changes:
  - Extend `make:migration` command to accept field schemas to generate stream assignments,.
  - Change order of creating fields/stream migrations to creating fields migration first.
  - Prevent command `make:addon` from creating a fields migration by default, fields migrations are now created per stream migration.
  - Fix issues [#8](https://github.com/websemantics/builder-extension/issues/8) and [#11](https://github.com/websemantics/builder-extension/issues/11).
  - Publish as composer package.

1.0.0
  date: 2016-09-11
  changes:
  - Rebrand as `Pyro Builder`, a generic Addons & Entities scaffolder,
  - Link to Builder Addon templates registry via Github,
  - Add `builder:clear` command to clear Builder cache and stored templates,
  - Add `builder:list` command to list available Builder addon templates,
  - Add `builder:make` command to create a PyroCMS addon from a Builder template,
  - Cache Api calls to avoid Github Api Rate Limit,
  - Remove references to `SelfHandling` interface for Laravel 5.3/Pyro 3.1,
  - Other fixes for Laravel 5.3

0.9.1
  date: 2016-08-16
  changes:
  - Generate entity seeder when a stream is created,
  - Better documentation and simpler examples to follow,
  - Update logo, again!

0.9.0
  date: 2016-08-15
  changes:
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

0.8.0
  date: 2016-02-26
  changes:
  - Generates seeder command for automatic module seeding after install,
  - Enables / disables seeding from builder config

0.7.0
  date: 2016-02-25
  changes:
  - Improved documentation,
  - Automatically seeds modules after install,
  - Support for more field types,
  - Module example

0.6.0
  date: 2016-02-12
  changes:
  - Add common methods to Repository class,
  - Generate language files for stream, fields and section,
  - fixing few bugs with Entity Seeders,
  - Code updates due to changes in Pyro

0.5.0
  date: 2015-11-17
  changes:
  - Add new command to create a module

0.4.0
  date: 2015-11-5
  changes:
  - Customize table columns and form fields,
  - More control on templates,
  - Example builder config file,
  - Detailed documentation,
  - Fixing bugs

0.2.0
  date: 2015-04-5
  changes:
  - Creates streams entities,
  - Allow to group entity code in namespace folder,
  - Seeding streams
