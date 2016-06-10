<?php
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Addon\Phpdoc\Factory;
use Codex\Addon\Phpdoc\Popover;
use Codex\Contracts\Codex;
use Codex\Http\Controllers\Api\V1\ApiController;
use Illuminate\Contracts\View\Factory as ViewFactory;

class PhpdocApiController extends ApiController
{
    protected $factory;

    /**
     * PhpdocApiController constructor.
     *
     * @param $factory
     */
    public function __construct(Codex $codex, ViewFactory $view, Factory $factory)
    {
        parent::__construct($codex, $view);
        $this->factory = $factory;
    }

    protected function getDoc($projectSlug, $ref = null)
    {
        $doc = $this->factory->make($this->resolveProject($projectSlug, $ref));
        $doc->checkUpdate(config('codex-phpdoc.debug', config('app.debug')) === true);
        return $doc;
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

        if ( !$phpdoc->hasElement($entity) ) {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity)->toArray();

        return $this->response($entity);
    }

    public function getSource($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');
        if ( !$phpdoc->hasElement($entity) ) {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity);

        return $this->response($entity);
    }

    public function getDocPage($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');
        if ( !$phpdoc->hasElement($entity) ) {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity);

        return $this->response([
            'doc' => view($this->codex->view('phpdoc.entity'), $entity->toArray())->with('phpdoc', $phpdoc)->render(),
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
