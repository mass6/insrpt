<?php

namespace tests\unit;

use Carbon\Carbon;
use Insight\Portal\Repositories\ProductQuery;

class ProductQueryTest extends \TestCase
{
    protected $query;

    function setup()
    {
        $this->query = new ProductQuery(new Carbon());
    }

    /** @test */
    function it_sets_a_parameter()
    {
        $this->query->setParam('foo', 'bar');
        $this->assertEquals('bar', $this->query->getParam('foo'));
    }

    /** @test */
    function it_sets_the_timezone()
    {
        $this->query->setTimezone('Europe/Copenhagen');
        $this->assertEquals('Europe/Copenhagen', $this->query->getParam('timezone'));
    }

    /** @test */
    function it_sets_timezone_to_UTC_if_not_specified()
    {
        $this->assertEquals('UTC', $this->query->getParam('timezone'));
    }

    /** @test */
    function it_sets_the_report_fields()
    {
        $this->query->setFields(['entity_id', 'name', 'website']);
        $this->assertEquals('entity_id,name,website', $this->query->getParam('fields'));
    }

    /** @test */
    function it_removes_field_limits()
    {
        $this->query->removeFieldLimits();
        $this->assertEquals(null, $this->query->getParam('fields'));
    }

    /** @test */
    function it_sets_the_order_by()
    {
        $this->query->orderBy('foo', 'desc');
        $this->assertEquals('foo', $this->query->getParam('orderBy'));
        $this->assertEquals('desc', $this->query->getParam('dir'));
    }

    /** @test */
    function it_sets_the_sort_direction_if_not_specified()
    {
        $this->query->orderBy('foo');
        $this->assertEquals('asc', $this->query->getParam('dir'));
    }

    /** @test */
    function it_sets_the_sub_calls()
    {
        $this->query->setSubCalls(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $this->query->getParam('sub_call'));
    }

    /** @test */
    function it_appends_a_single_sub_call()
    {
        $this->query->setSubCalls(['foo', 'bar']);
        $this->query->appendSubcall('baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $this->query->getParam('sub_call'));
    }

    /** @test */
    function it_appends_multiple_sub_call()
    {
        $this->query->setSubCalls(['foo', 'bar']);
        $this->query->appendSubCall(['baz','bop']);
        $this->assertEquals(['foo', 'bar', 'baz', 'bop'], $this->query->getParam('sub_call'));
    }

    /** @test */
    function it_sets_a_filter()
    {
        $this->query->setFilter('website', 'base');
        $this->assertEquals([['attribute' => 'website', 'eq' => 'base']], $this->query->getParam('filter'));
    }

    /**
     //* @test
     * @expectedException InvalidArgumentException
     */
    function it_throws_an_exception_if_field_is_not_allowed()
    {
        $this->query->setFields(['color']);
    }

    /**
     / @test
     * @expectedException InvalidArgumentException
     */
    function it_throws_an_exception_if_filter_field_is_not_allowed()
    {
        $this->query->setFilter('unknown_field', 'some_value');
    }

    /** @test */
    function it_sets_search_conditions_filter()
    {
        $this->query->setMultipleFieldFilter(['increment_id','status'], 'abc123');
        $expectedConditions = [[
            ['increment_id','status'],
            [
                [ 'like' => '%abc123%' ],
                [ 'like' => '%abc123%' ]
            ],
        ]];
        $this->assertEquals($expectedConditions, $this->query->getParam('filter'));
    }


    /** @test */
    function it_returns_an_array_of_all_merged_parameters()
    {
        $this->query->setParam('foo', 'bar');
        $this->query->setTimezone('Europe/Copenhagen');
        $this->query->setFields(['entity_id', 'name', 'website']);
        $this->query->orderBy('entity_id');
        $this->query->setFilter('status', 1);
        $this->query->setFilter('visibility', 4);

        $expected = [
            'foo' => 'bar',
            'timezone' => 'Europe/Copenhagen',
            'fields' => 'entity_id,name,website',
            'order' => 'entity_id',
            'dir' => 'asc',
            'filter' => [
                ['attribute' => 'status', 'eq' => 1],
                ['attribute' => 'visibility', 'eq' => 4]
            ]
        ];
        $this->assertEquals($expected, $this->query->getQueryParams());
    }

}
