<?php
namespace Codex\Addon\Phpdoc\Elements;

use Sebwite\Support\Str;

class Argument extends PhpdocXmlElement
{

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [
            'reference'     => $this->attr('by_reference'),
            'name'          => (string)$this->name,
            'default'       => (string)$this->default,
            'type'          => (string)$this->type
        ];
        $arr['types'] = [];
        foreach(explode('|', $arr['type']) as $type){
            $arr['types'][] = [
                'is_class_type' => Str::startsWith($type, '\\'),
                'name' => $type === '' ? 'mixed' : $type
            ];
        }

        return $arr;
    }
}
