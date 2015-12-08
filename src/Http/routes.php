<?php

Route::get(config('docit.hooks.phpdoc.route_prefix') . '/{projectName}/{ref?}', [
    'as'   => 'docit.phpdoc',
    'uses' => 'PhpdocController@show'
]);
