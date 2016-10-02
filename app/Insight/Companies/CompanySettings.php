<?php

namespace Insight\Companies;

use Insight\Libraries\Settings;

class CompanySettings extends Settings
{

    /**
     * Returns the Customer's defined approval timeout setting
     *
     * @return bool|int|null
     */
    public function timeoutWindow()
    {
        // End early if automatic approvals not enabled or defined
        if ( ! $this->get('product-requests.proposals.auto-approvals.enabled', false)) {
            return false;
        }

        // Get customer configured timeout window, and ensure it is an integer
        $timeoutWindowInHours = (int) $this->get('product-requests.proposals.auto-approvals.timeout-hours', null);
        if ( !$timeoutWindowInHours || !is_int($timeoutWindowInHours)) {
            return false;
        }

        return $timeoutWindowInHours;
    }
}
