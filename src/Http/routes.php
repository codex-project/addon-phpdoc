<?php

Route::group([ 'prefix' => '{projectSlug}/{ref}' ], function () {
    Route::get('entity', [ 'as' => 'phpdoc', 'uses' => 'PhpdocApiController@getEntity' ]);
    Route::get('list', [ 'as' => 'phpdoc.list', 'uses' => 'PhpdocApiController@getList' ]);
    Route::get('tree', [ 'as' => 'phpdoc.tree', 'uses' => 'PhpdocApiController@getTree' ]);
    Route::get('source', [ 'as' => 'phpdoc.source', 'uses' => 'PhpdocApiController@getSource' ]);
    Route::get('doc', [ 'as' => 'phpdoc.doc', 'uses' => 'PhpdocApiController@getDocPage' ]);
});
