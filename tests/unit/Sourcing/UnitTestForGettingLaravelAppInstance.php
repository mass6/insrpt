<?php
namespace Sourcing;

use Codeception\Util\Stub;
use Insight\Sourcing\Forms\NewSourcingRequestForm;

class UnitTestForGettingLaravelAppInstance extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $form;

    protected function _before()
    {
        $this->form = app()->make('Insight\Sourcing\Forms\NewSourcingRequestForm');
    }

    protected function _after()
    {
    }

    // tests
    /**
     *
     *
     */
    public function it_tests_something()
    {
        //
    }

}