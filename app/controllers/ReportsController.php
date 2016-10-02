<?php

use Insight\Portal\Repositories\PortalRepository;

class ReportsController extends \BaseController
{
    protected $_portal = null;



    public function __construct(PortalRepository $portal)
    {
        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('portal.orders')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });

        parent::__construct();
        $this->_portal = $portal;
    }





}