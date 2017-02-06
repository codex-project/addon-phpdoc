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
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Addon\Phpdoc\Structure\Method;
use Codex\Addon\Phpdoc\Structure\Property;
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
        $ref = $this->resolveRef($projectSlug ?: request('project'), $ref ?: request('ref', null));
        if ( $ref instanceof Response ) {
            return $ref;
        }
        $ref->phpdoc->checkUpdate();
        return $ref->phpdoc;
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
        $entity = urldecode(request('entity'));
        $full   = request('full', false) === 'true';
        $markdown   = request('markdown', false) === 'true';

        $phpdoc = $this->getDoc();

        if ( $phpdoc instanceof Response ) {
            return $phpdoc;
        }
        if ( !$phpdoc->hasEntity($entity) ) {
            return $this->error("Entity [{$entity}] could not be found");
        }
        $e = $phpdoc->getEntity($entity);

        if($full === false) {
            $e->getEntity()->getMethods()->transform(function (Method $method) {
                return [
                    'name'       => $method->name,
                    'full_name'  => $method->full_name,
                    'inherited'  => $method->inherited,
                    'visibility' => $method->visibility,
                ];
            });
            $e->getEntity()->getProperties()->transform(function (Property $prop) {
                return [
                    'name'       => $prop->name,
                    'full_name'  => $prop->full_name,
                    'types'      => $prop->types,
                    'inherited'  => $prop->inherited,
                    'description'  => $prop->description,
                    'visibility' => $prop->visibility,
                ];
            });
            $e->set('source', '');
            $e->set('parse_markers', [ 'error' => [] ]);
        }
        if($markdown){

        }

        return $this->response($e);
    }

    public function getMethod()
    {
        $entity = urldecode(request('entity'));
        $method = request('method');
        $phpdoc = $this->getDoc();

        if ( $phpdoc instanceof Response ) {
            return $phpdoc;
        }
        if ( !$phpdoc->hasEntity($entity) ) {
            return $this->error("Entity [{$entity}] could not be found");
        }
        $e = $phpdoc->getEntity($entity)->getEntity()->getMethods()->where('name', $method)->first();
        return $this->response($e);
    }

    public function getProperty()
    {
        $entity = urldecode(request('entity'));
        $property = request('property');
        $phpdoc   = $this->getDoc();

        if ( $phpdoc instanceof Response ) {
            return $phpdoc;
        }
        if ( !$phpdoc->hasEntity($entity) ) {
            return $this->error("Entity [{$entity}] could not be found");
        }
        $e = $phpdoc->getEntity($entity)->getEntity()->getProperties()->where('name', $property);
        return $this->response($e);
    }

}
