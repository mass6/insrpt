<?php 

namespace Insight\Settings\Events; 

use Insight\Settings\Setting;

class SettingWasUpdated
{

    /**
     * @var Setting
     */
    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
 