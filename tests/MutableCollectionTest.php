<?php

use Mundanity\Collection\MutableCollection;
use PHPUnit\Framework\TestCase;

class MutableCollectionTest extends TestCase
{
    public function testAdd()
    {
        $collection = new MutableCollection(['item1']);
        $chained    = $collection->add('item2');

        $this->assertCount(2, $collection);
        $this->assertSame($chained, $collection);
    }


    public function testRemove()
    {
        $collection = new MutableCollection(['item1', 'item2']);
        $chained    = $collection->remove('item2');

        $this->assertCount(1, $collection);
        $this->assertSame($chained, $collection);

        $collection->remove('item3');
        $this->assertCount(1, $collection);
    }


    public function testFilterReturnsSameObject()
    {
        $collection = new MutableCollection([1, 2, 3]);
        $filtered   = $collection->filter(function($item) {
            return ($item < 3);
        });

        $this->assertCount(2, $filtered);
        $this->assertSame($collection, $filtered);
    }


    public function testMapReturnsSameObject()
    {
        $collection = new MutableCollection([1, 2, 3]);
        $mapped     = $collection->map(function($item) {
            return ($item * 2);
        });

        $this->assertSame($collection, $mapped);
    }


    public function testDiffReturnsSameObject()
    {
        $collection1 = new MutableCollection([1, 2, 3]);
        $collection2 = new MutableCollection([1, 4, 5]);
        $collection3 = new MutableCollection([1, 6, 7]);

        $diffed = $collection1->diff($collection2, $collection3);

        $this->assertSame($collection1, $diffed);
    }


    public function testIntersectReturnsSameObject()
    {
        $collection1 = new MutableCollection([1, 2, 3]);
        $collection2 = new MutableCollection([1, 4, 5]);
        $collection3 = new MutableCollection([1, 6, 7]);

        $intersection = $collection1->intersect($collection2, $collection3);

        $this->assertSame($collection1, $intersection);
    }


    public function testMergeReturnsSameObject()
    {
        $collection1 = new MutableCollection([1]);
        $collection2 = new MutableCollection([2]);
        $collection3 = new MutableCollection([1, 3]);

        $merged = $collection1->merge($collection2, $collection3);

        $this->assertCount(4, $merged);
        $this->assertSame($collection1, $merged);
    }
}
