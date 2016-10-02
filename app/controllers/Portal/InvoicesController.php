<?php namespace Portal;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Insight\Portal\Services\InvoicesService;
use Laracasts\Flash\Flash;

class InvoicesController extends PortalController {

	/**
	 * @var InvoicesService
	 */
	private $invoicesService;


	/**
	 * InvoicesController constructor.
	 *
	 * @param InvoicesService $invoicesService
     */
    public function __construct(InvoicesService $invoicesService)
	{
		parent::__construct();

		$this->beforeFilter(function () {
			if (!$this->user->hasAccess('portal.invoices')) {
				Flash::error('You do not have the appropriate privileges to view the requested page.');

				return Redirect::home();
			}
		});
		$this->invoicesService = $invoicesService;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  integer  $entity_id
	 * @return Response
	 */
	public function show($entity_id)
	{
		$invoice = $this->invoicesService->getInvoice($entity_id);
		if (! $invoice) {
			Flash::error("Invoice not found.");
			return Redirect::home();
		}

		return View::make('portal.invoices.show', compact('invoice'));
	}

	public function printInvoice($entity_id)
	{
		$invoice = $this->invoicesService->getInvoice($entity_id);
		if (! $invoice) {
			Flash::error("Invoice not found.");
			return Redirect::home();
		}

		return View::make('portal.invoices.print', compact('invoice'));
	}



}
