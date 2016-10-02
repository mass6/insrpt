<?php

namespace tests\unit;

use Insight\Portal\Repositories\DeliveryQuery;
use InvalidArgumentException;
use Carbon\Carbon;

class DeliveryQueryTest extends \TestCase
{
    protected $query;

    function setup()
    {
        $this->query = new DeliveryQuery(new Carbon);
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
        $this->query->setFields(['entity_id', 'order_id', 'increment_id']);
        $this->assertEquals('entity_id,order_id,increment_id', $this->query->getParam('fields'));
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
        $this->query->setFilter('increment_id', '12345', 'eq');
        $this->assertEquals([['attribute' => 'increment_id', 'eq' => '12345']], $this->query->getParam('filter'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_throws_an_exception_if_field_is_not_allowed()
    {
        $this->query->setFields(['website']);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_throws_an_exception_if_filter_field_is_not_allowed()
    {
        $this->query->setFilter('unknown_field', 'some_value');
    }

    /** @test */
    function it_sets_an_start_date_filter()
    {
        $this->query->setFromDateFieldFilter('date_dispatched', '2016-01-01');
        $this->assertEquals([['attribute' => 'date_dispatched', 'gteq' => '2016-01-01 00:00:00']], $this->query->getParam('filter'));
    }

    /** @test */
    function it_sets_an_end_date_filter()
    {
        $this->query->setToDateFieldFilter('date_dispatched', '2016-01-31');
        $this->assertEquals([['attribute' => 'date_dispatched', 'lteq' => '2016-01-31 23:59:59']], $this->query->getParam('filter'));
    }

    /** @test */
    function it_sets_search_conditions_filter()
    {
        $this->query->setMultipleFieldFilter(['increment_id','order_id'], 'abc123');
        $expectedConditions = [[
            ['increment_id','order_id'],
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
        $this->query->setFields(['entity_id', 'order_id', 'date_dispatched']);
        $this->query->orderBy('date_dispatched', 'desc');
        $this->query->setSubCalls(['shipment_item']);
        $this->query->setFilter('order.customer_group_id', '12345', 'eq');
        $this->query->setFilter('order_id', 5);

        $expected = [
            'foo' => 'bar',
            'timezone' => 'Europe/Copenhagen',
            'fields' => 'entity_id,order_id,date_dispatched',
            'order' => 'date_dispatched',
            'dir' => 'desc',
            'sub_call' => ["shipment_item"],
            'filter' => [
                ['attribute' => 'order.customer_group_id', 'eq' => '12345'],
                ['attribute' => 'order_id', 'eq' => 5]
            ]
        ];
        $this->assertEquals($expected, $this->query->getQueryParams());
    }

}
