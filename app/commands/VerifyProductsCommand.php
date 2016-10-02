<?php

use Illuminate\Console\Command;
use Insight\Companies\Company;
use Insight\Portal\Products\ProductRepository;
use Insight\Portal\Products\UpdateProductsCommandHandler;
use Insight\Portal\Products\UpdateProductsCommand;
use Insight\Portal\Services\ProductsService;

class VerifyProductsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'insight:verify-products';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Verifies that local product data matches latest product data from portal.';

    /**
     * @var ProductRepository
     */
    private $product;

    /**
     * @var ProductsService
     */
    private $productsService;


    /**
     * VerifyProductsCommand constructor.
     *
     * @param ProductRepository $product
     * @param ProductsService   $productsService
     */
    public function __construct(ProductRepository $product, ProductsService $productsService)
    {
        parent::__construct();
        $this->product = $product;
        $this->productsService = $productsService;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $customers = $this->getCustomers();

        foreach($customers as $customer)
        {
            if ($customer->websiteCode()) {

                $localProducts = $this->product->getCustomerProducts($customer->websiteCode())->toArray();
                $portalProducts = $this->productsService->getAllProducts($customer->id);
                $portalProducts = isset($portalProducts['data']) ? $portalProducts['data']: [];

                $this->info("Products for {$customer->name} \r\n" . 'Local: ' . count($localProducts) . '  Portal: ' . count($portalProducts));
                $command = new UpdateProductsCommand($localProducts, $portalProducts, $customer, $customer->settings()->get('portal.website_code'));
                $handler = new UpdateProductsCommandHandler;
                $results = $handler->handle($command);
                $this->info($results);
            }
        }
	}

    /**
     * Get all customers
     *
     * @return mixed
     */
    protected function getCustomers()
    {
        return Company::whereType('customer')->get();
    }


}
