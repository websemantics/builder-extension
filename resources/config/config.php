<?php

return [

      /*
      |--------------------------------------------------------------------------
      | Github Registry Username
      |--------------------------------------------------------------------------
      |
      | The default Builder Extension registery organization
      |
      */
      'registry'           => env('BUILDER_REGISTRY', 'pyrocms-templates'),

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
      | Url template used to download a template from its repo
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


];
