<?php namespace Admin;

use Insight\Companies\Company;
use Insight\Core\CommandBus;
use Illuminate\Support\Facades\Redirect;
use Insight\Portal\Connections\Webservices;
use Insight\Settings\Setting;
use Insight\Settings\UpdateSettingsCommand;
use Laracasts\Flash\Flash;
use View, Input;

/**
 * Class SettingsController
 * @package Admin
 */
class SettingsController extends AdminBaseController
{

    use CommandBus;

    /**
     * @var Webservices
     */
    private $webservices;

    /**
     * @param Webservices $webservices
     */
    public function __construct(Webservices $webservices)
    {
        parent::__construct();
        $this->webservices = $webservices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $system = Setting::whereName('system')->first();
        $settings = $system->settings()->all();
        //get categories via api
        $categoriesList = $this->webservices->getAllCategories();
        $companies = Company::lists('name', 'id');

        return View::make('admin.settings.index', compact('settings', 'companies', 'categoriesList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        $settingsInput = Input::get('settings');
        $system = Setting::whereName('system')->first();
        $this->execute(new UpdateSettingsCommand($system, $settingsInput));

        Flash::success('System settings were successfully updated.');

        return Redirect::route('admin.settings.index');
    }


}
