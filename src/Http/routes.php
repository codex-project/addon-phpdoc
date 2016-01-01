<?php

Route::get(config('codex.hooks.phpdoc.route_prefix') . '/{projectName}/{ref?}', [
    'as'   => 'codex.phpdoc',
    'uses' => 'PhpdocController@show'
]);
