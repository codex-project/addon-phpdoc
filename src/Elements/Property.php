<?php
namespace Codex\Addon\Phpdoc\Elements;


use Sebwite\Support\Str;

class Property extends PhpdocXmlElement
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [

            'static'      => $this->attr('static') === 'true',
            'visibility'  => $this->attr('visibility'),
            'namespace'   => $this->attr('namespace'),
            'description' => (string)$this->docblock->description,
            'name'        => (string)$this->name,
            'full_name'   => (string)$this->full_name,
            'default'     => (string)$this->default === 'true',
            'tags'        => Helper::docBlockTags($this->docblock),
            'type'        => $this->resolveType(),
        ];

        return $arr;
    }

    public function resolveType()
    {
        $tags = Helper::docBlockTags($this->docblock);
        if ( array_key_exists('var', $tags) ) {
            return $tags[ 'var' ][ 'type' ];
        }
        return 'mixed';
    }

}
