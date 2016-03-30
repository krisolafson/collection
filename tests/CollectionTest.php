<?php

use Mundanity\Collection\Collection;


class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testFromCollection()
    {
        $source = $this->getMock('Mundanity\Collection\MutableCollection');
        $source->method('toArray')
            ->willReturn([]);

        $collection = Collection::fromCollection($source);

        $this->assertInstanceOf('Mundanity\Collection\Collection', $collection);
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
        $this->assertInternalType('integer', $collection->count());
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
        $map = $collection->map(function($item) {
            return ($item + 1);
        });

        $this->assertCount(5, $map);
        $this->assertEquals([2, 3, 4, 5, 6], $map);
    }
}
