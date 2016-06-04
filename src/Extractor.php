<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\PhpdocXmlElement;

/**
 * Extracts/Transforms a PHPDocumentor XML file
 *
 * @package        App\Phpdoc
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 */
class Extractor
{
    /** @var \Codex\Addon\Phpdoc\ProjectPhpdoc */
    protected $phpdoc;

    /** @var PhpdocXmlElement */
    protected $doc;


    /** @var \Sebwite\Filesystem\Filesystem  */
    protected $fs;

    /**
     * The raw XML string
     * @var string
     */
    protected $raw;

    protected $manifest = [ ];

    protected $itemHandlers = [ 'handleCreateItem', 'handleManifestItem' ];

    protected $rawResolver;

    public function __construct(ProjectPhpdoc $phpdoc)
    {
        $this->phpdoc      = $phpdoc;
        $this->fs          = $phpdoc->getFs();
        $this->rawResolver = function(){};
    }


    public function run()
    {
        $this->init();
        $this->createInfoFile();
        $this->runItemLoop();
        $this->createManifest();
    }

    protected function init()
    {
        $this->fs->isDirectory($this->phpdoc->getCachePath()) && $this->fs->deleteDirectory($this->phpdoc->getCachePath());
        $this->fs->ensureDirectory($this->phpdoc->getCachePath());
        $this->raw = call_user_func_array($this->rawResolver, [$this]);
        $this->doc = PhpdocXmlElement::create($this->raw);
    }

    protected function runItemLoop()
    {
        foreach ( $this->doc->file as $i => $file ) {
            foreach ( PhpdocXmlElement::getTypes() as $type ) {
                if ( isset($file->{$type}) ) {
                    foreach ( $this->itemHandlers as $handler ) {
                        $this->$handler($file, $type);
                    }
                }
            }
        }
    }

    protected function handleCreateItem(PhpdocXmlElement $file, $type)
    {
        $typeInfo = $file->{$type};
        $file->addChild('type', $type);
        $fileName = Util::toFileName($typeInfo->full_name, '.xml');
        $this->writeCacheFile($fileName, $file->asXML());
    }

    protected function handleManifestItem(PhpdocXmlElement $file, $type)
    {

        $this->manifest[] = [
            'type'      => $type,
            'name'      => (string)$file->{$type}->name,
            'full_name' => (string)$file->{$type}->full_name,
            'namespace' => (string)$file->{$type}->getAttribute('namespace'),
        ];
    }

    protected function createInfoFile()
    {
        $info                 = $this->doc->getAttributes();
        $info[ 'deprecated' ] = $this->doc->deprecated->getAttributes();

        $this->writeCacheFile('info.php', Util::exportArray($info));
    }

    protected function createManifest()
    {
        $this->writeCacheFile('manifest.php', Util::exportArray($this->manifest));
    }

    protected function writeCacheFile($fileName, $content)
    {
        $this->phpdoc->getFs()->put($this->phpdoc->getCacheFilePath($fileName), $content);
    }

    /**
     * Set the rawResolver value
     *
     * @param \Closure $rawResolver
     */
    public function setRawResolver($rawResolver)
    {
        $this->rawResolver = $rawResolver;
    }

    /**
     * @return ProjectPhpdoc
     */
    public function getPhpdoc()
    {
        return $this->phpdoc;
    }

    /**
     * @return \Sebwite\Filesystem\Filesystem
     */
    public function getFs()
    {
        return $this->fs;
    }

    /**
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @return array
     */
    public function getManifest()
    {
        return $this->manifest;
    }



}