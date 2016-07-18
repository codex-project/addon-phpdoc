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

/**
 * This is the class File.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 *
 * @property string $source
 * @property array  $parse_markers
 * @property string $path
 * @property string $hash
 * @property Entity $entity
 *
 */
class File extends AbstractStructure
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

        // FILE level items
        $items = [
            'uses'          => [ ],
            'source'        => gzuncompress(base64_decode($data[ 'source' ])),
            'parse_markers' => $data[ 'parse_markers' ],
        ];

        if ( $data->has('namespace-alias') ) {
            $items[ 'uses' ] = is_string($data[ 'namespace-alias' ]) ? [ $data[ 'namespace-alias' ] ] : $data[ 'namespace-alias' ];
        }
        $items = array_merge($items, [
            'path'                  => $data[ '@attributes.path' ],
            'hash'                  => $data[ '@attributes.hash' ],
            'file_description'      => $this->createString($data[ 'docblock.description' ]),
            'file_long-description' => $this->createString($data[ 'docblock.long-description' ]),

        ]);


        $items[ 'type' ] = null;
        if ( $data->has('class') ) {
            $items[ 'type' ] = 'class';
        } elseif ( $data->has('trait') ) {
            $items[ 'type' ] = 'trait';
        } elseif ( $data->has('interface') ) {
            $items[ 'type' ] = 'interface';
        }

        $this->items = $items;
        if ( $items[ 'type' ] !== null ) {
            $items[ 'entity' ] = new Entity($data[ $items[ 'type' ] ], $this->factory, $this);
        }


        return $items;
    }

    public function unserialize($serialized)
    {
        parent::unserialize($serialized);
        $this->getEntity() !== null && $this->getEntity()->setBelongsTo($this);
    }

    /**
     * getEntity method
     * @return \Codex\Addon\Phpdoc\Structure\Entity
     */
    public function getEntity()
    {
        return $this[ 'entity' ];
    }

}
