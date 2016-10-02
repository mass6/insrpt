<?php
namespace Libraries;


use Carbon\Carbon;
use Insight\Libraries\DatePeriod;
use Insight\Libraries\DateRanges;

class DatePeriodTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

    /**
     * @test
     */
    public function it_returns_todays_date_based_on_app_timezone()
    {
        $this->assertEquals(Carbon::today()->format('Y-m-d'), DatePeriod::today());
    }
    /**
     * @test
     */
    public function it_returns_yesterdays_date_based_on_app_timezone()
    {
        $this->assertEquals(Carbon::yesterday()->format('Y-m-d'), DatePeriod::yesterday());
    }

}