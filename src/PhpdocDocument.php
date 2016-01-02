<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Hooks\Phpdoc;

use Codex\Core\Contracts\Codex;
use Codex\Core\Document;
use Codex\Core\Project;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * This is the PhpdocDocument.
 *
 * @package        Codex\Hooks
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class PhpdocDocument extends Document
{
    /**
     * @var \Codex\Hooks\Phpdoc\PhpdocParser2
     */
    protected $parser;

    public function __construct(Codex $codex, Filesystem $files, Project $project, Container $container)
    {
        ini_set('memory_limit', '2G');
        $path     = $project->path($project->config('hooks.phpdoc.path'));
        $pathName = 'phpdoc';
        parent::__construct($codex, $files, $project, $container, $path, $pathName);
        $this->parser = new PhpdocParser;
        $this->mergeAttributes($project->config('hooks.phpdoc'));

        $this->setPath($path);
    }

    public function render()
    {

        $key          = md5($this->getPath());
        $lastModified = $this->files->lastModified($this->getPath());

        $generate = false;
        if ($lastModifiedCache = \Cache::get("{$key}.lastmodified", false) !== false && \Cache::has($key)) {
            if ($lastModified !== $lastModified) {
                $generate = true;
            }
        } else {
            $generate = true;
        }
        if (config('app.debug')) {
            $generate = true;
        }
        $rendered = '';
        if ($generate === true) {
            $rendered = $this->parser->parse($this->files->get($this->getPath()));
            \Cache::forever($key, $rendered);
            \Cache::forever("{$key}.lastmodified", $lastModified);
        } else {
            $rendered = \Cache::get($key, false);
        }

        return $rendered;
    }


    /**
     * Get the url to this document
     *
     * @return string
     */
    public function url()
    {
        return route('codex.phpdoc', [
            'projectName' => $this->project->getName(),
            'ref'         => $this->project->getRef()
        ]);
    }

    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->project->getDocumentsMenu()->getBreadcrumbToHref($this->url());
    }
}
