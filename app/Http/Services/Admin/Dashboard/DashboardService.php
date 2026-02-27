<?php

namespace App\Http\Services\Admin\Dashboard;

use App\Http\Repository\Admin\Dashboard\{
    OwnerDashboardRepository,
    DesignDashboardRepository,
    ProductionDashboardRepository,
    StoreDashboardRepository,
    PurchaseDashboardRepository,
    SecurityDashboardRepository,
    QualityDashboardRepository,
    LogisticsDashboardRepository,
    DispatchDashboardRepository,
    FinanceDashboardRepository,
    InventoryDashboardRepository,
    CMSDashboardRepository,
    EstimationDashboardRepository,
    HRDashboardRepository,
};

class DashboardService
{
    protected $owner;
    protected $design;
    protected $production;
    protected $store;
    protected $purchase;
    protected $quality;
    protected $security;
    protected $logistics;
    protected $dispatch;
    protected $finance;
    protected $inventory;
    protected $cms;
    protected $estimation;
    protected $hr;




    public function __construct(
        OwnerDashboardRepository $owner,
        DesignDashboardRepository $design,
        ProductionDashboardRepository $production,
        StoreDashboardRepository $store,
        PurchaseDashboardRepository $purchase,
        QualityDashboardRepository $quality,
        SecurityDashboardRepository $security,
        LogisticsDashboardRepository $logistics,
        DispatchDashboardRepository $dispatch,
        FinanceDashboardRepository $finance,
        InventoryDashboardRepository $inventory,
        CMSDashboardRepository $cms,
        EstimationDashboardRepository $estimation,
        HRDashboardRepository $hr,

    ) {
        $this->owner = $owner;
        $this->design = $design;
        $this->production = $production;
        $this->store = $store;
        $this->purchase = $purchase;
        $this->quality = $quality;
        $this->security = $security;
        $this->logistics = $logistics;
        $this->dispatch = $dispatch;
        $this->finance = $finance;
        $this->inventory = $inventory;
        $this->cms = $cms;
        $this->estimation = $estimation;
        $this->hr = $hr;
    }

    public function getDashboardData()
    {
        return array_merge(
            $this->owner->getCounts(),
            $this->design->getCounts(),
            $this->production->getCounts(),
            $this->store->getCounts(),
            $this->purchase->getCounts(),
            $this->quality->getCounts(),
            $this->security->getCounts(),
            $this->logistics->getCounts(),
            $this->dispatch->getCounts(),
            $this->finance->getCounts(),
            $this->cms->getCounts(),
            $this->inventory->getCounts(),
            $this->estimation->getCounts(),
            $this->hr->getCounts()
        );
    }
}
