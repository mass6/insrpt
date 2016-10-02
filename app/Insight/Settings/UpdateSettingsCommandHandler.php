<?php namespace Insight\Settings;
use Insight\Settings\Events\SettingWasUpdated;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use \Log;

/**
 * Insight Client Management Portal:
 * Date: 7/29/14
 * Time: 1:29 AM
 */

class UpdateSettingsCommandHandler implements CommandHandler
{
    use DispatchableTrait;

    /**
     * @var SettingRepository
     */
    protected $setting;

    /**
     * Array of checkbox fields
     *
     * @var array
     */
    private $checkboxFields = [
        'notifications.send_portal_data_update_notifications',
        'live_search.enabled',
    ];

    /**
     * @param SettingRepository $setting
     */
    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {

        $system = $command->system;
        $settings = $this->setCheckboxValuesToFalseIfEmpty($command->settings);
        $system->settings()->merge($settings);
        $command->system->save();

        $system->raise(new SettingWasUpdated($system));
        $this->dispatchEventsFor($system);

        return $system;
    }

    /**
     * Sets values for checkbox fields to false if not present in input array
     *
     * @param $settingsInput
     * @return mixed
     */
    private function setCheckboxValuesToFalseIfEmpty($settingsInput)
    {
        foreach ($this->checkboxFields as $field) {
            if (!array_get($settingsInput, $field)) {
                array_set($settingsInput, $field, false);
            }
        }

        return $settingsInput;
    }
}