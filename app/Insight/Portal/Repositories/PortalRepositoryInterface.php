<?php

namespace Insight\Portal\Repositories;

interface PortalRepositoryInterface
{
    public function getOrders(OrderQuery $query);
    public function getProducts(ProductQuery $query);
    public function getUsers($group = null);
    public function getContracts();
    public function getDoa($group = null);
    public function getApprovalStatistics($dataGroup = null);
}