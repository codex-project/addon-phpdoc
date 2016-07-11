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
namespace Codex\Addon\Phpdoc\Structure;

use ArrayAccess;
use Codex\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Sebwite\Support\Arr;
use Serializable;

abstract class AbstractStructure implements Arrayable, ArrayAccess, Serializable
{
    protected $items = [ ];

    /** @var \Codex\Addon\Phpdoc\Structure\StructureFactory */
    protected $factory;

    protected $belongsTo;

    public function __construct(array $raw, StructureFactory $factory, AbstractStructure $belongsTo = null)
    {
        $this->factory   = $factory;
        $this->belongsTo = $belongsTo;
        $this->items     = $this->transform(new Collection($raw));
    }

    /**
     * transform method
     *
     * @param array|Collection $data
     *
     * @return array
     */
    abstract protected function transform($data);

    protected function copy(array $keys = [ ], $from, &$to)
    {
        foreach ( $keys as $key ) {
            $to[ $key ] = $from[ $key ];
        }
        return $to;
    }

    public function set($key, $val)
    {
        Arr::set($this->items, $key, $val);
        return $this;
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    public function has($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     * forget method
     *
     * @param array|string ...$keys
     *
     * @return static
     */
    public function forget($keys)
    {
        Arr::forget($this->items, is_string($keys) ? func_get_args() : $keys);
        return $this;
    }

    protected function createString($str)
    {
        if ( is_array($str) ) {
            return (string)implode("\n", $str);
        }
        return (string)$str;
    }

    protected function structures($key, $class, Collection $data, Collection $to = null)
    {
        $to = $to ?: new Collection;

        if ( $data->has($key) ) {
            if ( is_string(head(array_keys($data[ $key ]))) ) {
                $to->add(new $class($data[ $key ], $this->factory, $this));
            } else {
                foreach ( $data->get($key, [ ]) as $item ) {
                    $to->add(new $class($item, $this->factory, $this));
                }
            }
        }
        return $to;
    }

    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (new Collection($this->items))->toArray();
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    public function __get($name)
    {
        return $this[$name];
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->items = unserialize($serialized);
    }
}
