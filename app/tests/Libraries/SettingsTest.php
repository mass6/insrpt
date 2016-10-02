<?php
use Insight\Libraries\Settings;
use Insight\Sourcing\SourcingRequest;

/**
 * Created by:
 * User: sam
 * Date: 7/5/15
 * Time: 2:27 PM
 */


class SettingsTest extends TestCase {


    private $settings;
    private $model;
    private $settingsArray;

    public function setUp()
    {
        parent::setUp();

        $this->settingsArray = ['foo' => 'bar', 'level2' => ['deeperfoo' => 'deeperbar']];
        $this->model = Mockery::mock('Insight\Companies\Company');
        $this->settings = new Settings($this->settingsArray, $this->model);
    }

    /**
     * @test
     */
    public function it_tests_if_setting_key_exists()
    {
        $this->assertTrue($this->settings->has('foo'), 'Should return true if key exists.');
    }

    /**
     * @test
     */
    public function it_tests_if_setting_key_does_not_exist()
    {
        $this->assertFalse($this->settings->has('unknown_key'), 'Should return false if key is exists.');
    }

    /**
     * @test
     */
    public function it_gets_an_array_of_all_settings()
    {
        $expected = ['foo' => 'bar', 'level2' => ['deeperfoo' => 'deeperbar']];

        $this->assertEquals($expected, $this->settings->all());
    }
    /**
     * @test
     */
    public function it_gets_a_root_level_setting()
    {
        $this->assertEquals('bar', $this->settings->get('foo'));
    }

    /**
     * @test
     */
    public function it_gets_a_nested_level_setting_using_dot_notation()
    {
        $this->assertEquals('deeperbar', $this->settings->get('level2.deeperfoo'));
    }

    /**
     * @test
     */
    public function it_sets_a_setting_value()
    {
        $this->model->shouldReceive('update')->withAnyArgs();
        $this->settings->set('foo', 'bar');

        $this->assertEquals('bar', $this->settings->get('foo'));
    }

}
 