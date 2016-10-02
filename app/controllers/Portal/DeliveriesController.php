<?php namespace Portal;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Insight\Portal\Services\DeliveriesService;
use Laracasts\Flash\Flash;

class DeliveriesController extends PortalController {


	private $showMaterialsReceivedTracking;

	/**
	 * @var DeliveriesService
	 */
	private $deliveriesService;


	/**
	 * DeliveriesController constructor.
	 *
	 * @param DeliveriesService $deliveriesService
     */
    public function __construct(DeliveriesService $deliveriesService)
	{
		parent::__construct();

		$this->beforeFilter(function () {
			if (!$this->user->hasAccess('portal.deliveries')) {
				Flash::error('You do not have the appropriate privileges to view the requested page.');

				return Redirect::home();
			}
		});
		$this->showMaterialsReceivedTracking = isSiteOwner($this->user) || $this->user->company->settings()->get('operations.materials-received-tracking.enabled');
		$this->deliveriesService = $deliveriesService;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		if (Request::ajax()) {
			return Response::json($this->deliveriesService->getDeliveries($this->getPortalCustomerGroupId()));
		}

		return View::make('portal.deliveries.index')->with('showMaterialsReceivedTracking', $this->showMaterialsReceivedTracking);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $increment_id
	 * @return Response
	 */
	public function show($increment_id)
	{
		$delivery = $this->deliveriesService->getDelivery($increment_id);
		if (! $delivery) {
			Flash::error("Delivery (#{$increment_id}) not found.");
			return Redirect::home();
		}

		return View::make('portal.deliveries.show', compact('delivery'))->with('showMaterialsReceivedTracking', $this->showMaterialsReceivedTracking);
	}

	public function printDelivery($increment_id)
	{
		$delivery = $this->deliveriesService->getDelivery($increment_id);
		if (! $delivery) {
			Flash::error("Delivery (#{$increment_id}) not found.");
			return Redirect::home();
		}

		return View::make('portal.deliveries.print', compact('delivery'))->with('showMaterialsReceivedTracking', $this->showMaterialsReceivedTracking);
	}

}
