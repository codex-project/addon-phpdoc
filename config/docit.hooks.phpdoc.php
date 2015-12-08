<?php

return [

    /*
     |---------------------------------------------------------------------
     | Route prefix
     |---------------------------------------------------------------------
     |
     | This prefix will be used in the route to the PhpdocController.
     | This will not replace the default docit prefix, but append to it.
     |
     */

    'route_prefix'           => 'phpdoc',

    /*
    |--------------------------------------------------------------------------
    | Default Project Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default settings used to pre-populate all project
    | configuration files. These values are merged in with Docit's
    | main `default_project_config` array.
    |
    */

    'default_project_config' => [

        /*
        |--------------------------------------------------------------------------
        | Enable Phpdoc Hook
        |--------------------------------------------------------------------------
        |
        | Project's may individually enable or disable the use of the phpdoc hook.
        |
        */

        'enable_phpdoc_hook'      => false,

        /*
         |---------------------------------------------------------------------
         | Phpdoc Hook Settings
         |---------------------------------------------------------------------
         |
         | These are the phpdoc hook specific settings.
         |
         */

        'phpdoc_hook_settings' => [

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
             | Menu name
             |---------------------------------------------------------------------
             |
             | The name that will be displayed for the menu item
             |
             */

            'menu_name' => 'API Documentation',

            /*
             |---------------------------------------------------------------------
             | Phpdoc xml file path
             |---------------------------------------------------------------------
             |
             | The path to the structure.xml file. This is relative to the project's
             | version directory
             |
             */

            'path'      => 'structure.xml',

            /*
             |---------------------------------------------------------------------
             | The layout view
             |---------------------------------------------------------------------
             |
             | The layout that will be used to extend the view.
             |
             */

            'layout'    => 'docit::layouts/default',

            /*
             |---------------------------------------------------------------------
             | The view file
             |---------------------------------------------------------------------
             |
             | The view file that will be used to display phpdoc
             |
             */

            'view'      => 'docit-phpdoc::phpdoc',

            /*
             |---------------------------------------------------------------------
             | Default class
             |---------------------------------------------------------------------
             |
             | The default class to show. If null, a random class will be used.
             |
             */

            'default_class'      => null


        ]
    ]
];
