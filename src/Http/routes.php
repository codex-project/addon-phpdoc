<?php

Route::group([ 'prefix' => '{projectSlug}/{ref}' ], function () {
    Route::get('entity', [ 'as' => 'phpdoc', 'uses' => 'PhpdocApiController@getEntity' ]);
    Route::get('element', [ 'as' => 'phpdoc.element', 'uses' => 'PhpdocApiController@getElement' ]);
    Route::get('list', [ 'as' => 'phpdoc.list', 'uses' => 'PhpdocApiController@getList' ]);
    Route::get('tree', [ 'as' => 'phpdoc.tree', 'uses' => 'PhpdocApiController@getTree' ]);
    Route::get('source', [ 'as' => 'phpdoc.source', 'uses' => 'PhpdocApiController@getSource' ]);
    Route::get('doc', [ 'as' => 'phpdoc.doc', 'uses' => 'PhpdocApiController@getDocPage' ]);
    Route::get('popover', [ 'as' => 'phpdoc.popover', 'uses' => 'PhpdocApiController@getPopover' ]);
});


Route::resource('entity', 'PhpdocApiRestController', [
    'only' => 'index', 'show'
]);

/*
 * /entity              index
 *  - full
 *  - tree
 * /entity/{entity}     show
 *  - fields
 *
 */
