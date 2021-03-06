<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2017 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
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

    'cache' => [
        'extract_path' => storage_path('codex/phpdoc'),
        'store' => 'file'
    ],

    // @deprecated
    'cache_path' => storage_path('codex/phpdoc'),

    'debug' => true, // true, false, null (null takes the app.debug value)

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


            /**
             * ss
             |---------------------------------------------------------------------
             | Phpdoc xml file path
             |---------------------------------------------------------------------
             |
             | The path to the structure.xml file. This is relative to the project's
             | version directory
             |
             */

            'xml_path' => 'structure.xml',

            'doc_path' => '_phpdoc',
            'doc_disabled_processors' => ['header', 'toc'], //'button',

            // @deprecated
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
