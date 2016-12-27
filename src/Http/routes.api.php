<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author    Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license   http://codex-project.ninja/license The MIT License
 */

//
//Route::group([ 'prefix' => '{projectSlug}/{ref}' ], function () {
//    Route::get('entity', [ 'as' => 'phpdoc', 'uses' => 'PhpdocApiController@getEntity' ]);
//    Route::get('element', [ 'as' => 'phpdoc.element', 'uses' => 'PhpdocApiController@getElement' ]);
//    Route::get('list', [ 'as' => 'phpdoc.list', 'uses' => 'PhpdocApiController@getList' ]);
//    Route::get('tree', [ 'as' => 'phpdoc.tree', 'uses' => 'PhpdocApiController@getTree' ]);
//    Route::get('source', [ 'as' => 'phpdoc.source', 'uses' => 'PhpdocApiController@getSource' ]);
//    Route::get('doc', [ 'as' => 'phpdoc.doc', 'uses' => 'PhpdocApiController@getDocPage' ]);
//    Route::get('popover', [ 'as' => 'phpdoc.popover', 'uses' => 'PhpdocApiController@getPopover' ]);
//});

Route::get('entities', ['as' => 'entities', 'uses' => 'PhpdocApiController@getEntities']);
Route::get('entity', ['as' => 'entity', 'uses' => 'PhpdocApiController@getEntity']);



/*
 * /entity              index
 *  - full
 *  - tree
 * /entity/{entity}     show
 *  - fields
 *
 */
