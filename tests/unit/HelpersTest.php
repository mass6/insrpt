<?php


class HelpersTest extends \Codeception\TestCase\Test
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
     *
     * @test
     */
    public function it_removes_commas_from_a_single_price()
    {
        $this->assertEquals(12500.50, removeCommasFromPriceFields('12,500.50'));
        $this->assertEquals(22500, removeCommasFromPriceFields('22,500.00'));
        $this->assertEquals(33500, removeCommasFromPriceFields('33,500'));
        $this->assertEquals(44500.00, removeCommasFromPriceFields('44,500'));
        $this->assertEquals('', removeCommasFromPriceFields(''));

    }
    /**
     *
     * @test
     */
    public function it_removes_commas_from_an_array_of_prices()
    {
        $prices = ['Price1' => '12,500.50', 'Price2' => '45,100.00'];

        $formattedPrices = removeCommasFromPriceFields($prices);

        $this->assertEquals(12500.50,$formattedPrices['Price1']);
        $this->assertEquals(45100,$formattedPrices['Price2']);
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_when_no_price_is_provided()
    {
        removeCommasFromPriceFields(null);
    }

}