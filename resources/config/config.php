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
];
