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
namespace Codex\Addon\Phpdoc\Structure;

use Codex\Support\Collection;

class Tag extends AbstractStructure
{

    /**
     * transform method
     *
     * @param array|Collection $data
     *
     * @return array
     */
    protected function transform($data)
    {
        $items = $data->get('@attributes', [])->toArray();
        if ( isset($items[ 'line' ]) ) {
            $items[ 'line' ] = (int)$items[ 'line' ];
        }
        return $items;
    }
}
