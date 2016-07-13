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

class Constant extends AbstractStructure
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

        return [
            'name'      => $data[ 'name' ],
            'full_name' => $data[ 'full_name' ],
            'value'     => $data[ 'value' ],
            'namespace' => $data[ '@attributes.namespace' ],
            'line'      => (int)$data[ '@attributes.line' ],
        ];
    }
}
