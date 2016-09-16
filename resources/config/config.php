<?php

return [

      /*
      |--------------------------------------------------------------------------
      | Github Registry Username
      |--------------------------------------------------------------------------
      |
      | The default Builder Extension registery
      |
      */
      'registry'           => env('BUILDER_REGISTRY', 'pyrocms-templates'),

      /*
      |--------------------------------------------------------------------------
      | Default Module
      |--------------------------------------------------------------------------
      |
      | The default Builder module template used by make:addon overridden command
      | to generate a module that's compatible with the Builder extension entities
      |
      |
      */
      'default-module' => env('BUILDER_DEFAULT_MODULE', 'default-module'),

      /*
      |--------------------------------------------------------------------------
      | Cache Minutes
      |--------------------------------------------------------------------------
      |
      | How long an API should be cached
      |
      |
      */
      'ttl' => env('BUILDER_TTL', 60),

      /*
      |--------------------------------------------------------------------------
      | Public path
      |--------------------------------------------------------------------------
      |
      | Where templates are stored in the public folder
      |
      | i.e. 'app/storage/streams/default/builder'
      |
      |
      */
      'path' => env('BUILDER_PATH', 'builder'),

      /*
      |--------------------------------------------------------------------------
      | Builder template archive url
      |--------------------------------------------------------------------------
      |
      | Url template used to download a compressed template from its repo
      |
      |
      */
      'archive' => env('BUILDER_ARCHIVE_URL', 'http://github.com/{{ registry }}/{{ template }}/archive/master.zip'),

      /*
      |--------------------------------------------------------------------------
      | Builder template temp file
      |--------------------------------------------------------------------------
      |
      | Location and name of template compressed file which is stored in the
      | builder path (see above)
      |
      |
      */
      'tmp' => env('BUILDER_TMP', 'tmp/master.zip'),

      /*
      |--------------------------------------------------------------------------
      | Migration attributes spacing
      |--------------------------------------------------------------------------
      |
      | Padding (number of chars) between the 'key', 'value' of
      | associative arrays inside a migration files (keep'me tidy)
      |
      |
      */
      'padding' => env('MIGRATION_PADDING', '30'),


];
