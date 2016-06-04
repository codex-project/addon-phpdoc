<?php

return [

    /*
     |---------------------------------------------------------------------
     | Route prefix
     |---------------------------------------------------------------------
     |
     | This prefix will be used in the route to the PhpdocController.
     | This will not replace the default codex prefix, but append to it.
     |
     */

    'route_prefix' => 'phpdoc',

    'cache_path' => storage_path('codex/phpdoc'),

    /*
    |--------------------------------------------------------------------------
    | Default Project Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default settings used to pre-populate all project
    | configuration files. These values are merged in with Codex's
    | main `default_project_config` array.
    |
    */

    'default_project_config' => [
        /*
         |---------------------------------------------------------------------
         | Phpdoc Hook Settings
         |---------------------------------------------------------------------
         |
         | These are the phpdoc hook specific settings.
         |
         */
        'phpdoc' => [


            'enabled'       => false,


            'document_slug' => 'phpdoc',

            /*
             |---------------------------------------------------------------------
             | Title
             |---------------------------------------------------------------------
             |
             | The name that will be displayed
             |
             */

            'title' => 'Api Documentation',


            /*
             |---------------------------------------------------------------------
             | Phpdoc xml file path
             |---------------------------------------------------------------------
             |
             | The path to the structure.xml file. This is relative to the project's
             | version directory
             |
             */

            'path' => 'structure.xml',

            /*
             |---------------------------------------------------------------------
             | The view file
             |---------------------------------------------------------------------
             |
             | The view file that will be used to display phpdoc
             |
             */

            'view' => 'codex-phpdoc::document',

            /*
             |---------------------------------------------------------------------
             | Default class
             |---------------------------------------------------------------------
             |
             | The default class to show. If null, a random class will be used.
             |
             */

            'default_class' => null,

        ],
    ],
];