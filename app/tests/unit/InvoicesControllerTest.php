<?php namespace tests\unit;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Insight\Users\User;

class InvoicesControllerTest extends \TestCase
{

    public function setUp()
    {

        Sentry::setUser(User::find(1));
    }

    public function testIndex()
    {
        $this->client->request('GET', 'portal/invoices');
    }

}