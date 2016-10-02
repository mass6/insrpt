<?php

use Insight\Sourcing\SourcingRequest;

class SourcingRequestModelTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $sourcingRequest;

    protected function _before()
    {
        $this->sourcingRequest = new SourcingRequest([
            "customer_id" => 1,
            "assigned_to_id" => 2,
            "created_by_id" => 1,
            "updated_by_id" => 1,
            'status' => 'ASS',
        ]);
    }

    protected function _after()
    {
    }

    // tests

    /**
     * Prices
     * @test
     */
    public function setPricesInBaseUnit()
    {
        $this->sourcingRequest = new SourcingRequest();
        $this->sourcingRequest->customer_price = 5.50;
        $this->sourcingRequest->tss_buy_price = 0;
        $this->sourcingRequest->tss_sell_price = null;
        // should not be converted
        $this->sourcingRequest->tss_sku = 5;


        $this->assertEquals(550,$this->sourcingRequest['attributes']['customer_price']);
        $this->assertEquals(null,$this->sourcingRequest['attributes']['tss_buy_price']);
        $this->assertEquals(null,$this->sourcingRequest['attributes']['tss_sell_price']);
        // should not be converted
        $this->assertEquals(5,$this->sourcingRequest['attributes']['tss_sku']);
    }

    /**
     * @test
     */
    public function getPricesInStandardCurrencyUnit()
    {
        $this->sourcingRequest = new SourcingRequest();
        $this->sourcingRequest->customer_price = 6;
        $this->sourcingRequest->tss_buy_price = 6;
        $this->sourcingRequest->tss_sell_price = 6;

        // prices should convert back to standard currency
        $this->assertEquals(6,$this->sourcingRequest->customer_price);
        $this->assertEquals(6,$this->sourcingRequest->tss_buy_price);
        $this->assertEquals(6,$this->sourcingRequest->tss_sell_price);

    }


    /**
     * Test Company relation
     * @test
     */
    public function getCustomer()
    {
        $class = get_class($this->sourcingRequest->customer);
        $this->assertEquals('Insight\Companies\Company',$class);
    }

    /**
     * Test Assigned To relation
     * @test
     */
    public function getAssignedUser()
    {
        $class = get_class($this->sourcingRequest->assignedTo);
        $this->assertEquals('Insight\Users\User',$class);
    }

    /**
     * Test Created By relation
     * @test
     */
    public function getCreatedByUser()
    {
        $class = get_class($this->sourcingRequest->createdBy);
        $this->assertEquals('Insight\Users\User',$class);
    }
    /**
     * Test Updated By relation
     * @test
     */
    public function getUpdatedByUser()
    {
        $class = get_class($this->sourcingRequest->updatedBy);
        $this->assertEquals('Insight\Users\User',$class);
    }
    /**
     * Test Updated By relation
     * @test
     */
    public function testStatusName()
    {
        $this->sourcingRequest->status = 'ASS';
        $this->assertEquals('Assessing',$this->sourcingRequest->statusName());

        $this->sourcingRequest->status = 'SRC';
        $this->assertEquals('Sourcing',$this->sourcingRequest->statusName());

        $this->sourcingRequest->status = 'PRI';
        $this->assertEquals('Pricing',$this->sourcingRequest->statusName());

        $this->sourcingRequest->status = 'CLS';
        $this->assertEquals('Closed',$this->sourcingRequest->statusName());
    }
    /**
     * Test TSS Buy Price Margin
     * @test
     */
    public function testBuyPriceMargin()
    {
        $this->sourcingRequest->customer_price = 60;
        $this->sourcingRequest->tss_buy_price = 30;
        $this->assertEquals(50,$this->sourcingRequest->tssBuyPriceMargin());
    }
    /**
     * Test TSS Sell Price Margin
     * @test
     */
    public function testSellPriceMargin()
    {
        $this->sourcingRequest->tss_buy_price = 30;
        $this->sourcingRequest->tss_sell_price = 45;
        $this->assertEquals(50,$this->sourcingRequest->tssSellPriceMargin());
    }
    /**
     * Test Customer Sell Price Margin
     * @test
     */
    public function testCustomerSellPriceMargin()
    {
        $this->sourcingRequest->customer_price = 60;
        $this->sourcingRequest->tss_sell_price = 45;
        $this->assertEquals(25,$this->sourcingRequest->customerSellPriceMargin());
    }


}