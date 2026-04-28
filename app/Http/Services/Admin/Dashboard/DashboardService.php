<?php

namespace App\Http\Services\Admin\Dashboard;

use Illuminate\Support\Facades\Cache;
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
    protected $owner, $design, $production, $store, $purchase;
    protected $quality, $security, $logistics, $dispatch, $finance;
    protected $inventory, $cms, $estimation, $hr;

    public function __construct(
        OwnerDashboardRepository      $owner,
        DesignDashboardRepository     $design,
        ProductionDashboardRepository $production,
        StoreDashboardRepository      $store,
        PurchaseDashboardRepository   $purchase,
        QualityDashboardRepository    $quality,
        SecurityDashboardRepository   $security,
        LogisticsDashboardRepository  $logistics,
        DispatchDashboardRepository   $dispatch,
        FinanceDashboardRepository    $finance,
        InventoryDashboardRepository  $inventory,
        CMSDashboardRepository        $cms,
        EstimationDashboardRepository $estimation,
        HRDashboardRepository         $hr,
    ) {
        $this->owner      = $owner;
        $this->design     = $design;
        $this->production = $production;
        $this->store      = $store;
        $this->purchase   = $purchase;
        $this->quality    = $quality;
        $this->security   = $security;
        $this->logistics  = $logistics;
        $this->dispatch   = $dispatch;
        $this->finance    = $finance;
        $this->inventory  = $inventory;
        $this->cms        = $cms;
        $this->estimation = $estimation;
        $this->hr         = $hr;
    }

    public function getDashboardData(): array
    {
        $roleId = (int) session('role_id');

        // Map each role_id to its repository (matches config/constants.php ROLE_ID)
        $roleMap = [
            1  => fn() => $this->owner->getCounts(),      // HIGHER_AUTHORITY
            2  => fn() => $this->purchase->getCounts(),   // PURCHASE
            3  => fn() => $this->design->getCounts(),     // DESIGNER
            4  => fn() => $this->production->getCounts(), // PRODUCTION
            5  => fn() => $this->security->getCounts(),   // SECURITY
            6  => fn() => $this->quality->getCounts(),    // QUALITY
            7  => fn() => $this->store->getCounts(),      // STORE
            8  => fn() => $this->finance->getCounts(),    // FINANCE
            9  => fn() => $this->hr->getCounts(),         // HR
            10 => fn() => $this->logistics->getCounts(),  // LOGISTICS
            11 => fn() => $this->dispatch->getCounts(),   // DISPATCH
            12 => fn() => $this->cms->getCounts(),        // CMS
            13 => fn() => $this->hr->getCounts(),         // EMPLOYEE (reuse HR repo)
            14 => fn() => $this->inventory->getCounts(),  // INVENTORY
            15 => fn() => $this->estimation->getCounts(), // ESTIMATION
        ];

        $userId   = (int) session('user_id');
        $cacheKey = "dashboard_{$userId}_{$roleId}";

        // Cache dashboard counts for 5 minutes — resets on any write via model events
        $roleData = Cache::remember($cacheKey, 300, function () use ($roleId, $roleMap) {
            if (isset($roleMap[$roleId])) {
                return ($roleMap[$roleId])();
            }
            // Fallback: run all (should never happen in production)
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
        });

        // HR leave data is always needed for the leave chart (all roles)
        $hrData = Cache::remember("dashboard_hr_{$userId}", 300, function () {
            return $this->hr->getCounts();
        });

        return array_merge($roleData, $hrData);
    }
}
