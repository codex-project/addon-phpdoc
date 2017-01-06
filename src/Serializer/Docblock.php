<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2017 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc\Serializer;

use JMS\Serializer\Annotation as Serializer;

class Docblock
{
    /**
     * @Serializer\XmlList(entry="tag", inline=true)
     * @Serializer\Type("ArrayCollection<Codex\Addon\Phpdoc\Serializer\Tag>")
     * @var \Doctrine\Common\Collections\ArrayCollection|Tag[]
     */
    public $tags;

    /**
     * @Serializer\XmlElement()
     * @Serializer\Type("string")
     * @var string;
     */
    public $description;


    /**
     * @Serializer\XmlElement()
     * @Serializer\Type("string")
     * @var string;
     */
    public $longDescription;
}