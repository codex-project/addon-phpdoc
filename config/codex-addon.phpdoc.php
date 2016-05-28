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
             | Menu name
             |---------------------------------------------------------------------
             |
             | The name that will be displayed for the menu item
             |
             */

            'title' => 'Api Documentation',

            'menu_name' => 'API Documentation',

            /*
             |---------------------------------------------------------------------
             | Menu icon class
             |---------------------------------------------------------------------
             |
             | The menu icon class will be added to the icon tag. Uses font-awesome
             |
             */

            'menu_icon' => 'fa fa-code',

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

            'view' => 'codex-phpdoc::phpdoc',

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