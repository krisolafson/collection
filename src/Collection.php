<?php

namespace Mundanity\Collection;

use Traversable;

/**
 * Implements a basic collection from an array.
 *
 */
class Collection implements CollectionInterface
{
    /**
     * The data in the collection.
     *
     * @var array
     *
     */
    protected $data = [];


    /**
     * Constructor
     *
     * @param array $data
     *   An array of data to populate the collection with. Note that existing
     *   indexes are removed. If indexes are significant, use KeyedCollection
     *   instead.
     *
     */
    public function __construct(array $data = [])
    {
        $this->data = array_values($data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public static function fromCollection(CollectionInterface $collection)
    {
        return new static($collection->toArray());
    }


    /**
     * {@inheritdoc}
     *
     */
    public function has($item)
    {
        return in_array($item, $this->data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function getWhere(callable $callable)
    {
        foreach ($this->data as $item) {
            if ($callable($item) === true) {
                return is_object($item) ? clone $item : $item;
            }
        }
    }


    /**
     * {@inheritdoc}
     *
     */
    public function getAtIndex($index)
    {
        if (!is_numeric($index)) {
            return;
        }

        return isset($this->data[$index]) ? $this->data[$index] : null;
    }


    /**
     * {@inheritdoc}
     *
     */
    public function isEmpty()
    {
        return empty($this->data);
    }


    /**
     * Returns the number of items within the collection.
     *
     * @return int
     *
     */
    public function count(): int
    {
        return count($this->data);
    }


    /**
     * Returns an iterator.
     *
     * @return Traversable
     *
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function toArray()
    {
        return $this->data;
    }


    /**
     * {@inheritdoc}
     *
     */
    public function filter(callable $callable)
    {
        $data = array_filter($this->data, $callable, ARRAY_FILTER_USE_BOTH);
        return new static($data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function map(callable $callable)
    {
        $data = array_map($callable, $this->data);
        return new static($data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function reduce(callable $callable, $initial = null)
    {
        return array_reduce($this->data, $callable, $initial);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function diff(CollectionInterface ...$collection)
    {
        $diffs = array_map(function($item) {
            return $item->toArray();
        }, $collection);

        $func = ($this instanceof KeyedCollection) ? 'array_diff_assoc' : 'array_diff';
        $data = $func($this->data, ...$diffs);

        return new static($data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function intersect(CollectionInterface ...$collection)
    {
        $intersections = array_map(function($item) {
            return $item->toArray();
        }, $collection);

        $func = ($this instanceof KeyedCollection) ? 'array_intersect_assoc' : 'array_intersect';
        $data = $func($this->data, ...$intersections);

        return new static($data);
    }


    /**
     * {@inheritdoc}
     *
     */
    public function merge(CollectionInterface ...$collection)
    {
        $merges = array_map(function($item) {
            return $item->toArray();
        }, $collection);

        $data = array_merge($this->data, ...$merges);

        return new static($data);
    }
}
