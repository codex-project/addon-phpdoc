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

use Codex\Addon\Phpdoc\Popover;
use Codex\Addon\Phpdoc\Structure\File;
use Codex\Addon\Phpdoc\Util;
use Codex\Codex;
use Codex\Http\Controllers\Api\V1\ApiController;
use Codex\Support\Collection;
use Illuminate\Contracts\View\Factory as ViewFactory;

class PhpdocApiControllerw extends ApiController
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
        $project = $this->resolveRef($projectSlug, $ref);
        $project->phpdoc->checkUpdate(true);
        return $project->phpdoc;
    }

    protected function isFull()
    {
        return request()->get('full', false) == true;
    }

    public function getTree($projectSlug, $ref = null)
    {
        return $this->response($this->getDoc($projectSlug, $ref)->tree($this->isFull()));
    }

    public function getList($projectSlug, $ref = null)
    {
        $entities = $this->getDoc($projectSlug, $ref)->getEntities($this->isFull());
        if($this->isFull()){
            foreach($entities as $entity){
                $this->filterFields($entity, request('fields'));
            }
        }
        return $this->response($entities->toArray());
    }

    public function getEntity($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);

        $full_name = (string)request()->get('entity');
        if ( !$phpdoc->hasEntity($full_name) ) {
            return $this->error('Entity does not exist');
        }

        $entity = $phpdoc->getEntity($full_name);
        $this->filterFields($entity, request('fields'));
        return $this->response($entity->toArray());
    }

    protected function filterFields(File $entity, $fields)
    {
        $fields = new Collection(explode(',', $fields));
        $data   = [
            'source'     => 'source',
            'properties' => 'entity.properties',
            'methods'    => 'entity.methods',
            'markers'    => 'parse_markers',
            'constants'  => 'entity.constants',
            'tags'       => 'entity.tags',
        ];
        $fields = $fields->toArray(); //->only(array_keys($data))->toArray();
        $diff   = array_diff(array_keys($data), $fields);
        $diff   = array_values(array_combine($diff, array_values(array_only($data, $diff))));
        $entity->forget($diff);
    }

    public function getSource($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');
        if ( !$phpdoc->hasEntity($entity) ) {
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getEntity($entity);

        return $this->response($entity->source);
    }

    protected function getCacheKey($projectSlug, $ref, $entity, $suffix = '')
    {
        $entity = md5($entity);
        return "phpdoc.{$projectSlug}.{$ref}.{$entity}{$suffix}";
    }

    public function getDocPage($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $fqn    = (string)request()->get('entity');

        if ( !$phpdoc->hasEntity($fqn) ) {
            return $this->error('Entity does not exist');
        }

        // t
        $entity = $phpdoc->getEntity($fqn);
//        $e       = $entity->collect();
//
//        $methods          = $e->get('methods')->filter(function ($item) use ($e) {
//            return $item[ 'class_name' ] === $e[ 'full_name' ];
//        });
//        $inheritedMethods = $e->get('methods')->filter(function ($item) use ($e) {
//            return $item[ 'class_name' ] !== $e[ 'full_name' ];
//        });
//
//        $methods          = $methods->sortBy('visibility')->reverse()->toArray();
//        $inheritedMethods = $inheritedMethods->sortBy('visibility')->reverse()->toArray();
//
//        $e->set('methods', $methods);
//        $e->set('inherited_methods', $inheritedMethods);
//
//        $document = null;
//        $pathName = $this->project->config('phpdoc.doc_path') . DIRECTORY_SEPARATOR . Util::toFileName($fqn);
//        if ( $this->project->documents->has($pathName) ) {
//            $doc = $this->project->documents->get($pathName);
//            $doc->setAttribute('processors.disabled', $this->project->config('phpdoc.doc_disabled_processors', [ ]));
//            $document = $doc->render();
//
//            $ex     = '/<!--section:(.*?)-->/';
//            $split  = preg_split($ex, $document);
//            $imatch = preg_match_all($ex, $document, $matches);
//        }

        $document = null;
        $doc = view($this->codex->view('phpdoc.entity'), $entity->toArray())->with(compact('phpdoc', 'document'))->render();
        /// tt

//        $doc = $this->codex->getCachedLastModified(
//            $this->getCacheKey($projectSlug, $ref, $entity, '.doc'),
//            $phpdoc->getLastModified(),
//            function () use ($entity, $phpdoc)
//            {
//                $entity = $phpdoc->getElement($entity);
//                $e = $entity->collect();
//
//                $methods = $e->get('methods')->reject(function($item) use ($e) {
//                    return $item['class_name'] === $e['full_name'];
//                });
//                return view($this->codex->view('phpdoc.entity'), $entity->toArray())->with('phpdoc', $phpdoc)->render();
//            }
//        );

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
