 <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Artisan;
    use App\Http\Controllers\Admin\LoginRegister\LoginController;
    use App\Http\Controllers\Admin\LoginRegister\RegisterController;
    use App\Http\Controllers\Admin\Dashboard\DashboardController;
    use App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController;
    use App\Http\Controllers\Admin\Organization\OrganizationController;
    use App\Http\Controllers\Admin\Departments\DepartmentController;
    use App\Http\Controllers\Admin\Roles\RolesController;
    use App\Http\Controllers\Organizations\Business\BusinessController;
    use App\Http\Controllers\Organizations\Business\AllListController as BusinessAllListController;
    use App\Http\Controllers\Organizations\Report\ReportController;
    use App\Http\Controllers\Organizations\Designers\DesignUploadController;
    use App\Http\Controllers\Organizations\Designers\AllListController;
    use App\Http\Controllers\Organizations\Estimation\AllListController as EstimationAllListController;
    use App\Http\Controllers\Organizations\Estimation\EstimationController;
    use App\Http\Controllers\Organizations\Estimation\ProductionController as EstimationProductionController;
    use App\Http\Controllers\Organizations\Productions\AllListController as ProductionAllListController;
    use App\Http\Controllers\Organizations\Productions\ProductionController;
    use App\Http\Controllers\Organizations\Store\RequistionController;
    use App\Http\Controllers\Organizations\Store\StoreController;
    use App\Http\Controllers\Organizations\Store\AllListController as StoreAllListController;
    use App\Http\Controllers\Organizations\Store\RejectedChalanController;
    use App\Http\Controllers\Organizations\Store\DeliveryChalanController;
    use App\Http\Controllers\Organizations\Store\ReturnableChalanController;
    use App\Http\Controllers\Organizations\Master\UnitController;
    use App\Http\Controllers\Organizations\Master\HSNController;
    use App\Http\Controllers\Organizations\Master\GroupController;
    use App\Http\Controllers\Organizations\Master\RackController;
    use App\Http\Controllers\Organizations\Master\ProcessController;
    use App\Http\Controllers\Organizations\Master\AccessoriesController;
    use App\Http\Controllers\Organizations\Inventory\InventoryController;
    use App\Http\Controllers\Organizations\Purchase\PurchaseOrderController;
    use App\Http\Controllers\Organizations\Purchase\VendorController;
    use App\Http\Controllers\Organizations\Purchase\TaxController;
    use App\Http\Controllers\Organizations\Purchase\VendorTypeController;
    use App\Http\Controllers\Organizations\Purchase\ItemController;
    use App\Http\Controllers\Organizations\Purchase\AllListController as PurchaseAllListController;
    use App\Http\Controllers\Organizations\Security\GatepassController;
    use App\Http\Controllers\Organizations\Security\AllListController as SecurityAllListController;
    use App\Http\Controllers\Organizations\Quality\GRNController;
    use App\Http\Controllers\Organizations\Finance\FinanceController;
    use App\Http\Controllers\Organizations\Finance\AllListController as FinanceAllListController;
    use App\Http\Controllers\Organizations\Logistics\AllListController as LogisticsAllListController;
    use App\Http\Controllers\Organizations\Logistics\LogisticsController;
    use App\Http\Controllers\Organizations\Logistics\VehicleTypeController;
    use App\Http\Controllers\Organizations\Logistics\NameOfTransportController;
    use App\Http\Controllers\Organizations\Dispatch\AllListController as DispatchAllListController;
    use App\Http\Controllers\Organizations\Dispatch\DispatchController;
    use App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController;
    use App\Http\Controllers\Organizations\HR\Leaves\LeavesController;
    use App\Http\Controllers\Organizations\HR\NoticeController;
    use App\Http\Controllers\Admin\CMS\VisionMissionController;
    use App\Http\Controllers\Admin\CMS\ServicesController;
    use App\Http\Controllers\Admin\CMS\TestimonialController;
    use App\Http\Controllers\Admin\CMS\ProductController;
    use App\Http\Controllers\Admin\CMS\DirectorDeskController;
    use App\Http\Controllers\Admin\CMS\TeamController;
    use App\Http\Controllers\Admin\CMS\ContactUsListController;
    use App\Http\Controllers\Website\PagesController;
    use App\Http\Controllers\Website\AboutController;
    use App\Http\Controllers\Website\ProductServicesController;
    use App\Http\Controllers\Website\ContactUsController;
    // use App\Http\Controllers\Organizations\Dashboard\DashboardController;
    // use App\Http\Controllers\Organizations\Productions\ProductionController;
    use App\Http\Controllers\Organizations\Store\DocUploadFianaceController;
    use App\Http\Controllers\Organizations\Security\SecurityRemarkController;
    use App\Http\Controllers\Organizations\Store\StoreReceiptController;
    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

    Route::get('/', fn() => view('welcome'));

    // ✅ Login & Register
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'submitLogin'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');

    // ✅ Clear cache (safe for dev only)
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');
        return 'Cache cleared';
    });

    // ✅ Admin Routes
    Route::middleware(['admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/get-notification', [DashboardController::class, 'getNotification'])->name('get-notification');
        Route::get('/get-offcanvas-data', [DashboardController::class, 'getOffcanvas'])->name('get-offcanvas-data');
        Route::get('/admin-log-out', [LoginController::class, 'logout'])->name('log-out');

        // Organizations
        Route::prefix('organizations')->group(function () {
            Route::get('/', [OrganizationController::class, 'index'])->name('list-organizations');
            Route::get('/add', [OrganizationController::class, 'add'])->name('add-organizations');
            Route::post('/store', [OrganizationController::class, 'store'])->name('store-organizations');
            Route::get('/edit/{id}', [OrganizationController::class, 'edit'])->name('edit-organizations');
            Route::post('/update', [OrganizationController::class, 'update'])->name('update-organizations');
            Route::delete('/delete/{id}', [OrganizationController::class, 'destroy'])->name('delete-organizations');
            Route::get('/details/{id}', [OrganizationController::class, 'details'])->name('organization-details');
            Route::get('/filter-employees/{id}', [OrganizationController::class, 'filterEmployees'])->name('filter-employees');
        });


        Route::any('/list-departments', [DepartmentController::class, 'index'])->name('list-departments');
        Route::any('/add-departments', [DepartmentController::class, 'add'])->name('add-departments');
        Route::any('/store-departments', [DepartmentController::class, 'store'])->name('store-departments');
        Route::any('/edit-departments/{id}', [DepartmentController::class, 'edit'])->name('edit-departments');
        Route::any('/update-departments', [DepartmentController::class, 'update'])->name('update-departments');
        Route::any('/delete-departments/{id}', [DepartmentController::class, 'destroy'])->name('delete-departments');

        Route::any('/list-roles', [RolesController::class, 'index'])->name('list-roles');
        Route::any('/add-roles', [RolesController::class, 'add'])->name('add-roles');
        Route::any('/store-roles', [RolesController::class, 'store'])->name('store-roles');
        Route::any('/edit-roles/{id}', [RolesController::class, 'edit'])->name('edit-roles');
        Route::any('/update-roles', [RolesController::class, 'update'])->name('update-roles');
        Route::any('/delete-roles/{id}', [RolesController::class, 'destroy'])->name('delete-roles');

        // Departments
        Route::resource('departments', DepartmentController::class)->except(['show']);

        // Roles
        Route::resource('roles', RolesController::class)->except(['show']);
    });

    // ✅ Owner Routes
    Route::prefix('owner')->middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
        Route::get('/get-offcanvas', [DashboardController::class, 'getOffcanvas'])->name('owner.get-offcanvas');

        // Business
        Route::get('/list-business', [BusinessController::class, 'index'])->name('list-business');
        Route::get('/add-business', [BusinessController::class, 'add'])->name('add-business');
        Route::post('/store-business', [BusinessController::class, 'store'])->name('store-business');
        Route::get('/edit-business/{id}', [BusinessController::class, 'edit'])->name('edit-business');
        Route::post('/update-business', [BusinessController::class, 'update'])->name('update-business');
        Route::any('/delete-business/{id}', [BusinessController::class, 'deleteBusiness'])->name('delete-business');

        // Reports
        Route::get('/list-product-completed-report', [ReportController::class, 'getCompletedProductList'])->name('list-product-completed-report');
        Route::get('/list-product-completed-report-ajax', [ReportController::class, 'getCompletedProductListAjax'])->name('list-product-completed-report-ajax');

        // All Lists

        Route::get('/list-forwarded-to-design', [BusinessAllListController::class, 'getAllListForwardedToDesign'])->name('list-forwarded-to-design');
        Route::get('/list-design-correction', [BusinessAllListController::class, 'getAllListCorrectionToDesignFromProduction'])->name('list-design-correction');
        Route::get('/material-ask-by-prod-to-store', [BusinessAllListController::class, 'materialAskByProdToStore'])
            ->name('material-ask-by-prod-to-store');
        Route::get('/material-ask-by-store-to-purchase', [BusinessAllListController::class, 'getAllStoreDeptSentForPurchaseMaterials'])
            ->name('material-ask-by-store-to-purchase');
        Route::get('/list-rejected-chalan-updated', [RejectedChalanController::class, 'getAllRejectedChalanList'])
            ->name('list-rejected-chalan-updated');
        // ... (continue grouping all the remaining "AllListController" routes neatly here)

        // Purchase Orders - Approved
        Route::get('/list-purchase-orders', [BusinessAllListController::class, 'getAllListPurchaseOrder'])->name('list-purchase-orders');


        Route::get('/list-submit-final-purchase-order/{id}', [BusinessAllListController::class, 'submitFinalPurchaseOrder'])->name('list-submit-final-purchase-order');
        Route::get('/list-submit-final-purchase-order-particular-business/{purchase_order_id}', [BusinessController::class, 'getPurchaseOrderDetails'])->name('list-submit-final-purchase-order-particular-business');

        //  Route::get('/list-submit-final-purchase-order/{id}', ['as' => 'list-submit-final-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@submitFinalPurchaseOrder']);
        // Route::get('/list-submit-final-purchase-order-particular-business/{purchase_order_id}', ['as' => 'list-submit-final-purchase-order-particular-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@getPurchaseOrderDetails']);

        Route::get('/list-approved-purchase-orders-owner', [BusinessAllListController::class, 'getAllListApprovedPurchaseOrderOwnerlogin'])
            ->name('list-approved-purchase-orders-owner');
        Route::get('/list-purchase-order-approved-bussinesswise/{id}', [BusinessAllListController::class, 'submitFinalPurchaseOrder'])
            ->name('list-purchase-order-approved-bussinesswise');

        // Purchase Orders - Rejected
        Route::get('/list-rejected-purchase-orders-owner', [BusinessAllListController::class, 'getAllListRejectedPurchaseOrderOwnerlogin'])
            ->name('list-rejected-purchase-orders-owner');
        Route::get('/list-purchase-order-rejected-bussinesswise/{id}', [BusinessAllListController::class, 'getPurchaseOrderRejectedBusinessWise'])
            ->name('list-purchase-order-rejected-bussinesswise');

        // Design Received for Estimation
        Route::get('/list-design-received-estimation', [BusinessAllListController::class, 'loadDesignSubmittedForEstimation'])
            ->name('list-design-received-estimation');
        Route::get('/list-design-received-estimation-business-wise/{business_details_id}', [BusinessAllListController::class, 'loadDesignSubmittedForEstimationBusinessWise'])
            ->name('list-design-received-estimation-business-wise');

        // Accept BOM Estimation
        Route::get('/accept-bom-estimation/{id}', [BusinessController::class, 'acceptEstimationBOM'])
            ->name('accept-bom-estimation');
        Route::get('/list-accept-bom-estimation', [BusinessAllListController::class, 'getAcceptEstimationBOM'])
            ->name('list-accept-bom-estimation');
        Route::get('/list-accept-bom-estimation-business-wise/{id}', [BusinessAllListController::class, 'getAcceptEstimationBOMBusinessWise'])
            ->name('list-accept-bom-estimation-business-wise');

        // Edit / Reject Estimation - Owner Side
        Route::get('/edit-reject-estimation-owner-side/{id}', [BusinessController::class, 'editRejectEstimation'])
            ->name('edit-reject-estimation-owner-side');
        Route::post('/add-rejected-bom-estimation', [BusinessController::class, 'addRejectedEstimationBOM'])
            ->name('add-rejected-bom-estimation');
        Route::get('/list-rejected-bom-estimation', [BusinessAllListController::class, 'getRejectEstimationBOM'])
            ->name('list-rejected-bom-estimation');
        Route::get('/list-rejected-bom-estimation-business-wise/{id}', [BusinessAllListController::class, 'getRejectEstimationBOMBusinessWise'])
            ->name('list-rejected-bom-estimation-business-wise');

        // Revised BOM Estimation
        Route::get('/list-revised-bom-estimation', [BusinessAllListController::class, 'getRevisedEstimationBOM'])
            ->name('list-revised-bom-estimation');
        Route::get('/list-revised-bom-estimation-business-wise/{id}', [BusinessAllListController::class, 'getRevisedEstimationBOMBusinessWise'])
            ->name('list-revised-bom-estimation-business-wise');

        Route::get('/list-purchase-order', [BusinessAllListController::class, 'getAllListPurchaseOrder'])
            ->name('list-purchase-order');
        // Design Uploaded - Owner
        Route::get('/list-design-uploaded-owner', [BusinessAllListController::class, 'loadDesignSubmittedForProduction'])
            ->name('list-design-uploaded-owner');
        Route::get('/list-design-uploaded-owner-business-wise/{business_id}', [BusinessAllListController::class, 'loadDesignSubmittedForProductionBusinessWise'])
            ->name('list-design-uploaded-owner-business-wise');

        // PO Received for Approval Payment
        Route::get('/list-po-recived-for-approval-payment', [BusinessAllListController::class, 'listPOReceivedForApprovaTowardsOwner'])
            ->name('list-po-recived-for-approval-payment');
        Route::get('/accept-purchase-order-payment-release/{purchase_order_id}/{business_id}', [BusinessController::class, 'acceptPurchaseOrderPaymentRelease'])
            ->name('accept-purchase-order-payment-release');
        Route::get('/list-release-approval-payment-by-vendor', [BusinessAllListController::class, 'listPOPaymentReleaseByVendor'])
            ->name('list-release-approval-payment-by-vendor');

        Route::get('/accept-purchase-order/{purchase_order_id}/{business_id}', ['as' => 'accept-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@acceptPurchaseOrder']);
        Route::get('/rejected-purchase-order/{purchase_order_id}/{business_id}', ['as' => 'rejected-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@rejectedPurchaseOrder']);

        // Login History
        Route::get('/list-login-history', [BusinessAllListController::class, 'listLoginHistory'])
            ->name('list-login-history');
        Route::get('/show-login-history/{id}', [BusinessAllListController::class, 'showLoginHistory'])
            ->name('show-login-history');

        // Product Dispatch Completed
        Route::get('/list-product-dispatch-completed', [BusinessAllListController::class, 'listProductDispatchCompletedFromDispatch'])
            ->name('list-product-dispatch-completed');

        // Delete Addmore
        Route::post('/delete-addmore', [BusinessController::class, 'destroyAddmore'])
            ->name('delete-addmore');

        // Owner Submitted PO / Gatepass / GRN / Material
        Route::get('/list-owner-submited-po-to-vendor', [BusinessAllListController::class, 'getAllListSubmitedPurchaeOrderByVendorOwnerside'])
            ->name('list-owner-submited-po-to-vendor');
        Route::get('/list-owner-gatepass', [BusinessAllListController::class, 'getOwnerReceivedGatePass'])
            ->name('list-owner-gatepass');
        Route::get('/list-owner-grn', [BusinessAllListController::class, 'getOwnerGRN'])
            ->name('list-owner-grn');
        Route::get('/list-material-sent-to-store-generated-grn', [BusinessAllListController::class, 'getAllListMaterialSentFromQualityToStoreGeneratedGRN'])
            ->name('list-material-sent-to-store-generated-grn');
        Route::get('/list-material-sent-to-store-generated-grn-businesswise/{id}', [BusinessAllListController::class, 'getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise'])
            ->name('list-material-sent-to-store-generated-grn-businesswise');
        Route::get('/list-owner-material-recived-from-store', [BusinessAllListController::class, 'getOwnerAllListMaterialRecievedToProduction'])
            ->name('list-owner-material-recived-from-store');
        Route::get('/list-owner-final-production-completed', [BusinessAllListController::class, 'getOwnerAllCompletedProduction'])
            ->name('list-owner-final-production-completed');
        Route::get('/list-owner-final-production-completed-recive-to-logistics', [BusinessAllListController::class, 'getOwnerFinalAllCompletedProductionLogistics'])
            ->name('list-owner-final-production-completed-recive-to-logistics');
        Route::get('/recive-owner-logistics-list', [BusinessAllListController::class, 'getOwnerAllListBusinessReceivedFromLogistics'])
            ->name('recive-owner-logistics-list');
        Route::get('/list-owner-send-to-dispatch', [BusinessAllListController::class, 'getOwnerAllListBusinessFianaceSendToDispatch'])
            ->name('list-owner-send-to-dispatch');

        // Reports
        Route::get('/list-product-completed-report', [ReportController::class, 'getCompletedProductList'])
            ->name('list-product-completed-report');
        Route::get('/list-product-completed-report-ajax', [ReportController::class, 'getCompletedProductListAjax'])
            ->name('list-product-completed-report-ajax');
    });

    Route::group(['prefix' => 'designdept', 'middleware' => 'admin'], function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Design Upload Routes
        Route::get('/list-new-requirements-received-for-design', [DesignUploadController::class, 'getAllNewRequirement'])->name('list-new-requirements-received-for-design');
        Route::get('/list-new-requirements-received-for-design-businesswise/{id}', [DesignUploadController::class, 'getAllNewRequirementBusinessWise'])->name('list-new-requirements-received-for-design-businesswise');
        Route::get('/list-design-upload', [DesignUploadController::class, 'getUploadedDesignSendEstimation'])->name('list-design-upload');
        Route::get('/add-design-upload/{id}', [DesignUploadController::class, 'add'])->name('add-design-upload');
        Route::post('/store-design-upload', [DesignUploadController::class, 'store'])->name('store-design-upload');
        Route::get('/edit-design-upload/{id}', [DesignUploadController::class, 'edit'])->name('edit-design-upload');
        Route::post('/update-design-upload', [DesignUploadController::class, 'update'])->name('update-design-upload');
        Route::get('/add-re-upload-design/{id}', [DesignUploadController::class, 'addReUploadDesing'])->name('add-re-upload-design');
        Route::post('/update-re-design-upload', [DesignUploadController::class, 'updateReUploadDesign'])->name('update-re-design-upload');

        // Design Correction / Production List
        Route::get('/list-updated-design', [AllListController::class, 'getAllListCorrectedDesignSendToProduction'])->name('list-updated-design');
        Route::get('/list-reject-design-from-prod', [AllListController::class, 'getAllListDesignReceivedForCorrection'])->name('list-reject-design-from-prod');
        Route::get('/list-accept-design-by-production', [AllListController::class, 'acceptDesignByProduct'])->name('list-accept-design-by-production');

        // Reports
        Route::get('/list-design-report', [ReportController::class, 'listDesignReport'])->name('list-design-report');
        Route::get('/design-ajax', [ReportController::class, 'listDesignReportAjax'])->name('design-ajax');

        Route::get('/list-consumption-report', [ReportController::class, 'listConsumptionReport'])->name('list-consumption-report');
        Route::get('/consumption-ajax', [ReportController::class, 'listConsumptionReportAjax'])->name('consumption-ajax');
        Route::get('/list-consumption/{id}', [ReportController::class, 'getConsumptionMaterialList'])->name('list-consumption');
        Route::get('/get-products-by-project/{id}', [ReportController::class, 'getProductsByProject']);

        Route::get('/list-items-stock-report', [ReportController::class, 'listItemStockReport'])->name('list-items-stock-report');
        Route::get('/items-stock-ajax', [ReportController::class, 'listItemStockReportAjax'])->name('items-stock-ajax');

        Route::get('/list-logistics-report', [ReportController::class, 'listLogisticsReport'])->name('list-logistics-report');
        Route::get('/logistics-ajax', [ReportController::class, 'listLogisticsReportAjax'])->name('logistics-ajax');

        Route::get('/list-finance-report', [ReportController::class, 'listFiananceReport'])->name('list-finance-report');
        Route::get('/finance-ajax', [ReportController::class, 'listFiananceReportAjax'])->name('finance-ajax');

        Route::get('/list-vendor-payment-report', [ReportController::class, 'listVendorPaymentReport'])->name('list-vendor-payment-report');
        Route::get('/vendor-payment-ajax', [ReportController::class, 'listVendorPaymentReportAjax'])->name('vendor-payment-ajax');

        Route::get('/list-dispatch-report', [ReportController::class, 'listDispatchReport'])->name('list-dispatch-report');
        Route::get('/dispatch-ajax', [ReportController::class, 'listDispatchReportAjax'])->name('dispatch-ajax');

        Route::get('/dispatch-pending-report', [ReportController::class, 'listPendingDispatchReport'])->name('dispatch-pending-report');
        Route::get('/pending-dispatch-ajax', [ReportController::class, 'listPendingDispatchReportAjax'])->name('pending-dispatch-ajax');

        Route::get('/stock-daily-report', [ReportController::class, 'listStockDailyReport'])->name('stock-daily-report');
        Route::get('/stock-daily-report-ajax', [ReportController::class, 'listStockDailyReportAjax'])->name('stock-daily-report-ajax');

        Route::get('/list-itemwise-vendor-rate-report', [ReportController::class, 'listItemWiseVendorRateReport'])->name('list-itemwise-vendor-rate-report');
        Route::get('/list-itemwise-vendor-rate-report-ajax', [ReportController::class, 'listItemWiseVendorRateReportAjax'])->name('list-itemwise-vendor-rate-report-ajax');

        Route::get('/list-dispatch-bar-chart', [ReportController::class, 'listDispatchBarChart'])->name('list-dispatch-bar-chart');

        Route::get('/list-vendor-through-taken-material', [ReportController::class, 'listVendorThroughTakenMaterial'])->name('list-vendor-through-taken-material');
        Route::get('/list-vendor-through-taken-material-ajax', [ReportController::class, 'listVendorThroughTakenMaterialAjax'])->name('list-vendor-through-taken-material-ajax');
        Route::get('/vendor-through-taken-material-data/{id}', [ReportController::class, 'listVendorThroughTakenMaterialVendorId'])->name('vendor-through-taken-material-data');
        Route::get('/vendor-through-taken-material-data-ajax/{id}', [ReportController::class, 'listVendorThroughTakenMaterialVendorIdAjax'])->name('vendor-through-taken-material-data-ajax');

        Route::get('/stock-item', [ReportController::class, 'getStockItem'])->name('stock-item');
        Route::get('/stock-item-ajax', [ReportController::class, 'getStockItemAjax'])->name('stock-item-ajax');

        Route::get('/store-item-stock-list', [ReportController::class, 'getStoreItemStockList'])->name('store-item-stock-list');
        Route::get('/store-item-stock-list-ajax', [ReportController::class, 'getStoreItemStockListAjax'])->name('store-item-stock-list-ajax');

        Route::get('/item-stock-history-list', [ReportController::class, 'getItemStockHistoryList'])->name('item-stock-history-list');
        Route::get('/item-stock-history-list-ajax', [ReportController::class, 'getItemStockListHistoryAjax'])->name('item-stock-history-list-ajax');
    });

    // ===================== ESTIMATION DEPT =====================
    Route::group(['prefix' => 'estimationdept', 'middleware' => 'admin'], function () {

        // Dashboard (unique name for estimation dashboard)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('estimation-dashboard');


        // Reports
        Route::get('/estimation-report', [ReportController::class, 'getEstimationReport'])->name('estimation-report');
        Route::get('/estimation-report-ajax', [ReportController::class, 'getEstimationReportAjax'])->name('estimation-report-ajax');

        // All Lists

        // All List
        Route::get('/list-new-requirements-received-for-estimation', [EstimationAllListController::class, 'getAllNewRequirement'])->name('list-new-requirements-received-for-estimation');
        Route::get('/list-new-requirements-received-for-estimation-business-wise/{business_id}', [EstimationAllListController::class, 'getAllNewRequirementBusinessWise'])->name('list-new-requirements-received-for-estimation-business-wise');

        // Estimation CRUD
        Route::get('/edit-estimation/{id}', [EstimationController::class, 'editEstimation'])->name('edit-estimation');
        Route::post(
            '/check-estimation-amount',
            [EstimationController::class, 'checkEstimationAmount']
        )->name('check.estimation.amount');
        Route::post('/update-estimation', [EstimationController::class, 'updateEstimation'])->name('update-estimation');

        // Send to Owner
        Route::get('/list-updated-estimation-send-to-owner', [EstimationAllListController::class, 'getAllEstimationSendToOwnerForApproval'])->name('list-updated-estimation-send-to-owner');
        Route::get('/list-updated-estimation-send-to-owner-business-wise/{business_id}', [EstimationAllListController::class, 'getAllEstimationSendToOwnerForApprovalBusinessWise'])->name('list-updated-estimation-send-to-owner-business-wise');

        // Revised Estimation
        Route::get('/edit-revised-bom-material-estimation/{id}', [EstimationController::class, 'editRevisedEstimation'])->name('edit-revised-bom-material-estimation');
        Route::post('/update-edit-revised-bom-material-estimation', [EstimationController::class, 'updateRevisedEstimation'])->name('update-edit-revised-bom-material-estimation');

        // Send to Production
        Route::post('/send-to-production/{id}', [EstimationController::class, 'sendToProduction'])->name('send-to-production');
        Route::get('/list-send-to-production', [EstimationAllListController::class, 'getSendToProductionList'])->name('list-send-to-production');

        // Accept/Reject Design
        // Route::get('/accept-design/{id}', [EstimationProductionController::class, 'acceptdesign'])->name('accept-design');
        // Route::get('/reject-design-edit/{id}', [EstimationProductionController::class, 'rejectdesignedit'])->name('reject-design-edit');
        // Route::post('/reject-design', [EstimationProductionController::class, 'rejectdesign'])->name('estimation-reject-design');

        // Revised Material Received
        Route::get('/list-revised-material-received-design', [EstimationAllListController::class, 'reviseddesignlist'])->name('list-revised-material-received-design');

        // Reports
        Route::get('/list-production-report', [ReportController::class, 'getProductionReport'])->name('list-production-report');
        Route::get('/production-report-ajax', [ReportController::class, 'getProductionReportAjax'])->name('production-report-ajax');
    });


    // ===================== PRODUCTION DEPT =====================
    Route::group(['prefix' => 'proddept', 'middleware' => 'admin'], function () {

        // Dashboard (unique name for production dashboard)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('production-dashboard');

        // Accept/Reject Design
        Route::get('/accept-design/{id}', [ProductionController::class, 'acceptdesign'])->name('accept-design');
        Route::get('/reject-design-edit/{id}', [ProductionController::class, 'rejectdesignedit'])->name('reject-design-edit');
        Route::post('/reject-design', [ProductionController::class, 'rejectdesign'])->name('reject-design');

        // New Requirements
        Route::get('/list-new-requirements-received-for-production', [ProductionAllListController::class, 'getAllNewRequirement'])->name('list-new-requirements-received-for-production');
        Route::get('/list-new-requirements-received-for-production-business-wise', [ProductionAllListController::class, 'getAllNewRequirementBusinessWise'])->name('list-new-requirements-received-for-production-business-wise');

        // Accept/Reject Lists
        Route::get('/list-accept-design', [ProductionAllListController::class, 'acceptdesignlist'])->name('list-accept-design');
        Route::get('/list-accept-design-business-wise/{business_id}', [ProductionAllListController::class, 'acceptdesignlistBusinessWise'])->name('list-accept-design-business-wise');
        Route::get('/list-reject-design', [ProductionAllListController::class, 'rejectdesignlist'])->name('list-reject-design');

        // Revised Material
        Route::get('/list-revislist-material-reciveded-design', [ProductionAllListController::class, 'reviseddesignlist'])->name('list-revislist-material-reciveded-design');
        // Materials
        Route::get('/list-material-received', [ProductionAllListController::class, 'getAllListMaterialRecievedToProduction'])->name('list-material-received');
        Route::get('/list-final-purchase-order-production/{id}', [ProductionAllListController::class, 'getAllListMaterialRecievedToProductionBusinessWise'])->name('list-final-purchase-order-production');

        Route::get('/edit-received-inprocess-production-material/{id}', [ProductionController::class, 'editProduct'])->name('edit-received-inprocess-production-material');
        Route::post('/update-received-inprocess-production-material/{id}', [ProductionController::class, 'updateProductMaterial'])->name('update-received-inprocess-production-material');
        Route::post('/delete-addmore-production-material-item', [ProductionController::class, 'destroyAddmoreStoreItem'])->name('delete-addmore-production-material-item');
        Route::post('/update-final-production-completed-status/{id}', [ProductionController::class, 'acceptProductionCompleted'])->name('update-final-production-completed-status');

        Route::post('/update-production/{id}', [ProductionController::class, 'updateProductMaterial'])->name('update-production');

        // Completed Production
        Route::get('/list-final-production-completed', [ProductionAllListController::class, 'getAllCompletedProduction'])->name('list-final-production-completed');
        Route::get('/list-final-prod-completed-send-to-logistics-tracking', [ProductionAllListController::class, 'getAllCompletedProductionSendToLogistics'])->name('list-final-prod-completed-send-to-logistics-tracking');
        Route::get('/list-final-prod-completed-send-to-logistics-tracking-product-wise/{id}', [ProductionAllListController::class, 'getAllCompletedProductionSendToLogisticsProductWise'])->name('list-final-prod-completed-send-to-logistics-tracking-product-wise');

        Route::get('/edit-received-businesswise-quantity-tracking/{id}', [ProductionController::class, 'editProductQuantityTracking'])->name('edit-received-businesswise-quantity-tracking');

        // Reports
        Route::get('/list-production-report', [ReportController::class, 'getProductionReport'])->name('list-production-report');
        Route::get('/production-report-ajax', [ReportController::class, 'getProductionReportAjax'])->name('production-production-report-ajax');
    });

    Route::group(['prefix' => 'storedept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/list-requisition', [RequistionController::class, 'index'])->name('list-requisition');
        Route::get('/add-requisition', [RequistionController::class, 'add'])->name('add-requisition');
        Route::get('/edit-requisition', [RequistionController::class, 'edit'])->name('edit-requisition');

        // Store Operations
        Route::get('/accepted-and-material-sent/{id}', [StoreController::class, 'orderAcceptedAndMaterialForwareded'])->name('accepted-and-material-sent');
        Route::get('/need-to-create-req/{id}', [StoreController::class, 'createRequesition'])->name('need-to-create-req');
        Route::post('/store-purchase-request-req', [StoreController::class, 'storeRequesition'])->name('store-purchase-request-req');
        Route::post('/generate-sr-store-dept', [StoreController::class, 'generateSRstoreDept'])->name('generate-sr-store-dept');
        Route::get('/accepted-store-material-sent-to-production/{purchase_orders_id}/{business_id}', [StoreController::class, 'genrateStoreReciptAndForwardMaterialToTheProduction'])->name('accepted-store-material-sent-to-production');
        Route::get('/get-part-item-rate', [StoreController::class, 'getPartItemRate'])->name('get-part-item-rate');
        Route::get('/check-stock-quantity', [StoreController::class, 'checkStockQuantity'])->name('check-stock-quantity');
        Route::post('/deleted-addmore-store-material-item', [StoreController::class, 'destroyAddmoreStoreItem'])->name('deleted-addmore-store-material-item');
        Route::get('/edit-material-list-bom-wise-new-req/{id}', [StoreController::class, 'editProductMaterialWiseAddNewReq'])->name('edit-material-list-bom-wise-new-req');
        Route::post('/update-material-list-bom-wise-new-req/{id}', [StoreController::class, 'updateProductMaterialWiseAddNewReq'])->name('update-material-list-bom-wise-new-req');

        // Store All List
        Route::get('/list-accepted-design-from-prod', [StoreAllListController::class, 'getAllListDesignRecievedForMaterial'])->name('list-accepted-design-from-prod');
        Route::get('/list-accepted-design-from-prod-business-wise/{business_id}', [StoreAllListController::class, 'getAllListDesignRecievedForMaterialBusinessWise'])->name('list-accepted-design-from-prod-business-wise');
        Route::get('/list-material-sent-to-prod', [StoreAllListController::class, 'getAllListMaterialSentToProduction'])->name('list-material-sent-to-prod');
        Route::get('/list-material-sent-to-purchase', [StoreAllListController::class, 'getAllListMaterialSentToPurchase'])->name('list-material-sent-to-purchase');
        Route::get('/list-material-received-from-quality', [StoreAllListController::class, 'getAllListMaterialReceivedFromQuality'])->name('list-material-received-from-quality');
        Route::get('/list-material-received-from-quality-bussinesswise', [StoreAllListController::class, 'submitFinalPurchaseOrder'])->name('list-material-received-from-quality-bussinesswise');
        Route::get('/list-material-received-from-quality-po-tracking', [StoreAllListController::class, 'getAllListMaterialReceivedFromQualityPOTracking'])->name('list-material-received-from-quality-po-tracking');
        Route::get('/list-material-received-from-quality-bussinesswise-tracking', [StoreAllListController::class, 'getAllListMaterialReceivedFromQualityPOTrackingBusinessWise'])->name('list-material-received-from-quality-bussinesswise-tracking');
        Route::get('/list-product-inprocess-received-from-production', [StoreAllListController::class, 'getAllInprocessProductProduction'])->name('list-product-inprocess-received-from-production');

        // Rejected Chalan
        Route::get('/list-rejected-chalan', [RejectedChalanController::class, 'index'])->name('list-rejected-chalan');
        Route::get('/add-rejected-chalan/{purchase_orders_id}/{id}', [RejectedChalanController::class, 'add'])->name('add-rejected-chalan');
        Route::post('/store-rejected-chalan', [RejectedChalanController::class, 'store'])->name('store-rejected-chalan');
        Route::get('/list-rejected-chalan-updated', [RejectedChalanController::class, 'getAllRejectedChalanList'])->name('list-rejected-chalan-updated');
        Route::get('/list-rejected-chalan-details/{purchase_orders_id}/{id}', [RejectedChalanController::class, 'getAllRejectedChalanDetailsList'])->name('list-rejected-chalan-details');

        // Delivery Chalan
        Route::get('/list-delivery-chalan', [DeliveryChalanController::class, 'index'])->name('list-delivery-chalan');
        Route::post('/add-delivery-chalan', [DeliveryChalanController::class, 'create'])->name('add-delivery-chalan');
        Route::post('/store-delivery-chalan', [DeliveryChalanController::class, 'store'])->name('store-delivery-chalan');
        Route::get('/show-delivery-chalan/{id}', [DeliveryChalanController::class, 'show'])->name('show-delivery-chalan');
        Route::get('/edit-delivery-chalan/{id}', [DeliveryChalanController::class, 'edit'])->name('edit-delivery-chalan');
        Route::any('/update-delivery-chalan', [DeliveryChalanController::class, 'update'])->name('update-delivery-chalan');
        Route::any('/delete-delivery-chalan/{id}', [DeliveryChalanController::class, 'destroy'])->name('delete-delivery-chalan');
        Route::post('/delete-addmore-delivery', [DeliveryChalanController::class, 'destroyAddmore'])->name('delete-addmore-delivery');
        Route::get('/get-hsn-for-part-item-store', [DeliveryChalanController::class, 'getHsnForPartItemInDelivery'])->name('get-hsn-for-part-item-store');

        // Returnable Chalan
        Route::get('/list-returnable-chalan', [ReturnableChalanController::class, 'index'])->name('list-returnable-chalan');
        Route::post('/add-returnable-chalan', [ReturnableChalanController::class, 'create'])->name('add-returnable-chalan');
        Route::post('/store-returnable-chalan', [ReturnableChalanController::class, 'store'])->name('store-returnable-chalan');
        Route::get('/show-returnable-chalan/{id}', [ReturnableChalanController::class, 'show'])->name('show-returnable-chalan');
        Route::get('/edit-returnable-chalan/{id}', [ReturnableChalanController::class, 'edit'])->name('edit-returnable-chalan');
        Route::any('/update-returnable-chalan', [ReturnableChalanController::class, 'update'])->name('update-returnable-chalan');
        Route::any('/delete-returnable-chalan/{id}', [ReturnableChalanController::class, 'destroy'])->name('delete-returnable-chalan');
        Route::post('/delete-addmore-returnable', [ReturnableChalanController::class, 'destroyAddmore'])->name('delete-addmore-returnable');
        Route::get('/fetch-po-numbers', [ReturnableChalanController::class, 'fetchPONumbers'])->name('fetch-po-numbers');
        Route::get('/get-po-numbers/{vendor_id}', [ReturnableChalanController::class, 'getPONumbers'])->name('get-po-numbers');

        // Master modules
        Route::any('/list-unit', [UnitController::class, 'index'])->name('list-unit');
        Route::any('/add-unit', [UnitController::class, 'add'])->name('add-unit');
        Route::any('/store-unit', [UnitController::class, 'store'])->name('store-unit');
        Route::any('/edit-unit/{id}', [UnitController::class, 'edit'])->name('edit-unit');
        Route::any('/update-unit', [UnitController::class, 'update'])->name('update-unit');
        Route::any('/delete-unit/{id}', [UnitController::class, 'destroy'])->name('delete-unit');

        Route::any('/list-hsn', [HSNController::class, 'index'])->name('list-hsn');
        Route::any('/add-hsn', [HSNController::class, 'add'])->name('add-hsn');
        Route::any('/store-hsn', [HSNController::class, 'store'])->name('store-hsn');
        Route::any('/edit-hsn/{id}', [HSNController::class, 'edit'])->name('edit-hsn');
        Route::any('/update-hsn', [HSNController::class, 'update'])->name('update-hsn');
        Route::any('/delete-hsn/{id}', [HSNController::class, 'destroy'])->name('delete-hsn');

        Route::any('/list-group', [GroupController::class, 'index'])->name('list-group');
        Route::any('/add-group', [GroupController::class, 'add'])->name('add-group');
        Route::any('/store-group', [GroupController::class, 'store'])->name('store-group');
        Route::any('/edit-group/{id}', [GroupController::class, 'edit'])->name('edit-group');
        Route::any('/update-group', [GroupController::class, 'update'])->name('update-group');
        Route::any('/delete-group/{id}', [GroupController::class, 'destroy'])->name('delete-group');

        Route::any('/list-rack', [RackController::class, 'index'])->name('list-rack');
        Route::any('/add-rack', [RackController::class, 'add'])->name('add-rack');
        Route::any('/store-rack', [RackController::class, 'store'])->name('store-rack');
        Route::any('/edit-rack/{id}', [RackController::class, 'edit'])->name('edit-rack');
        Route::any('/update-rack', [RackController::class, 'update'])->name('update-rack');
        Route::any('/delete-rack/{id}', [RackController::class, 'destroy'])->name('delete-rack');

        Route::any('/list-process', [ProcessController::class, 'index'])->name('list-process');
        Route::any('/add-process', [ProcessController::class, 'add'])->name('add-process');
        Route::any('/store-process', [ProcessController::class, 'store'])->name('store-process');
        Route::any('/edit-process/{id}', [ProcessController::class, 'edit'])->name('edit-process');
        Route::any('/update-process', [ProcessController::class, 'update'])->name('update-process');
        Route::any('/delete-process/{id}', [ProcessController::class, 'destroy'])->name('delete-process');

        Route::any('/list-accessories', [AccessoriesController::class, 'index'])->name('list-accessories');
        Route::any('/add-accessories', [AccessoriesController::class, 'add'])->name('add-accessories');
        Route::any('/store-accessories', [AccessoriesController::class, 'store'])->name('store-accessories');
        Route::any('/edit-accessories/{id}', [AccessoriesController::class, 'edit'])->name('edit-accessories');
        Route::any('/update-accessories', [AccessoriesController::class, 'update'])->name('update-accessories');
        Route::any('/delete-accessories/{id}', [AccessoriesController::class, 'destroy'])->name('delete-accessories');

        // Inventory
        Route::any('/list-inventory-material', [InventoryController::class, 'getMaterialList'])->name('list-inventory-material');
        Route::any('/add-product-stock', [InventoryController::class, 'add'])->name('add-product-stock');
        Route::any('/store-product-stock', [InventoryController::class, 'store'])->name('store-product-stock');
        Route::any('/edit-product-stock/{id}', [InventoryController::class, 'edit'])->name('edit-product-stock');
        Route::any('/update-product-stock', [InventoryController::class, 'update'])->name('update-product-stock');

        Route::get('/list-grn-details/{purchase_orders_id}/{business_details_id}/{id}', [StoreAllListController::class, 'getGRNDetails'])->name('list-grn-details');
        Route::get(
            '/list-grn-details-po-tracking/{purchase_orders_id}/{business_details_id}/{id}',
            [StoreAllListController::class, 'getGRNDetailsPOTracking']
        )->name('list-grn-details-po-tracking');
    });


    Route::group(['prefix' => 'purchase', 'middleware' => 'admin'], function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('purchase.dashboard');

        // Reports
        Route::get('/list-purchase', [PurchaseAllListController::class, 'getAllListMaterialReceivedForPurchase'])->name('list-purchase');
        Route::get('/purchase-report', [PurchaseAllListController::class, 'getPurchaseReport'])->name('purchase-report');
        Route::get('/ajax', [PurchaseAllListController::class, 'getPurchaseReportAjax'])->name('ajax');
        Route::get('/party-report', [PurchaseAllListController::class, 'getPurchasePartyReport'])->name('party-report');
        Route::get('/party-ajax', [PurchaseAllListController::class, 'getPurchasePartyReportAjax'])->name('party-ajax');
        Route::get('/follow-up-report', [PurchaseAllListController::class, 'FollowUpReport'])->name('follow-up-report');
        Route::get('/follow-up-report-ajax', [PurchaseAllListController::class, 'FollowUpReportAjax'])->name('follow-up-report-ajax');

        // Purchase Orders

        Route::get('/list-purchase-order/{requistition_id}/{business_details_id}', [PurchaseOrderController::class, 'index'])->name('list-purchase-order');
        Route::get('/list-purchase-order-rejected', [PurchaseOrderController::class, 'rejectedPurchaseOrder'])->name('list-purchase-order-rejected');

        Route::post('/add-purchase-order', [PurchaseOrderController::class, 'create'])->name('add-purchase-order');
        Route::post('/store-purchase-order', [PurchaseOrderController::class, 'store'])->name('store-purchase-order');
        Route::get('/show-purchase-order/{id}', [PurchaseOrderController::class, 'show'])->name('show-purchase-order');
        Route::get('/edit-purchase-order/{id}', [PurchaseOrderController::class, 'edit'])->name('edit-purchase-order');
        Route::post('/update-purchase-order', [PurchaseOrderController::class, 'update'])->name('update-purchase-order');
        Route::delete('/delete-purchase-order/{id}', [PurchaseOrderController::class, 'destroy'])->name('delete-purchase-order');
        Route::post('/delete-addmore', [PurchaseOrderController::class, 'destroyAddmore'])->name('delete-addmore');

        Route::get('/get-hsn-for-part', [PurchaseOrderController::class, 'getHsnForPart'])->name('get-hsn-for-part');
        Route::get('/get-tax-value', [PurchaseOrderController::class, 'getTaxValue'])
            ->name('get-tax-value');
        // Vendor CRUD

        Route::get('/list-vendor', [VendorController::class, 'index'])->name('list-vendor');
        Route::get('/add-vendor', [VendorController::class, 'add'])->name('add-vendor');
        Route::post('/store-vendor', [VendorController::class, 'store'])->name('store-vendor');
        Route::get('/edit-vendor/{id}', [VendorController::class, 'edit'])->name('edit-vendor');
        Route::post('/update-vendor', [VendorController::class, 'update'])->name('update-vendor');
        Route::any('/delete-vendor/{id}', [VendorController::class, 'destroy'])->name('delete-vendor');

        // Approved Purchase Orders
        Route::get('/list-approved-purchase-orders', [PurchaseAllListController::class, 'getAllListApprovedPurchaseOrder'])->name('list-approved-purchase-orders');
        Route::get('/check-details-of-po-before-send-vendor/{purchase_order_id}', [PurchaseOrderController::class, 'checkDetailsBeforeSendPOToVendor'])->name('check-details-of-po-before-send-vendor');
        Route::get('/list-check-final-purchase-order/{purchase_order_id}', [PurchaseOrderController::class, 'listAllApprovedPOToBeChecked'])->name('list-check-final-purchase-order');
        Route::get('/finalize-and-submit-mail-to-vendor/{purchase_order_id}/{business_id}', [PurchaseOrderController::class, 'submitAndSentEmailToTheVendorFinalPurchaseOrder'])->name('finalize-and-submit-mail-to-vendor');
        Route::post('/submit-purchase-order-to-owner-for-review', [PurchaseOrderController::class, 'submitPurchaseOrderToOwnerForReview'])->name('submit-purchase-order-to-owner-for-review');

        Route::get('/list-purchase-order-approved-sent-to-vendor', [PurchaseAllListController::class, 'getAllListPurchaseOrderMailSentToVendor'])->name('list-purchase-order-approved-sent-to-vendor');
        Route::get('/list-purchase-order-approved-sent-to-vendor-businesswise/{id}', [PurchaseAllListController::class, 'getAllListPurchaseOrderMailSentToVendorBusinessWise'])->name('list-purchase-order-approved-sent-to-vendor-businesswise');
        Route::get('/list-purchase-orders-sent-to-owner', [PurchaseAllListController::class, 'getAllListPurchaseOrderTowardsOwner'])->name('list-purchase-orders-sent-to-owner');
        Route::get('/list-purchase-orders-sent-to-owner-details/{purchase_order_id}', [PurchaseOrderController::class, 'getAllListPurchaseOrderTowardsOwnerDetails'])->name('list-purchase-orders-sent-to-owner-details');
        Route::get('/list-purchase-order-sent-to-owner-for-approval-busineswise/{purchase_order_id}', [PurchaseAllListController::class, 'getPurchaseOrderSentToOwnerForApprovalBusinesWise'])->name('list-purchase-order-sent-to-owner-for-approval-busineswise');

        // Submitted POs by Vendor
        Route::get('/list-submited-po-to-vendor', [PurchaseAllListController::class, 'getAllListSubmitedPurchaeOrderByVendor'])->name('list-submited-po-to-vendor');
        Route::get('/list-submited-po-to-vendor-businesswise/{id}', [PurchaseAllListController::class, 'getAllListSubmitedPurchaeOrderByVendorBusinessWise'])->name('list-submited-po-to-vendor-businesswise');

        // Tax CRUD
        Route::any('/list-tax', [TaxController::class, 'index'])->name('list-tax');
        Route::any('/add-tax', [TaxController::class, 'add'])->name('add-tax');
        Route::any('/store-tax', [TaxController::class, 'store'])->name('store-tax');
        Route::any('/edit-tax/{id}', [TaxController::class, 'edit'])->name('edit-tax');
        Route::any('/update-tax', [TaxController::class, 'update'])->name('update-tax');
        Route::any('/delete-tax/{id}', [TaxController::class, 'destroy'])->name('delete-tax');

        // Vendor Type CRUD
        Route::any('/list-vendor-type', [VendorTypeController::class, 'index'])->name('list-vendor-type');
        Route::any('/add-vendor-type', [VendorTypeController::class, 'add'])->name('add-vendor-type');
        Route::any('/store-vendor-type', [VendorTypeController::class, 'store'])->name('store-vendor-type');
        Route::any('/edit-vendor-type/{id}', [VendorTypeController::class, 'edit'])->name('edit-vendor-type');
        Route::any('/update-vendor-type', [VendorTypeController::class, 'update'])->name('update-vendor-type');
        Route::any('/delete-vendor-type/{id}', [VendorTypeController::class, 'destroy'])->name('delete-vendor-type');

        // Part Item CRUD
        Route::resource('part-item', ItemController::class)->except(['create', 'show']);


        Route::any('/list-part-item', [ItemController::class, 'index'])->name('list-part-item');
        Route::any('/add-part-item', [ItemController::class, 'add'])->name('add-part-item');
        Route::any('/store-part-item', [ItemController::class, 'store'])->name('store-part-item');
        Route::any('/edit-part-item/{id}', [ItemController::class, 'edit'])->name('edit-part-item');
        Route::any('/update-part-item', [ItemController::class, 'update'])->name('update-part-item');
        Route::any('/delete-part-item/{id}', [ItemController::class, 'destroy'])->name('delete-part-item');
    });

    // ================= SECURITY DEPT =================
    Route::group(['prefix' => 'securitydept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('security.dashboard');
        Route::get('/search-by-po-no', [GatepassController::class, 'searchByPONo'])->name('search-by-po-no');
        Route::get('/list-gatepass', [GatepassController::class, 'index'])->name('list-gatepass');
        Route::get('/list-po-details/{id}/{purchase_order_id}', [GatepassController::class, 'getPurchaseDetails'])->name('list-po-details');
        Route::get('/list-po-details-after-gatepass/{id}/{purchase_order_id}', [GatepassController::class, 'getPurchaseDetailsAfterGatepass'])->name('list-po-details-after-gatepass');
        Route::get('/add-gatepass', [GatepassController::class, 'add'])->name('add-gatepass');
        Route::get('/edit-gatepass/{id}', [GatepassController::class, 'edit'])->name('edit-gatepass');
        Route::post('/update-gatepass', [GatepassController::class, 'update'])->name('update-gatepass');
        Route::get('/add-gatepass-with-po/{id}', [GatepassController::class, 'addGatePassWithPO'])->name('add-gatepass-with-po');
        Route::post('/store-gatepass', [GatepassController::class, 'store'])->name('store-gatepass');
        Route::post('/list-all-po-number', [SecurityAllListController::class, 'getAllListMaterialRecieved'])->name('list-all-po-number');
        Route::get('/security-report', [ReportController::class, 'getSecurityReport'])->name('security-report');
        Route::get('/security-report-ajax', [ReportController::class, 'getSecurityReportAjax'])->name('security-report-ajax');
    });

    // ================= QUALITY DEPT =================
    Route::group(['prefix' => 'quality', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('quality.dashboard');
        Route::get('/list-grn', [GRNController::class, 'index'])->name('list-grn');
        Route::get('/add-grn/{purchase_orders_id}/{id}', [GRNController::class, 'add'])->name('add-grn');
        Route::post('/store-grn', [GRNController::class, 'store'])->name('store-grn');
        Route::get('/get-balance-quantity', [GRNController::class, 'getBalanceQuantity'])->name('get-balance-quantity');
        Route::get('/edit-grn', [GRNController::class, 'edit'])->name('edit-grn');
        Route::get('/list-material-sent-to-quality', [GRNController::class, 'getAllListMaterialSentFromQuality'])->name('list-material-sent-to-quality');
        Route::get('/list-material-sent-to-quality-businesswise/{id}', [GRNController::class, 'getAllListMaterialSentFromQualityBusinessWise'])->name('list-material-sent-to-quality-businesswise');
        Route::get('/list-rejected-chalan-po-wise', [GRNController::class, 'getAllListMaterialSentFromQuality'])->name('list-rejected-chalan-po-wise');
        Route::get('/grn-report', [ReportController::class, 'getGRNReport'])->name('grn-report');
        Route::get('/grn-report-ajax', [ReportController::class, 'getGRNReportAjax'])->name('grn-report-ajax');

        Route::get('/rejected-grn-report', [ReportController::class, 'getRejectedGRNReport'])->name('rejected-grn-report');
        Route::get('/rejected-grn-report-ajax', [ReportController::class, 'getRejectedGRNReportAjax'])->name('rejected-grn-report-ajax');

        Route::get('/get-vendor-by-purchase_order/{id}', [ReportController::class, 'getVendorbyPurchaseOrder'])
            ->name('get-vendor-by-purchase_order');
    });

    // ================= FINANCE DEPT =================
    Route::group(['prefix' => 'financedept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('finance.dashboard');
        Route::get('/forward-the-purchase-order-to-the-owner-for-sanction/{purchase_orders_id}/{business_id}', [FinanceController::class, 'forwardPurchaseOrderToTheOwnerForSanction'])->name('forward-the-purchase-order-to-the-owner-for-sanction');
        Route::get('/list-accepted-grn-srn-finance/{purchase_orders_id}', [FinanceAllListController::class, 'listAcceptedGrnSrnFinance'])->name('list-accepted-grn-srn-finance');
        Route::get('/list-sr-and-gr-genrated-business', [FinanceAllListController::class, 'getAllListSRAndGRNGeanrated'])->name('list-sr-and-gr-genrated-business');
        Route::get('/list-sr-and-gr-genrated-business-wise/{id}', [FinanceAllListController::class, 'getAllListSRAndGRNGeanratedBusinessWise'])->name('list-sr-and-gr-genrated-business-wise');
        Route::get('/list-po-sent-for-approval', [FinanceAllListController::class, 'listPOSentForApprovaTowardsOwner'])->name('list-po-sent-for-approval');
        Route::get('/list-po-sanction-and-need-to-do-payment-to-vendor', [FinanceAllListController::class, 'listPOSanctionAndNeedToDoPaymentToVendor'])->name('list-po-sanction-and-need-to-do-payment-to-vendor');
        Route::get('/send-payment-to-vendor/{purchase_orders_id}/{business_id}', [FinanceController::class, 'forwardedPurchaseOrderPaymentToTheVendor'])->name('send-payment-to-vendor');
        Route::get('/recive-logistics-list', [FinanceAllListController::class, 'getAllListBusinessReceivedFromLogistics'])->name('recive-logistics-list');
        Route::get('/send-to-dispatch/{id}/{business_details_id}', [FinanceController::class, 'sendToDispatch'])->name('send-to-dispatch');
        Route::get('/list-send-to-dispatch', [FinanceAllListController::class, 'getAllListBusinessFianaceSendToDispatch'])->name('list-send-to-dispatch');

        Route::get('/list-fianance-report', [ReportController::class, 'listFinanceReport'])->name('list-fianance-report');
        Route::get('/finance-ajax', [ReportController::class, 'listFinanceReportAjax'])->name('finance-ajax');
    });

    // ================= LOGISTICS DEPT =================
    Route::group(['prefix' => 'logisticsdept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('logistics.dashboard');
        Route::get('/list-final-production-completed-recive-to-logistics', [LogisticsAllListController::class, 'getAllCompletedProduction'])->name('list-final-production-completed-recive-to-logistics');
        Route::get('/add-logistics/{business_id}', [LogisticsController::class, 'addLogistics'])->name('add-logistics');
        Route::post('/store-logistics', [LogisticsController::class, 'storeLogistics'])->name('store-logistics');
        Route::get('/list-logistics', [LogisticsAllListController::class, 'getAllLogistics'])->name('list-logistics');
        Route::get('/send-to-fianance/{id}/{business_details_id}', [LogisticsController::class, 'sendToFianance'])->name('send-to-fianance');
        Route::get('/list-send-to-fianance-by-logistics', [LogisticsAllListController::class, 'getAllListSendToFiananceByLogistics'])->name('list-send-to-fianance-by-logistics');

        // Vehicle Type
        Route::any('/list-vehicle-type', [VehicleTypeController::class, 'index'])->name('list-vehicle-type');
        Route::any('/add-vehicle-type', [VehicleTypeController::class, 'add'])->name('add-vehicle-type');
        Route::any('/store-vehicle-type', [VehicleTypeController::class, 'store'])->name('store-vehicle-type');
        Route::any('/edit-vehicle-type/{id}', [VehicleTypeController::class, 'edit'])->name('edit-vehicle-type');
        Route::any('/update-vehicle-type', [VehicleTypeController::class, 'update'])->name('update-vehicle-type');
        Route::any('/delete-vehicle-type/{id}', [VehicleTypeController::class, 'destroy'])->name('delete-vehicle-type');

        // Transport Name
        Route::any('/list-transport-name', [NameOfTransportController::class, 'index'])->name('list-transport-name');
        Route::any('/add-transport-name', [NameOfTransportController::class, 'add'])->name('add-transport-name');
        Route::any('/store-transport-name', [NameOfTransportController::class, 'store'])->name('store-transport-name');
        Route::any('/edit-transport-name/{id}', [NameOfTransportController::class, 'edit'])->name('edit-transport-name');
        Route::any('/update-transport-name', [NameOfTransportController::class, 'update'])->name('update-transport-name');
        Route::any('/delete-transport-name/{id}', [NameOfTransportController::class, 'destroy'])->name('delete-transport-name');
    });

    // ================= DISPATCH DEPT =================
    Route::group(['prefix' => 'dispatchdept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dispatch.dashboard');
        Route::get('/list-final-production-completed-received-from-fianance', [DispatchAllListController::class, 'getAllReceivedFromFianance'])->name('list-final-production-completed-received-from-fianance');
        Route::get('/add-dispatch/{business_id}/{business_details_id}', [DispatchController::class, 'addDispatch'])->name('add-dispatch');
        Route::post('/store-dispatch', [DispatchController::class, 'storeDispatch'])->name('store-dispatch');
        Route::get('/list-dispatch', [DispatchAllListController::class, 'getAllDispatch'])->name('list-dispatch');
        Route::get('/list-dispatch-final-product-close', [DispatchAllListController::class, 'getAllDispatchClosedProduct'])->name('list-dispatch-final-product-close');
    });

    // ================= HR MODULE =================
    Route::group(['prefix' => 'hr', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users

        Route::get('/list-employee', [EmployeesHrController::class, 'index'])->name('list-employee');
        Route::get('/add-employee', [EmployeesHrController::class, 'addUsers'])->name('add-employee');
        Route::post('/add-employee', [EmployeesHrController::class, 'register'])->name('store-employee');
        Route::get('/edit-employee/{id}', [EmployeesHrController::class, 'editUsers'])->name('edit-employee');
        Route::post('/update-employee', [EmployeesHrController::class, 'update'])->name('update-employee');
        Route::any('/delete-employee/{id}', [EmployeesHrController::class, 'destroy'])->name('delete-employee');
        Route::any('/show-employee/{id}', [EmployeesHrController::class, 'show'])->name('show-employee');
        Route::any('/users-leaves-details/{id}', [EmployeesHrController::class, 'usersLeavesDetails'])->name('users-leaves-details');

        Route::get('/cities', [EmployeesHrController::class, 'getCities'])->name('cities');
        Route::get('/states', [EmployeesHrController::class, 'getState'])->name('states');

        // Yearly Leave Management
        Route::get('/list-yearly-leave-management', [LeaveManagmentController::class, 'index'])->name('list-yearly-leave-management');
        Route::get('/add-yearly-leave-management', [LeaveManagmentController::class, 'add'])->name('add-yearly-leave-management');
        Route::post('/store-yearly-leave-management', [LeaveManagmentController::class, 'store'])->name('store-yearly-leave-management');
        Route::get('/edit-yearly-leave-management/{id}', [LeaveManagmentController::class, 'edit'])->name('edit-yearly-leave-management');
        Route::post('/update-yearly-leave-management', [LeaveManagmentController::class, 'update'])->name('update-yearly-leave-management');
        Route::any('/delete-yearly-leave-management/{id}', [LeaveManagmentController::class, 'destroy'])->name('delete-yearly-leave-management');

        // Leaves
        Route::get('/list-leaves', [LeavesController::class, 'index'])->name('list-leaves');
        Route::get('/add-leaves', [LeavesController::class, 'add'])->name('add-leaves');
        Route::post('/store-leaves', [LeavesController::class, 'store'])->name('store-leaves');
        Route::get('/edit-leaves/{id}', [LeavesController::class, 'edit'])->name('edit-leaves');
        Route::post('/update-leaves', [LeavesController::class, 'update'])->name('update-leaves');
        // Route::any('/delete-leaves/{id}', [LeavesController::class, 'destroy'])->name('delete-leaves');
        Route::delete('/delete-leaves/{id}', [LeavesController::class, 'destroy'])->name('delete-leaves');

        Route::post('/check-leave-balance', [LeavesController::class, 'checkLeaveBalance'])
            ->name('check-leave-balance');


        Route::get('/list-leaves-acceptedby-hr', [LeavesController::class, 'getAllLeavesRequest'])->name('list-leaves-acceptedby-hr');
        Route::any('/show-leaves/{id}', [LeavesController::class, 'show'])->name('show-leaves');
        Route::get('/list-leaves-not-approvedby-hr', [LeavesController::class, 'getAllNotApprovedRequest'])->name('list-leaves-not-approvedby-hr');

        Route::get('/list-leaves-approvedby-hr', [LeavesController::class, 'getAllApprovedRequest'])->name('list-leaves-approvedby-hr');
        Route::post('/check-dates', [LeavesController::class, 'checkDates'])->name('check-dates');
        Route::post('/update-status', [LeavesController::class, 'updateLabourStatus'])->name('update-status');
        Route::post('/update-status-rejected', [LeavesController::class, 'updateLabourStatusRejected'])->name('update-status-rejected');

        // Notices
        Route::get('/list-notice', [NoticeController::class, 'index'])->name('list-notice');
        Route::get('/add-notice', [NoticeController::class, 'add'])->name('add-notice');
        Route::post('/add-notice', [NoticeController::class, 'store'])->name('store-notice');
        Route::get('/edit-notice/{id}', [NoticeController::class, 'edit'])->name('edit-notice');
        Route::post('/update-notice', [NoticeController::class, 'update'])->name('update-notice');
        Route::post('/show-notice', [NoticeController::class, 'show'])->name('show-notice');
        Route::any('/delete-notice/{id}', [NoticeController::class, 'destroy'])->name('delete-notice');
        Route::get('/particular-notice-department-wise', [NoticeController::class, 'departmentWiseNotice'])
            ->name('particular-notice-department-wise');
    });

    Route::group(['prefix' => 'cms', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('cms.dashboard');

        // Vision Mission
        Route::get('/list-vision-mission', [VisionMissionController::class, 'index'])->name('list-vision-mission');
        Route::get('/add-vision-mission', [VisionMissionController::class, 'add'])->name('add-vision-mission');
        Route::post('/add-vision-mission', [VisionMissionController::class, 'store'])->name('store-vision-mission');
        Route::get('/edit-vision-mission/{edit_id}', [VisionMissionController::class, 'edit'])->name('edit-vision-mission');
        Route::post('/update-vision-mission', [VisionMissionController::class, 'update'])->name('update-vision-mission');
        Route::post('/show-vision-mission', [VisionMissionController::class, 'show'])->name('show-vision-mission');

        // Services
        Route::get('/list-services', [ServicesController::class, 'index'])->name('list-services');
        Route::get('/add-services', [ServicesController::class, 'add'])->name('add-services');
        Route::post('/add-services', [ServicesController::class, 'store'])->name('store-services');
        Route::get('/edit-services/{edit_id}', [ServicesController::class, 'edit'])->name('edit-services');
        Route::post('/update-services', [ServicesController::class, 'update'])->name('update-services');
        Route::post('/show-services', [ServicesController::class, 'show'])->name('show-services');
        Route::any('/delete-services/{id}', [ServicesController::class, 'destroy'])->name('delete-services');
        Route::post('/update-active-services', [ServicesController::class, 'updateOne'])->name('update-active-services');

        // Testimonial
        Route::get('/list-testimonial', [TestimonialController::class, 'index'])->name('list-testimonial');
        Route::get('/add-testimonial', [TestimonialController::class, 'add'])->name('add-testimonial');
        Route::post('/add-testimonial', [TestimonialController::class, 'store'])->name('store-testimonial');
        Route::get('/edit-testimonial/{edit_id}', [TestimonialController::class, 'edit'])->name('edit-testimonial');
        Route::post('/update-testimonial', [TestimonialController::class, 'update'])->name('update-testimonial');
        Route::post('/show-testimonial', [TestimonialController::class, 'show'])->name('show-testimonial');
        Route::any('/delete-testimonial/{id}', [TestimonialController::class, 'destroy'])->name('delete-testimonial');
        Route::post('/update-active-testimonial', [TestimonialController::class, 'updateOne'])->name('update-active-testimonial');

        // Products
        Route::get('/list-product', [ProductController::class, 'index'])->name('list-product');
        Route::get('/add-product', [ProductController::class, 'add'])->name('add-product');
        Route::post('/add-product', [ProductController::class, 'store'])->name('store-product');
        Route::get('/edit-product/{edit_id}', [ProductController::class, 'edit'])->name('edit-product');
        Route::post('/update-product', [ProductController::class, 'update'])->name('update-product');
        Route::post('/show-product', [ProductController::class, 'showProduct'])->name('show-product');
        Route::any('/delete-product/{id}', [ProductController::class, 'destroy'])->name('delete-product');
        Route::post('/update-active-product', [ProductController::class, 'updateOne'])->name('update-active-product');

        // Director Desk
        Route::get('/list-director-desk', [DirectorDeskController::class, 'index'])->name('list-director-desk');
        Route::get('/add-director-desk', [DirectorDeskController::class, 'add'])->name('add-director-desk');
        Route::post('/add-director-desk', [DirectorDeskController::class, 'store'])->name('store-director-desk');
        Route::get('/edit-director-desk/{edit_id}', [DirectorDeskController::class, 'edit'])->name('edit-director-desk');
        Route::post('/update-director-desk', [DirectorDeskController::class, 'update'])->name('update-director-desk');
        Route::post('/show-director-desk', [DirectorDeskController::class, 'show'])->name('show-director-desk');
        Route::post('/delete-director-desk', [DirectorDeskController::class, 'destroy'])->name('delete-director-desk');
        Route::post('/update-active-director-desk', [DirectorDeskController::class, 'updateOne'])->name('update-active-director-desk');

        // Team
        Route::get('/list-team', [TeamController::class, 'index'])->name('list-team');
        Route::get('/add-team', [TeamController::class, 'add'])->name('add-team');
        Route::post('/add-team', [TeamController::class, 'store'])->name('store-team');
        Route::get('/edit-team/{edit_id}', [TeamController::class, 'edit'])->name('edit-team');
        Route::post('/update-team', [TeamController::class, 'update'])->name('update-team');
        Route::post('/show-team', [TeamController::class, 'show'])->name('show-team');
        Route::any('/delete-team/{id}', [TeamController::class, 'destroy'])->name('delete-team');
        Route::post('/update-active-team', [TeamController::class, 'updateOne'])->name('update-active-team');

        // Contact Us
        Route::get('/list-contactus-form', [ContactUsListController::class, 'index'])->name('list-contactus-form');
        Route::post('/show-contactus-form', [ContactUsListController::class, 'show'])->name('show-contactus-form');
        Route::any('/delete-contactus-form/{id}', [ContactUsListController::class, 'destroy'])->name('delete-contactus-form');
    });

    // ================= INVENTORY MODULE =================
    Route::group(['prefix' => 'inventory', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('inventory.dashboard');
    });

    // ================= EMPLOYEE MODULE =================
    Route::group(['prefix' => 'employee', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('employee.dashboard');
        Route::any('/show-employee-profile/{id}', [EmployeesHrController::class, 'showParticularDetails'])->name('employee.show-employee-profile');
    });

    // ======================== Frontend Website ======================== //
    Route::get('/', [PagesController::class, 'index'])->name('index');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/services', [ProductServicesController::class, 'getAllServices'])->name('services');
    Route::get('/product', [ProductServicesController::class, 'index'])->name('product');
    Route::get('/contactus', [ContactUsController::class, 'getContactUs'])->name('contactus');
    Route::post('/add-contactus', [ContactUsController::class, 'addContactUs'])->name('add-contactus');
    Route::post('/product-details', [ProductServicesController::class, 'showParticularPrdouct'])->name('product-details');

    // ======================== Production ======================== //
    Route::prefix('production')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('production.dashboard');

        Route::get('/list-products', [ProductionController::class, 'index'])->name('list-products');
        Route::get('/add-products', [ProductionController::class, 'add'])->name('add-products');
        Route::post('/store-products', [ProductionController::class, 'store'])->name('store-products');
        Route::get('/edit-products/{id}', [ProductionController::class, 'edit'])->name('edit-products');
        Route::post('/update-products', [ProductionController::class, 'update'])->name('update-products');
        Route::delete('/delete-products/{id}', [ProductionController::class, 'destroy'])->name('delete-products');
    });

    // ======================== Doc Upload Finance ======================== //
    Route::prefix('finance')->group(function () {
        Route::get('/list-doc-upload-fianace', [DocUploadFianaceController::class, 'index'])->name('list-doc-upload-fianace');
        Route::get('/add-doc-upload-fianace', [DocUploadFianaceController::class, 'add'])->name('add-doc-upload-fianace');
        Route::get('/edit-doc-upload-fianace', [DocUploadFianaceController::class, 'edit'])->name('edit-doc-upload-fianace');
    });

    // ======================== Security Remark ======================== //
    Route::prefix('security')->group(function () {
        Route::get('/list-security-remark', [SecurityRemarkController::class, 'index'])->name('list-security-remark');
        Route::get('/add-security-remark', [SecurityRemarkController::class, 'add'])->name('add-security-remark');
        Route::get('/edit-security-remark', [SecurityRemarkController::class, 'edit'])->name('edit-security-remark');
    });

    // ======================== Store Receipt ======================== //
    Route::prefix('store')->group(function () {
        Route::get('/list-store-receipt', [StoreReceiptController::class, 'index'])->name('list-store-receipt');
        Route::get('/add-store-receipt', [StoreReceiptController::class, 'add'])->name('add-store-receipt');
        Route::get('/edit-store-receipt', [StoreReceiptController::class, 'edit'])->name('edit-store-receipt');
    });

// ======================== Vendor (Future) ======================== //
// Add vendor-related routes here
