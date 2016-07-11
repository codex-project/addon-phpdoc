<?php
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addon\Phpdoc\Popover;
use Codex\Addon\Phpdoc\Util;
use Codex\Codex;
use Codex\Http\Controllers\Api\V1\ApiController;
use Codex\Support\Collection;
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
        $project->phpdoc->checkUpdate();
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

    public function getElement($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string)request()->get('entity');
        $data = json_decode(json_encode($phpdoc->getElement($entity)), true);
        return response()->json($data);
    }

    public function getEntity($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);

        $entity = (string)request()->get('entity');
        if ( !$phpdoc->hasElement($entity) ) {
            return $this->error('Entity does not exist');
        }

        $fields = new Collection(explode(',', request('fields')));
        #$allowed = [ 'markers', 'inherited_methods', 'properties', 'methods', 'source' ];

        $key = "phpdoc.api.entity.{$this->project}.{$this->project->getRef()}";



        $element = $this->codex->cache->lastModified($key, $phpdoc->getLastModified(),
            function () use ($entity, $phpdoc, $fields) {
                return $phpdoc->getElement($entity)->toArray();
            });


        // Filter general fields
        $this->dropIfNotRequested('source', $element, $fields);
        $this->dropIfNotRequested('properties', $element, $fields);
        $this->dropIfNotRequested('markers', $element, $fields);

        // Filter methods
        $methods = collect();;
        if ( $fields->contains('methods') ) {
            $methods->merge(array_filter($element[ 'methods' ], function ($method) use ($element) {
                return $method[ 'class_name' ] === $element[ 'full_name' ];
            }));
        }

        if ( $fields->contains('inherited_methods') ) {
            $methods->merge(array_filter($element[ 'methods' ], function ($method) use ($element) {
                return $method[ 'class_name' ] === $element[ 'full_name' ];
            }));
        }

        $element[ 'methods' ] = $methods->toArray();

        if ( $methods->isEmpty() ) {
            unset($element[ 'methods' ]);
        }


        // Filter properties
        $properties = [ ];
        if ( $fields->contains('properties') ) {
            foreach ( array_filter($element[ 'properties' ], function ($property) use ($element) {
                return $property[ 'class_name' ] === $element[ 'full_name' ];
            }) as $property ) {
                $properties[] = $property;
            }
        }

        if ( $fields->contains('inherited_properties') ) {
            foreach ( array_filter($element[ 'properties' ], function ($property) use ($element) {
                return $property[ 'class_name' ] !== $element[ 'full_name' ];
            }) as $property ) {
                $properties[] = $property;
            }
        }

        $element[ 'properties' ] = $properties;

        if ( count($properties) === 0 ) {
            unset($element[ 'properties' ]);
        }




        return $this->response($element);
    }

    protected function dropIfNotRequested($key, &$element, Collection $fields)
    {
        if ( false === $fields->contains($key) ) {
            unset($element[ $key ]);
        }
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

    protected function getCacheKey($projectSlug, $ref, $entity, $suffix = '')
    {
        $entity = md5($entity);
        return "phpdoc.{$projectSlug}.{$ref}.{$entity}{$suffix}";
    }

    public function getDocPage($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $fqn = (string)request()->get('entity');

        if ( !$phpdoc->hasElement($fqn) ) {
            return $this->error('Entity does not exist');
        }

        // t
        $element = $phpdoc->getElement($fqn);
        $e      = $element->collect();

        $methods          = $e->get('methods')->filter(function ($item) use ($e) {
            return $item[ 'class_name' ] === $e[ 'full_name' ];
        });
        $inheritedMethods = $e->get('methods')->filter(function ($item) use ($e) {
            return $item[ 'class_name' ] !== $e[ 'full_name' ];
        });

        $methods          = $methods->sortBy('visibility')->reverse()->toArray();
        $inheritedMethods = $inheritedMethods->sortBy('visibility')->reverse()->toArray();

        $e->set('methods', $methods);
        $e->set('inherited_methods', $inheritedMethods);

        $document = null;
        $pathName = $this->project->config('phpdoc.doc_path') . DIRECTORY_SEPARATOR . Util::toFileName($fqn);
        if($this->project->documents->has($pathName)) {
            $doc = $this->project->documents->get($pathName);
            $doc->setAttribute('processors.disabled', $this->project->config('phpdoc.doc_disabled_processors', []));
            $document = $doc->render();

            $ex = '/<!--section:(.*?)-->/';
            $split = preg_split($ex, $document);
            $imatch = preg_match_all($ex, $document, $matches);
        }

        $doc = view($this->codex->view('phpdoc.entity'), $e->toArray())->with(compact('phpdoc', 'document'))->render();
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
