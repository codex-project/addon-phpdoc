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

class Method extends AbstractStructure
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
            'final'      => (bool)$data[ '@attributes.final' ],
            'abstract'   => (bool)$data[ '@attributes.abstract' ],
            'static'     => (bool)$data[ '@attributes.static' ],
            'line'       => (int)$data[ '@attributes.line' ],
            'visibility' => $data[ '@attributes.visibility' ],
            'namespace'  => $data[ '@attributes.namespace' ],
            'package'    => $data[ '@attributes.package' ],

            'description'      => $this->createString($data[ 'docblock.description' ]),
            'long-description' => $this->createString($data[ 'docblock.long-description' ]),

            'tags'      => [ ],
            'arguments' => [ ],
        ];

        if($data['name'] = ''){
            $desc = $data->get('docblock.description', 'asdf ' . str_random(5));
            if(is_array($desc)){
                $desc = implode("\n", $desc);
            }
            $data['name'] = camel_case(last(explode(' ', $desc)));

        }

        $items[ 'full_name' ]  = $data->get('full_name', $this->belongsTo[ 'class_name' ] . '::' . $data[ 'name' ]);
        $items[ 'class_name' ] = head(explode('::', $items[ 'full_name' ]));

        $this->items = $items;
        $items[ 'tags' ]      = $this->structures('docblock.tag', Tag::class, $data);
        $items[ 'arguments' ] = $this->structures('argument', Argument::class, $data);

        return $items;
    }


    /** @return Collection */
    public function getArguments()
    {
        return $this[ 'tags' ];
    }

    /** @return bool */
    public function hasArguments()
    {
        return count($this->get('arguments', [ ])) > 0;
    }

    /** @return Collection */
    public function getTags()
    {
        return $this[ 'tags' ];
    }

    /** @return bool */
    public function hasTags()
    {
        return count($this->get('tags', [ ])) > 0;
    }
}
