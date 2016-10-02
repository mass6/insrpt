<?php

namespace Insight\Libraries;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Support\Facades\Schema;

/**
 * Class AclTrait
 * @package Insight\Libraries
 */
trait AclTrait
{

    /**
     * @var
     */
    protected $aclUser;


    /**
     * Apply company and user scope to query
     *
     * @param $query
     * @return mixed
     */
    public function scopeAcl($query)
    {

        $aclUser = $this->getAclUser();
        // if current user is not a member of the site owner company, apply scope
        if ($aclUser->company->id !== (int) settings('site_owner')) {
            $query->where('company_id', $aclUser->company->id);

            // user is not a site owner, restrict access to his own data
            if (!$aclUser->hasAccess('customers.data')) {

                $query->where(function($query) use ($aclUser) {

                    $query->where('created_by_id', $aclUser->id)
                        ->orWhere(function($query) use ($aclUser)
                        {
                            if (Schema::hasColumn($this->table, 'requested_by_id'))
                            {
                                $query->where('requested_by_id', $aclUser->id);
                            }
                        })->orWhere(function($query) use ($aclUser)
                        {
                            if (Schema::hasColumn($this->table, 'assigned_to_id'))
                            {
                                $query->where('assigned_to_id', $aclUser->id);
                            }
                        });

                });

            }
        }

        return $query;
    }

    /**
     * Get current user
     *
     * @return mixed
     */
    private function getAclUser()
    {
        if (!$this->aclUser) {
            $this->aclUser = Sentry::getUser();
        }
        return $this->aclUser;
    }

    /**
     * Apply company scope to query
     *
     * @param $query
     * @return mixed
     */
    public function scopeCompanyData($query)
    {
        $aclUser = $this->getAclUser();
        // if current user is not a member of the site owner company, apply company scope
        if ($aclUser->company->id !== (int) settings('site_owner')) {
            return $query->where('company_id', $aclUser->company->id);
        }
    }

    /**
     * Apply user scope to query
     *
     * @param $query
     * @return mixed
     */
    public function scopeUserData($query)
    {
        $aclUser = $this->getAclUser();
        // user does not have access to company-wide data, restrict access to his own data
        if (!$aclUser->hasAccess('customers.data')) {
            return $query->where('created_by_id', $aclUser->id)
                ->orWhere(function($query) use ($aclUser)
                    {
                        if (Schema::hasColumn($this->table, 'requested_by_id'))
                        {
                            $query->where('requested_by_id', $aclUser->id);
                        }
                    })->orWhere(function($query) use ($aclUser)
                    {
                        if (Schema::hasColumn($this->table, 'assigned_to_id'))
                        {
                            $query->where('assigned_to_id', $aclUser->id);
                        }
                    });
        }
    }
}