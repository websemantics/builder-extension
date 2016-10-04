<?php

use {{vendor|studly_case}}\{{module_name}}Module\{{entity_name}}\Contract\{{entity_name}}Interface;
use {{vendor|studly_case}}\{{module_name}}Module\{{entity_name}}\Contract\{{entity_name}}RepositoryInterface;
use Illuminate\Contracts\Config\Repository;
use Roumen\Sitemap\Sitemap;

return [
    'lastmod' => function ({{entity_name}}RepositoryInterface ${{repository_name}}) {

        if (!$post = ${{repository_name}}->lastModified()) {
            return null;
        }

        return $post->lastModified()->toAtomString();
    },
    'entries' => function ({{entity_name}}RepositoryInterface ${{repository_name}}) {

        /* @var \{{vendor|studly_case}}\{{module_name}}Module\{{entity_name}}\{{entity_name}}Collection ${{repository_name}} */
        return ${{repository_name}}{{config.sitemap.entries_method|raw}};
    },
    'handler' => function (Sitemap $sitemap, Repository $config, {{entity_name}}Interface $entry) {

        $translations = [];

        foreach ($config->get('streams::locales.enabled') as $locale) {
            if ($locale != $config->get('streams::locales.default')) {
                $translations[] = [
                    'language' => $locale,
                    'url'      => url($locale . $entry{{config.sitemap.url_method|raw}}),
                ];
            }
        }

        $sitemap->add(
            url($entry{{config.sitemap.url_method|raw}}),
            $entry->lastModified()->toAtomString(),
            {{config.sitemap.priority}},
            '{{config.sitemap.frequency}}',
            {{config.sitemap.images}},
            {{config.sitemap.title}},
            $translations
        );
    },
];
