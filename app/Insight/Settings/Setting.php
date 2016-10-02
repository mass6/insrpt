<?php namespace Insight\Settings;
use Insight\Libraries\Settings;
use Laracasts\Commander\Events\EventGenerator;


/**
 * Class Setting
 * @package Insight\Settings
 */
class Setting extends \Eloquent {

    use EventGenerator;

    /**
     * @var array
     */
    protected $fillable = ['name', 'value', 'settings'];

    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * Return a Settings Class object
     *
     * @return Settings
     */
    public function settings()
    {
        return new Settings(json_decode($this->attributes['settings'], true), $this);
    }
}