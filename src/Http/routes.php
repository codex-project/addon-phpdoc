<?php

Route::get(config('codex.hooks.phpdoc.route_prefix') . '/{projectSlug}/{ref?}', [
    'as'   => 'codex.hooks.phpdoc',
    'uses' => 'PhpdocController@show'
]);
