<?php

namespace Insight\Portal\Services;

use Insight\Companies\Company;
use Insight\Portal\Products\ProductRepository;
use Insight\Portal\Repositories\PortalRepository;
use Insight\Portal\Repositories\ProductQuery;

/**
 * Class ProductsService
 * @package Insight\Portal\Services
 */
class ProductsService
{

    /**
     * @var PortalRepository
     */
    private $portalRepository;

    /**
     * @var ProductQuery
     */
    private $query;

    /**
     * @var ProductRepository
     */
    private $productRepository;


    /**
     * OrderService constructor.
     *
     * @param PortalRepository  $portalRepository
     * @param ProductQuery      $query
     * @param ProductRepository $productRepository
     */
    public function __construct(PortalRepository $portalRepository, ProductQuery $query, ProductRepository $productRepository)
    {
        $this->portalRepository = $portalRepository;
        $this->query            = $query;
        $this->productRepository = $productRepository;
    }

    public function getLocalProducts($companyId = null)
    {
        $website = ($companyId ? $this->getWebsiteCode($companyId) : null);
        return $this->productRepository->getCustomerProducts($website);
    }

    /**
     * @param null $customer_id
     *
     * @return mixed
     */
    public function getAllProducts($customer_id = null)
    {
        $website = $this->getWebsiteCode($customer_id);
        if ($website) {
            $this->query->setParam('website', $website);
        }
        $this->query->setParam('limit', 999999);
        $this->query->setFilter('status', 1);

        return $this->portalRepository->getProducts($this->query);
    }


    /**
     * @param $customer_id
     *
     * @return mixed
     */
    protected function getWebsiteCode($customer_id)
    {
        $customer = Company::findOrFail($customer_id);

        return $customer->websiteCode();
    }


    /**
     * @param      $filters
     * @param bool $format
     *
     * @return array|mixed
     */
    public function getProductsOrdered($filters, $format = true)
    {
        $filters['report_period'] = array_get($filters, 'report_period', 'month');
        $response                 = $this->portalRepository->getProductsOrdered($filters);

        return $format ? $this->formatProductsOrderedResponse($response) : $response;
    }


    /**
     * @param $response
     *
     * @return array
     */
    protected function formatProductsOrderedResponse($response)
    {
        $products = [ ];
        $periods  = [ ];
        foreach ($response['data'] as $period) {
            $periods[] = $period['period'];
            foreach ($period['products_ordered']['data'] as $product) {
                if (empty( $product['sku'] )) {
                    continue;
                }
                if ( ! array_key_exists($product['sku'], $products)) {
                    $products[$product['sku']] = [
                        'name'        => $product['name'],
                        'sku'         => $product['sku'],
                        'uom'         => $product['uom'],
                        'price'       => $product['unit_price'],
                        'qty_ordered' => [
                            $period['period'] => $product['qty_ordered']
                        ]
                    ];
                } else {
                    $products[$product['sku']]['qty_ordered'][$period['period']] = $product['qty_ordered'];
                }
            }
        }

        return compact('products', 'periods');
    }

}
