<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Foundation\Testing\ApplicationTrait;
use Illuminate\Foundation\Artisan;
use Insight\Users\User;
use PHPUnit_Framework_Assert as PHPUnit;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB as DB;

/**
 * Defines application features from the specific context.
 */
class LaravelFeatureContext implements Context, SnippetAcceptingContext
{

    use ApplicationTrait, CrawlerTrait, PhpUnitAssertionsTrait;

    protected $baseUrl = 'http://localhost';

    protected $phpUnit;

    protected $artisan;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->phpUnit = new TestCase;

    }

    /** @BeforeSuite */
    public static function setupSuite(BeforeSuiteScope $scope)
    {
        $unitTesting = true;
        $testEnvironment = 'test';
        $app = require __DIR__.'/../../bootstrap/start.php';
        $app->boot();
        $artisan = new Artisan($app);
        //$artisan->call('migrate');
        //$artisan->call('db:seed');
    }

    /** @AfterSuite */
    public static function teardown(AfterSuiteScope $scope)
    {
        $unitTesting = true;
        $testEnvironment = 'test';
        $app = require __DIR__.'/../../bootstrap/start.php';
        $app->boot();
        $artisan = new Artisan($app);
        //$artisan->call('migrate:rollback');
    }

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        if ( ! $this->app)
        {
            $this->refreshApplication();
        }

        $this->artisan = new Artisan($this->app);
    }

    /**
     * @BeforeScenario
     */
    public function setupDatabase()
    {
        //var_dump($this->artisan);
        //$this->artisan->call('migrate');
        //$this->artisan->call('db:seed');
        //$this->app['artisan']->call('migrate');
        //Artisan::call('migrate');
        DB::beginTransaction();

    }


    /**
     * @AfterScenario
     */
    public function cleanDatabase()
    {
        //$this->artisan->call('migrate:reset');
        //Artisan::call('migrate:reset');
        DB::rollback();
    }

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'test';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        //var_dump($this->app->environment());
        $user = Sentry::findUserById(2);
        //$user = Sentry::createUser(array(
        //    'email'       => 'test.user@example.com',
        //    'password'    => 'test',
        //    'activated'   => true,
        //    'company_id'  => 1,
        //    'permissions' => array(
        //        'superuser' => 1,
        //    ),
        //));

        Sentry::setUser($user);
    }


    /**
     * @When I visit :uri
     */
    public function iVisit($uri)
    {
        $this->visit($uri);
    }


    /**
     * @Then I should see :text
     */
    public function iShouldSee($text)
    {

        $this->see($text);

        //$this->response = $this->client->getResponse();

        //$crawler = new Crawler($this->client->getResponse()->getContent());
        //$message = $crawler->filterXPath('//body/h1')->text();
        //var_dump($message);
        //PHPUnit::assertCount(1, $crawler->filterXpath("//text()[. = '{$text}']"));
        User::create([
            'email' => 'taconaco1@test.com',
            'password' => 'secret1234'
        ]);
    }

    /**
     * @Then I should not see :text
     */
    public function iShouldNotSee($text)
    {
        $this->dontSee($text);
        User::create([
            'email' => 'taconaco2@test.com',
            'password' => 'secret1234'
        ]);
    }



}
