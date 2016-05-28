<?php
namespace Codex\Addon\Phpdoc\Elements;


class Method extends AbstractXmlElement
{
    public function hasParameters()
    {
        return false;
    }

    public function countParameters()
    {
        return 0;
    }

    public function getArguments()
    {

        $arguments = [ ];
        if ( !isset($this->argument) ) {
            return collect();
        }
        foreach ( $this->argument as $p ) {
            $argument = Argument::create($p->asXML())->toArray();
            $tag      = $this->getParamTag($argument[ 'name' ]);
            if ( $tag !== null ) {
                if ( isset($tag[ 'description' ]) ) {
                    $argument[ 'description' ] = $tag[ 'description' ];
                }
            }
            $arguments [] = $argument;
        }
        return collect($arguments);
    }

    public function getParamTag($name)
    {
        $tags = Helper::docBlockTagsCollection($this->docblock);
        return $tags->where('name', 'param')->where('variable', $name)->first();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [

            'static'           => $this->attr('static') === 'true',
            'abstract'         => $this->attr('abstract') === 'true',
            'final'            => $this->attr('final') === 'true',
            'visibility'       => $this->attr('visibility'),
            'namespace'        => $this->attr('namespace'),
            'line'             => (int)$this->attr('line'),
            'description'      => (string)$this->docblock->description,
            'long-description' => (string)$this->docblock[ 'long-description' ],
            'name'             => (string)$this->name,
            'full_name'        => (string)$this->full_name,
            'default'          => (string)$this->default === 'true',
            'arguments'        => $this->getArguments()->toArray(),
            'tags'             => Helper::docBlockTags($this->docblock),
        ];

        return $arr;
    }
}