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

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 *
 * @Serializer\XmlRoot("project")
 */
class Project
{
    /**
     * @Serializer\XmlAttribute()
     * @Serializer\Type("Codex\Addon\Phpdoc\Serializer\Version")
     * @var Version
     */
    public $version;

    /**
     * @Serializer\XmlAttribute()
     * @Serializer\Type("string")
     * @var string
     */
    public $title;

    /**
     * @var ArrayCollection|File[]
     * @Serializer\XmlMap(entry="file", inline=true, keyAttribute="path")
     * @Serializer\Type("ArrayCollection<Codex\Addon\Phpdoc\Serializer\File>")
     */
    public $files;
}