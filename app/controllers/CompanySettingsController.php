<?php

use Illuminate\Support\Facades\Redirect;
use Insight\Companies\Company;
use Laracasts\Flash\Flash;

/**
 * Class CompanySettingsController
 */
class CompanySettingsController extends \BaseController
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('company-settings')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $company = $this->user->company;
        $settings = $company->settings()->all();
        $users = $company->users;
        $userlist = [];
        foreach ($users as $user) {
            $userlist[$user->id] = $user->name();
        }
        $userlist = [null => ''] + $userlist;

        if ($categories = array_get($settings, 'product-requests.procurement-categories', null)) {
            array_set($settings, 'product-requests.procurement-categories', implode("\r\n", $categories));
        } else {
            array_set($settings, 'product-requests.procurement-categories', '');
        }

        return View::make('company-settings.edit', compact('company', 'settings', 'userlist'));
    }

    /**
     * @return mixed
     */
    public function suppliers()
    {
        $company = $this->user->company;

        return View::make('company-settings.partials.index', compact('company'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $settings = Input::get('settings');

        $categoriesString = $settings['product-requests']['procurement-categories'];
        $categories = parseMultilineStringIntoArray($categoriesString);

        $settings['product-requests']['procurement-categories'] = $categories;

        $company = Company::findOrFail($id);
        $company->settings()->merge($settings);

        Flash::success('Settings have been successfully saved.');

        return Redirect::action('CompanySettingsController@edit');
    }

}
