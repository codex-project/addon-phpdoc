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
use Sebwite\Support\Str;

class Property extends AbstractStructure
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
            'name'       => $data[ 'name' ],
            'static'     => (bool)$data[ '@attributes.static' ],
            'line'       => (int)$data[ '@attributes.line' ],
            'visibility' => $data[ '@attributes.visibility' ],
            'namespace'  => $data[ '@attributes.namespace' ],
            'package'    => $data[ '@attributes.package' ],

            'description'      => $this->createString($data[ 'docblock.description' ]),
            'long-description' => $this->createString($data[ 'docblock.long-description' ]),
        ];

        $items[ 'full_name' ]  = $data->get('full_name', $this->belongsTo[ 'full_name' ] . '::' . Str::removeLeft($data[ 'name' ], '$'));
        $items[ 'class_name' ] = head(explode('::', $items[ 'full_name' ]));
        $items[ 'inherited' ]  = $this->belongsTo[ 'full_name' ] !== $items[ 'class_name' ];
        $items[ 'tags' ]       = $this->structures('docblock.tag', Tag::class, $data);

        $types = $data->get('docblock.tag.type', 'mixed');
        if ( !is_array($types) ) {
            $types = [ $types ];
        }
        $items[ 'types' ] = $types;

        return $items;
    }
}
