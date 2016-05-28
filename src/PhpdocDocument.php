<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Addon\Phpdoc;

use Codex\Core\Documents\Document;
use Codex\Core\Projects\Project;

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

    protected $phpdoc;

    public function __construct($codex, Project $project, $path, $pathName)
    {
        $pathName = 'phpdoc';
        parent::__construct($codex, $project, $path, $pathName);
        $this->mergeAttributes($project->config('phpdoc'));
        $this->phpdoc = new ProjectPhpdoc($project);

    }

    public function render()
    {
        $entities = $this->phpdoc->collection();
        $tree = $this->phpdoc->tree();
        $data = compact('entities', 'tree');
        return $data;
    }

    /**
     * Get the url to this document
     *
     * @return string
     */
    public function url()
    {
        return route('codex-addons.phpdoc', [
            'projectName' => $this->getProject()->getName(),
            'ref'         => $this->getProject()->getRef(),
        ]);
    }

    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->getProject()->getSidebarMenu()->getBreadcrumbToHref($this->url());
    }

}
