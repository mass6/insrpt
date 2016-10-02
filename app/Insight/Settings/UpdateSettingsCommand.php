<?php namespace Insight\Settings;
/**
 * Insight Client Management Portal:
 * Date: 7/29/14
 * Time: 1:23 AM
 */

/**
 * Class UpdateSettingsCommand
 * @package Insight\Settings
 */
class UpdateSettingsCommand
{
    public $system;
    /**
     * Input array to be processed
     *
     * @var
     */
    public $settings;

    /**
     * @param $system
     * @param $settings
     */
    public function __construct($system, $settings)
    {
        $this->system = $system;
        $this->settings = $settings;
    }
} 