<?php

namespace tests\unit;

use InvalidArgumentException;
use Carbon\Carbon;
use Insight\Portal\Repositories\OrderQuery;

class OrderQueryTest extends \TestCase
{
    protected $query;

    function setup()
    {
        $this->query = new OrderQuery(new Carbon);
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
    function it_sets_default_report_fields()
    {
        $this->assertEquals('increment_id,contract_shipping,status,created_at,updated_at,approved_by,approved_at,actual_delivery_date,custom_ref1,custom_ref2', $this->query->getParam('fields'));
    }

    /** @test */
    function it_sets_the_report_fields()
    {
        $this->query->setFields(['increment_id', 'contract_shipping', 'status']);
        $this->assertEquals('increment_id,contract_shipping,status', $this->query->getParam('fields'));
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
    function it_sets_all_sub_calls()
    {
        $this->query->setAllSubCalls();
        $this->assertEquals(['order_item', 'order_contract', 'order_reference', 'order_customer', 'order_comment'], $this->query->getParam('sub_call'));
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
        $this->query->setFromDateFieldFilter('approved_at', '2016-01-01');
        $this->assertEquals([['attribute' => 'approved_at', 'gteq' => '2016-01-01 00:00:00']], $this->query->getParam('filter'));
    }

    /** @test */
    function it_sets_an_end_date_filter()
    {
        $this->query->setToDateFieldFilter('approved_at', '2016-01-31');
        $this->assertEquals([['attribute' => 'approved_at', 'lteq' => '2016-01-31 23:59:59']], $this->query->getParam('filter'));
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
        $this->query->setFields(['increment_id', 'contract_shipping', 'status']);
        $this->query->orderBy('approved_at', 'desc');
        $this->query->setSubCalls(['order_item', 'order_contract', 'order_reference', 'order_customer', 'order_comment']);
        $this->query->setFilter('increment_id', '12345', 'eq');
        $this->query->setFilter('customer_id', 5);

        $expected = [
            'foo' => 'bar',
            'timezone' => 'Europe/Copenhagen',
            'fields' => 'increment_id,contract_shipping,status',
            'order' => 'approved_at',
            'dir' => 'desc',
            'sub_call' => ["order_item", "order_contract", "order_reference", "order_customer", "order_comment"],
            'filter' => [
                ['attribute' => 'increment_id', 'eq' => '12345'],
                ['attribute' => 'customer_id', 'eq' => 5]
            ]
        ];
        $this->assertEquals($expected, $this->query->getQueryParams());
    }

}
