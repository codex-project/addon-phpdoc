<?php
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Addon\Phpdoc\Factory;
use Codex\Addon\Phpdoc\Popover;
use Codex\Contracts\Codex;
use Codex\Http\Controllers\Api\V1\ApiController;
use Illuminate\Contracts\View\Factory as ViewFactory;

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

    protected function getDoc($projectSlug, $ref = null)
    {
        $project = $this->resolveProject($projectSlug, $ref);
        $project->phpdoc->checkUpdate(config('codex-phpdoc.debug') === true);
        return $project->phpdoc;
    }

    protected function isFull()
    {
        return request()->get('full', false) === true;
    }

    public function getTree($projectSlug, $ref = null)
    {
        return $this->response($this->getDoc($projectSlug, $ref)->tree($this->isFull()));
    }

    public function getList($projectSlug, $ref = null)
    {
        return $this->response($this->getDoc($projectSlug, $ref)->getElements($this->isFull())->toArray());
    }

    public function getEntity($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);

        $entity = (string)request()->get('entity');

        if ( ! $phpdoc->hasElement($entity) )
        {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity)->toArray();

        return $this->response($entity);
    }

    public function getSource($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');
        if ( ! $phpdoc->hasElement($entity) )
        {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity);

        return $this->response($entity);
    }

    protected function getCacheKey($projectSlug, $ref, $entity, $suffix = '')
    {
        $entity = md5($entity);
        return "phpdoc.{$projectSlug}.{$ref}.{$entity}{$suffix}";
    }

    public function getDocPage($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');

        if ( ! $phpdoc->hasElement($entity) )
        {
            return $this->error('Entity does not exist');
        }


        $cache        = app('cache.store');
        $key = $this->getCacheKey($projectSlug, $ref, $entity, '.doc.');
        $lastModified = (int) $cache->get($key . '.lastModified', 0);
        if ( $lastModified !== (int) $phpdoc->getLastModified() )
        {
            $entity = $phpdoc->getElement($entity);
            $doc    = view($this->codex->view('phpdoc.entity'), $entity->toArray())->with('phpdoc', $phpdoc)->render();
            $cache->forever($key, $doc);
            $cache->forever($key . '.lastModified', (int) $phpdoc->getLastModified());
        }
        else
        {
            $doc = $cache->get($key);
        }

        return $this->response([
            'doc' => $doc,
        ]);
    }

    public function getPopover($projectSlug, $ref = null)
    {
        $phpdoc   = $this->getDoc($projectSlug, $ref);
        $name     = (string)request()->get('name');
        $segments = explode('::', $name);
        $popover  = Popover::make($phpdoc)->generate($segments[ 0 ], isset($segments[ 1 ]) ? $segments[ 1 ] : null);

        return isset($popover) ? $this->response($popover) : $this->error("Could not find '{$name}' ");
    }

}
