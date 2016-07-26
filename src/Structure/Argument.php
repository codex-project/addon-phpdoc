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

class Argument extends AbstractStructure
{


    /**
     * transform method
     *
     * @param array|Collection $data
     *
     * @return mixed
     */
    protected function transform($data)
    {
        $items = [
            'byref'       => (bool)$data[ '@attributes.by_reference' ],
            'types'       => explode('|', $this->createString($data[ 'type' ])),
            'description' => '',
        ];

        $this->copy([ 'name', 'default' ], $data, $items);
        //$data['default']
        return $items;
    }
}
