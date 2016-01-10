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
        'hooks' => [
            'phpdoc' => [

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

                'path' => 'structure.xml',

                /*
                 |---------------------------------------------------------------------
                 | The layout view
                 |---------------------------------------------------------------------
                 |
                 | The layout that will be used to extend the view.
                 |
                 */

                'layout' => 'codex::layouts.document',

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

                'default_class' => null

            ]
        ]
    ]
];
