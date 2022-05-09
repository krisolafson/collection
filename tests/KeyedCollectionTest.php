<?php

use Mundanity\Collection\KeyedCollection;
use PHPUnit\Framework\TestCase;

class KeyedCollectionTest extends TestCase
{
    public function testGetAtIndex()
    {
        $collection = new KeyedCollection([
            'key1' => 'item1',
            'key2' => 'item2',
        ]);

        $this->assertEquals('item2', $collection->getAtIndex(1));
        $this->assertCount(2, $collection);

        $collection = new KeyedCollection(['key1' => 'item1']);
        $this->assertNull($collection->getAtIndex(1));

        $collection = new KeyedCollection(['key1' => 'item1']);
        $this->assertNull($collection->getAtIndex('potato'));
    }


    public function testHasKey()
    {
        $collection = new KeyedCollection(['key' => 'value']);
        $this->assertTrue($collection->hasKey('key'));

        $collection = new KeyedCollection();
        $this->assertFalse($collection->hasKey('key'));
    }


    public function testGetByKey()
    {
        $collection = new KeyedCollection();
        $this->assertNull($collection->getByKey('key'));

        $collection = new KeyedCollection(['key' => 'value']);
        $this->assertEquals('value', $collection->getByKey('key'));

        $item = new \StdClass;
        $item->property = 'value';
        $collection = new KeyedCollection(['key' => $item]);
        $this->assertEquals($item, $collection->getByKey('key'));
        $this->assertNotSame($item, $collection->getByKey('key'));
    }


    public function testDiff()
    {
        $collection1 = new KeyedCollection([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);
        $collection2 = new KeyedCollection([
            'key1' => 'value1',
            'key3' => 'value3',
        ]);

        $diff = $collection1->diff($collection2);

        $this->assertCount(1, $diff);
        $this->assertTrue($diff->has('value2'));
        $this->assertTrue($diff->hasKey('key2'));
    }


    public function testDiffMatchesKeys()
    {
        $collection1 = new KeyedCollection([
            'keyX' => 'value1',
            'key2' => 'value2',
        ]);
        $collection2 = new KeyedCollection([
            'key1' => 'value1',
            'keyX' => 'value2',
        ]);

        $diff = $collection1->diff($collection2);

        $this->assertCount(2, $diff);
        $this->assertTrue($diff->hasKey('keyX'));
        $this->assertTrue($diff->hasKey('key2'));
    }


    public function testIntersect()
    {
        $collection1 = new KeyedCollection([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);
        $collection2 = new KeyedCollection([
            'key1' => 'value1',
            'key3' => 'value3',
        ]);

        $intersection = $collection1->intersect($collection2);

        $this->assertCount(1, $intersection);
        $this->assertTrue($intersection->has('value1'));
        $this->assertTrue($intersection->hasKey('key1'));
    }


    public function testIntersectMatchesKeys()
    {
        $collection1 = new KeyedCollection([
            'keyX' => 'value1',
            'key2' => 'value2',
        ]);
        $collection2 = new KeyedCollection([
            'key1' => 'value1',
            'keyX' => 'value2',
        ]);

        $intersection = $collection1->intersect($collection2);

        $this->assertTrue($intersection->isEmpty());
    }
}
