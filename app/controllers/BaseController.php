<?php
use Illuminate\Support\Facades\Config;

/**
 * Class BaseController
 */
class BaseController extends \Controller {

    /**
     * The current user
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $company;

    /**
     * @var string
     */
    protected $layoutDirectory;

    /**
     * @var string
     */
    protected $layoutName;

    /**
     * Class constructor
     * Sets the current user variable
     */
    public function __construct()
    {
        $this->user = Sentry::getUser();
        $this->company = $this->user->company;
        $this->refreshSessionData();
        $this->layoutDirectory = $this->getLayoutDirectory();
        $this->layoutName = $this->layoutDirectory . '.layout';
    }

    protected function refreshSessionData()
    {
        if (! Session::has('hasSessionData')) {
            Session::put([
                'hasSessionData' => true,
                'user' => $this->user,
                'company' => $this->user->company,
                'dataGroup' => $this->user->company->settings()->get('portal.dataGroup') ?: 'none',
            ]);
        }
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

        // variables made available in all views
        View::share([
            'currentUser'      => $this->user,
            'userCompany'      => $this->company,
            'layoutDirectory'  => $this->layoutDirectory,
            'layout'           => $this->layoutName,
        ]);
	}

    /**
     * @return string
     */
    protected function getLayoutDirectory()
    {
        $layout = $this->company->settings()->get('design.layout', null);

        if (!$layout || !file_exists(app_path() . '/views/layouts/' . $layout . '/layout.blade.php')) {
            $layout = Config::get('view.layout.default');
        }

        return  'layouts.' . $layout;
    }

}
