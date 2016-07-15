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
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Codex;
use Codex\Http\Controllers\Api\V1\ApiController;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Symfony\Component\HttpFoundation\Response;

class PhpdocApiController extends ApiController
{

    /**
     * PhpdocApiController constructor.
     *
     * @param $factory
     */
    public function __construct(Codex $codex, ViewFactory $view)
    {
        config()->set('debugbar.enabled', false);

        parent::__construct($codex, $view);
    }

    protected function getDoc($projectSlug = null, $ref = null)
    {
        $project = $this->resolveProject($projectSlug ?: request('project'), $ref ?: request('ref', null));
        if ( $project instanceof Response ) {
            return $project;
        }
        $project->phpdoc->checkUpdate();
        return $project->phpdoc;
    }

    public function getEntities()
    {
        $full   = request('full', false) === 'true';
        $tree   = request('tree', false) === 'true';
        $phpdoc = $this->getDoc();
        if ( $phpdoc instanceof Response ) {
            return $phpdoc;
        }
        if ( $tree ) {
            return $this->response($phpdoc->tree($full));
        }
        return $this->response($phpdoc->getEntities($full));
    }

    public function getEntity()
    {
        $entity = request('entity');
        $fields = request('fields', '');
        $phpdoc = $this->getDoc();
        if ( $phpdoc instanceof Response ) {
            return $phpdoc;
        }
        if ( !$phpdoc->hasEntity($entity) ) {
            return $this->error("Entity [{$entity}] could not be found");
        }
        return $this->response($phpdoc->getEntity($entity));
    }


}
