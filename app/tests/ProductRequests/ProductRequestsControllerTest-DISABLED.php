<?php

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Insight\ProductRequests\ProductRequest;

class ProductRequestsControllerTest extends TestCase
{
    protected $user;

    protected $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock(
            '\Eloquent',
            'Insight\ProductRequests\ProductRequestRepository'
        );

        $user = Sentry::findUserById(2);
        Sentry::setUser($user);
        $this->user = $user;
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testIndex()
    {
        $data = new Collection([
            [
                'id' => 1,
                'product_description' => 'my description'
            ]
        ]);
        $this->mock
           ->shouldReceive('all')
           ->once()
           ->andReturn($data);

        $this->app->instance('Insight\ProductRequests\ProductRequestRepository', $this->mock);

        $response = $this->call('GET', '/product-requests');

        $this->assertViewHas('product_requests');
        $product_requests = $response->original->getData()['product_requests'];
        $this->assertTrue(is_array($product_requests));
        $this->assertResponseOk();
    }

//    public function testStore()
//    {
//        $response = $this->call('POST', '/product-requests');
//    }
}
