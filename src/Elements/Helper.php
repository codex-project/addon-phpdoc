<?php
namespace Codex\Addon\Phpdoc\Elements;

class Helper
{
    public static function docBlockTags(PhpdocXmlElement $docblock)
    {
        $tags = [ ];
        if ( !isset($docblock->tag) ) {
            return $tags;
        }
        foreach ( $docblock->tag as $tag ) {
            /** @var Element $tag */
            $attr                    = $tag->getAttributes();
            $tags[ $attr[ 'name' ] ] = $attr;
        }
        return $tags;
    }

    public static function docBlockTagsCollection(PhpdocXmlElement $docblock)
    {
        $tags = [ ];
        if ( !isset($docblock->tag) ) {
            return collect();
        }
        foreach ( $docblock->tag as $tag ) {
            /** @var Element $tag */
            $tags[] = $tag->getAttributes();
        }
        return collect($tags);
    }
}
