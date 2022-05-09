<?php

use Mundanity\Collection\Collection;
use Mundanity\Collection\MutableCollection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testFromCollection()
    {
        $source = $this->createMock(MutableCollection::class);
        $source->method('toArray')
            ->willReturn([]);

        $collection = Collection::fromCollection($source);

        $this->assertInstanceOf(Collection::class, $collection);
    }


    public function testIsIterable()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertInstanceOf(\Traversable::class, $collection);
    }


    public function testCollectionStripsKeys()
    {
        $collection = new Collection([
            'one' => 'item1',
            'two' => 'item2',
        ]);

        $data = $collection->toArray();
        $this->assertArrayNotHasKey('one', $data);
        $this->assertArrayHasKey(0, $data);
    }


    public function testHas()
    {
        $collection = new Collection(['item1']);
        $this->assertTrue($collection->has('item1'));

        $collection = new Collection();
        $this->assertFalse($collection->has('item'));
    }


    public function testGetAtIndex()
    {
        $collection = new Collection(['item1', 'item2']);
        $this->assertEquals('item2', $collection->getAtIndex(1));
        $this->assertCount(2, $collection);

        $collection = new Collection(['item1']);
        $this->assertNull($collection->getAtIndex(1));

        $collection = new Collection(['item1']);
        $this->assertNull($collection->getAtIndex('potato'));
    }


    public function testIsEmpty()
    {
        $collection = new Collection();
        $this->assertTrue($collection->isEmpty());

        $collection = new Collection(['item1']);
        $this->assertFalse($collection->isEmpty());
    }


    public function testGetWhere()
    {
        $collection = new Collection();
        $result = $collection->getWhere(function($item) {} );
        $this->assertNull($result);

        $collection = new Collection(['found']);
        $result = $collection->getWhere(function($item) {
            return $item == 'found';
        });
        $this->assertEquals('found', $result);

        $item = new \StdClass;
        $item->property = 'value';
        $collection = new Collection([$item]);
        $result = $collection->getWhere(function($item) {
            return $item->property == 'value';
        });
        $this->assertEquals($item, $result);
        $this->assertNotSame($item, $result);
    }


    public function testCount()
    {
        $collection = new Collection(['item1']);
        $this->assertCount(1, $collection);
        $this->assertIsInt($collection->count());
    }


    public function testToArray()
    {
        $data = ['item1', 'item2'];
        $collection = new Collection($data);

        $this->assertEquals($data, $collection->toArray());
    }


    public function testGetIterator()
    {
        $collection = new Collection();
        $this->assertInstanceOf(\Traversable::class, $collection->getIterator());
    }


    public function testFilter()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $filtered   = $collection->filter(function($item) {
            return ($item < 3);
        });

        $this->assertInstanceOf(Collection::class, $filtered);
        $this->assertCount(2, $filtered);
    }


    public function testMap()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $mapped     = $collection->map(function($item) {
            return ($item + 1);
        });

        $this->assertInstanceOf(Collection::class, $mapped);
        $this->assertCount(5, $mapped);
        $this->assertTrue($mapped->has(6));
    }


    public function testReduce()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $reduced    = $collection->reduce(function($carry, $item) {
            return $carry + $item;
        });

        $this->assertIsInt($reduced);
        $this->assertEquals(15, $reduced);
    }


    public function testReduceHandlesInitialValue()
    {
        $collection = new Collection([1, 2, 3]);
        $reduced    = $collection->reduce(function($carry, $item) {
            return $carry - $item;
        }, 5);

        $this->assertIsInt($reduced);
        $this->assertEquals(-1, $reduced);

        $collection = new Collection([]);
        $reduced    = $collection->reduce(function($carry, $item) {
            return $carry + $item;
        }, 'foo');

        $this->assertIsString($reduced);
        $this->assertEquals('foo', $reduced);
    }


    public function testDiff()
    {
        $collection1 = new Collection([1, 2, 3]);
        $collection2 = new Collection([1, 4, 5]);
        $collection3 = new Collection([1, 6, 7]);

        $diffed = $collection1->diff($collection2, $collection3);

        $this->assertInstanceOf(Collection::class, $diffed);
        $this->assertCount(2, $diffed);
        $this->assertTrue($diffed->has(2));
        $this->assertTrue($diffed->has(3));
    }


    public function testIntersect()
    {
        $collection1 = new Collection([1, 2, 3]);
        $collection2 = new Collection([1, 4, 5]);
        $collection3 = new Collection([1, 6, 7]);

        $intersection = $collection1->intersect($collection2, $collection3);

        $this->assertInstanceOf(Collection::class, $intersection);
        $this->assertCount(1, $intersection);
        $this->assertTrue($intersection->has(1));
    }


    public function testMerge()
    {
        $collection1 = new Collection([1]);
        $collection2 = new Collection([2]);
        $collection3 = new Collection([1, 3]);

        $merged = $collection1->merge($collection2, $collection3);

        $this->assertInstanceOf(Collection::Class, $merged);
        $this->assertCount(4, $merged);
    }
}
