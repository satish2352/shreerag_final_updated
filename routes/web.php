<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', function () {
    return view('admin.login');
});


Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('clear-compiled');
    return 'Cache cleared'; // You can return any response you want here
});

Route::get('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@index']);
Route::post('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@submitLogin']);
Route::get('/register', ['as' => 'register', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@index']);

Route::group(['middleware' => ['admin']], function () {   
     Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    Route::get('/get-notification', ['as' => 'get-notification', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@getNotification']);
    Route::get('/get-offcanvas-data', ['as' => 'get-offcanvas-data', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@getOffcanvas']);

    // Route::get('/forms', ['as' => 'forms', 'uses' => 'App\Http\Controllers\Admin\Forms\FormsController@index']);
    Route::get('/admin-log-out', ['as' => 'log-out', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@logout']);

    Route::get('/list-organizations', ['as' => 'list-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@index']);
    Route::get('/add-organizations', ['as' => 'add-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@add']);
    Route::post('/store-organizations', ['as' => 'store-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@store']);
    Route::get('/edit-organizations/{id}', ['as' => 'edit-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@edit']);
    Route::post('/update-organizations', ['as' => 'update-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@update']);
    Route::any('/delete-organizations/{id}', ['as' => 'delete-organizations', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@destroy']);
    Route::get('/organization-details/{id}', ['as' => 'organization-details', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@details']);
    Route::get('/filter-employees/{id}', ['as' => 'filter-employees', 'uses' => 'App\Http\Controllers\Admin\Organization\OrganizationController@filterEmployees']);


    Route::get('/list-employees', ['as' => 'list-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@index']);
    Route::get('/add-employees', ['as' => 'add-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@add']);
    Route::post('/store-employees', ['as' => 'store-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@store']);
    Route::get('/edit-employees/{emp_id}', ['as' => 'edit-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@edit']);
    Route::post('/update-employees', ['as' => 'update-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@update']);
    Route::any('/delete-employees/{emp_id}', ['as' => 'delete-employees', 'uses' => 'App\Http\Controllers\Admin\Employees\EmployeesController@destroy']);

    Route::any('/list-departments', ['as' => 'list-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@index']);
    Route::any('/add-departments', ['as' => 'add-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@add']);
    Route::any('/store-departments', ['as' => 'store-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@store']);
    Route::any('/edit-departments/{id}', ['as' => 'edit-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@edit']);
    Route::any('/update-departments', ['as' => 'update-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@update']);
    Route::any('/delete-departments/{id}', ['as' => 'delete-departments', 'uses' => 'App\Http\Controllers\Admin\Departments\DepartmentController@destroy']);

    Route::any('/list-roles', ['as' => 'list-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@index']);
    Route::any('/add-roles', ['as' => 'add-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@add']);
    Route::any('/store-roles', ['as' => 'store-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@store']);
    Route::any('/edit-roles/{id}', ['as' => 'edit-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@edit']);
    Route::any('/update-roles', ['as' => 'update-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@update']);
    Route::any('/delete-roles/{id}', ['as' => 'delete-roles', 'uses' => 'App\Http\Controllers\Admin\Roles\RolesController@destroy']);


    Route::get('/list-rules-regulations', ['as' => 'list-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@index']);
    Route::get('/add-rules-regulations', ['as' => 'add-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@add']);
    Route::post('/add-rules-regulations', ['as' => 'add-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@store']);
    Route::get('/edit-rules-regulations/{edit_id}', ['as' => 'edit-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@edit']);
    Route::post('/update-rules-regulations', ['as' => 'update-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@update']);
    Route::post('/show-rules-regulations', ['as' => 'show-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@show']);
    Route::any('/delete-rules-regulations/{id}', ['as' => 'delete-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@destroy']);
    Route::post('/update-active-rules-regulations', ['as' => 'update-active-rules-regulations', 'uses' => 'App\Http\Controllers\Admin\RulesAndRegulations\RulesAndRegulationsController@updateOne']);
});
    
    // Organization admin
    // Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);

    // Route::get('/admin-dashboard', ['as' => '/admin-dashboard', 'uses' => 'App\Http\Controllers\Organizations\Dashboard\DashboardController@index']);
    // Route::get('/organizations-list-employees', ['as' => 'organizations-list-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@index']);
    Route::get('/organizations-add-employees', ['as' => 'organizations-add-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@add']);
    Route::post('/organizations-store-employees', ['as' => 'organizations-store-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@store']);
    Route::get('/organizations-edit-employees/{id}', ['as' => 'organizations-edit-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@edit']);
    Route::post('/organizations-update-employees', ['as' => 'organizations-update-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@update']);
    Route::any('/organizations-delete-employees/{id}', ['as' => 'organizations-delete-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@destroy']);
    Route::post('/check-email-availability', ['as' => 'check-email-availability', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@checkEmailAvailability']);

    Route::group(['prefix' => 'owner', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/get-offcanvas', ['as' => 'get-offcanvas', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@getOffcanvas']);
        
        Route::get('/organizations-list-employees', ['as' => 'organizations-list-employees', 'uses' => 'App\Http\Controllers\Organizations\Employees\EmployeesController@index']);
    
        Route::get('/list-business', ['as' => 'list-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@index']);
        Route::get('/add-business', ['as' => 'add-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@add']);
        Route::post('/store-business', ['as' => 'store-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@store']);
        Route::get('/edit-business/{id}', ['as' => 'edit-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@edit']);
        Route::post('/update-business', ['as' => 'update-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@update']);
        Route::any('/delete-business/{id}', ['as' => 'delete-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@destroy']);
        Route::get('/list-submit-final-purchase-order/{id}', ['as' => 'list-submit-final-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@submitFinalPurchaseOrder']);
        Route::get('/list-submit-final-purchase-order-particular-business/{purchase_order_id}', ['as' => 'list-submit-final-purchase-order-particular-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@getPurchaseOrderDetails']);
    
        Route::get('/accept-purchase-order/{purchase_order_id}/{business_id}', ['as' => 'accept-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@acceptPurchaseOrder']);
        Route::get('/rejected-purchase-order/{purchase_order_id}/{business_id}', ['as' => 'rejected-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@rejectedPurchaseOrder']);

        //ALL List
        Route::get('/list-forwarded-to-design', ['as' => 'list-forwarded-to-design', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListForwardedToDesign']);
        Route::get('/list-design-correction', ['as' => 'list-design-correction', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListCorrectionToDesignFromProduction']);
        Route::get('/material-ask-by-prod-to-store', ['as' => 'material-ask-by-prod-to-store', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@materialAskByProdToStore']);
        Route::get('/material-ask-by-store-to-purchase', ['as' => 'material-ask-by-store-to-purchase', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllStoreDeptSentForPurchaseMaterials']);
        Route::get('/list-purchase-orders', ['as' => 'list-purchase-orders', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListPurchaseOrder']);
        Route::get('/list-approved-purchase-orders-owner', ['as' => 'list-approved-purchase-orders-owner', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListApprovedPurchaseOrderOwnerlogin']);
        Route::get('/list-purchase-order-approved-bussinesswise/{id}', ['as' => 'list-purchase-order-approved-bussinesswise', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@submitFinalPurchaseOrder']);
    
        Route::get('/list-rejected-purchase-orders-owner', ['as' => 'list-rejected-purchase-orders-owner', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListRejectedPurchaseOrderOwnerlogin']);
        Route::get('/list-purchase-order-rejected-bussinesswise/{id}', ['as' => 'list-purchase-order-rejected-bussinesswise', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getPurchaseOrderRejectedBusinessWise']);

        // Route::get('/list-submit-final-purchase-order-particular-business/{purchase_order_id}', ['as' => 'list-submit-final-purchase-order-particular-business', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@getPurchaseOrderDetails']);
    
    
        Route::get('/list-design-uploaded-owner', ['as' => 'list-design-uploaded-owner', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@loadDesignSubmittedForProduction']);
        Route::get('/list-design-uploaded-owner-business-wise/{business_id}', ['as' => 'list-design-uploaded-owner-business-wise', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@loadDesignSubmittedForProductionBusinessWise']);

        Route::get('/list-po-recived-for-approval-payment', ['as' => 'list-po-recived-for-approval-payment', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@listPOReceivedForApprovaTowardsOwner']);
        Route::get('/accept-purchase-order-payment-release/{purchase_order_id}/{business_id}', ['as' => 'accept-purchase-order-payment-release', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@acceptPurchaseOrderPaymentRelease']);
        Route::get('/list-release-approval-payment-by-vendor', ['as' => 'list-release-approval-payment-by-vendor', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@listPOPaymentReleaseByVendor']);

       
        Route::get('/list-product-dispatch-completed', ['as' => 'list-product-dispatch-completed', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@listProductDispatchCompletedFromDispatch']);

        Route::post('/delete-addmore', ['as' => 'delete-addmore', 'uses' => 'App\Http\Controllers\Organizations\Business\BusinessController@destroyAddmore']);
        Route::get('/list-owner-submited-po-to-vendor', ['as' => 'list-owner-submited-po-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListSubmitedPurchaeOrderByVendorOwnerside']);
        Route::get('/list-owner-gatepass', ['as' => 'list-owner-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerReceivedGatePass']);
        Route::get('/list-owner-grn', ['as' => 'list-owner-grn', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerGRN']);
        Route::get('/list-material-sent-to-store-generated-grn', ['as' => 'list-material-sent-to-store-generated-grn', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListMaterialSentFromQualityToStoreGeneratedGRN']);
        Route::get('/list-material-sent-to-store-generated-grn-businesswise/{id}', ['as' => 'list-material-sent-to-store-generated-grn-businesswise', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise']);
        Route::get('/list-owner-material-recived-from-store', ['as' => 'list-owner-material-recived-from-store', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerAllListMaterialRecievedToProduction']);
        Route::get('/list-owner-final-production-completed', ['as' => 'list-owner-final-production-completed', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerAllCompletedProduction']);
        Route::get('/list-owner-final-production-completed-recive-to-logistics', ['as' => 'list-owner-final-production-completed-recive-to-logistics', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerFinalAllCompletedProductionLogistics']);
        Route::get('/recive-owner-logistics-list', ['as' => 'recive-owner-logistics-list', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerAllListBusinessReceivedFromLogistics']);
        Route::get('/list-owner-send-to-dispatch', ['as' => 'list-owner-send-to-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Business\AllListController@getOwnerAllListBusinessFianaceSendToDispatch']);
    
        Route::get('/list-product-completed-report', ['as' => 'list-product-completed-report', 'uses' => 'App\Http\Controllers\Organizations\Report\ReportController@getCompletedProductList']);


    });
    Route::group(['prefix' => 'designdept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        //ALL List
        Route::get('/list-new-requirements-received-for-design', ['as' => 'list-new-requirements-received-for-design', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@getAllNewRequirement']);
        Route::get('/list-new-requirements-received-for-design-businesswise/{id}', ['as' => 'list-new-requirements-received-for-design-businesswise', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@getAllNewRequirementBusinessWise']);
        Route::get('/list-design-upload', ['as' => 'list-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@index']);
    
        Route::get('/add-design-upload/{id}', ['as' => 'add-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@add']);
    
        Route::post('/store-design-upload', ['as' => 'store-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@store']);
        Route::get('/edit-design-upload/{id}', ['as' => 'edit-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@edit']);
        Route::post('/update-design-upload', ['as' => 'update-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@update']);
    
    
        Route::get('/add-re-upload-design/{id}', ['as' => 'add-re-upload-design', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@addReUploadDesing']);
        Route::post('/update-re-design-upload', ['as' => 'update-re-design-upload', 'uses' => 'App\Http\Controllers\Organizations\Designers\DesignUploadController@updateReUploadDesign']);
    
    
        //ALL List
        Route::get('/list-reject-design-from-prod', ['as' => 'list-reject-design-from-prod', 'uses' => 'App\Http\Controllers\Organizations\Designers\AllListController@getAllListDesignRecievedForCorrection']);
        Route::get('/list-accept-design-by-production', ['as' => 'list-accept-design-by-production', 'uses' => 'App\Http\Controllers\Organizations\Designers\AllListController@acceptdesignbyProduct']);
        Route::get('/list-design-report', ['as' => 'list-design-report', 'uses' => 'App\Http\Controllers\Organizations\Designers\AllListController@listDesignReport']);
    });
    Route::group(['prefix' => 'proddept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/accept-design/{id}', ['as' => 'accept-design', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@acceptdesign']);
        Route::get('/reject-design-edit/{id}', ['as' => 'reject-design-edit', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@rejectdesignedit']);
        Route::post('/reject-design', ['as' => 'reject-design', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@rejectdesign']);
    
        //All List
        Route::get('/list-new-requirements-received-for-production', ['as' => 'list-new-requirements-received-for-production', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllNewRequirement']);
        Route::get('/list-new-requirements-received-for-production-business-wise/{business_id}', ['as' => 'list-new-requirements-received-for-production-business-wise', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllNewRequirementBusinessWise']);


        Route::get('/list-accept-design', ['as' => 'list-accept-design', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@acceptdesignlist']);
        Route::get('/list-accept-design-business-wise/{business_id}', ['as' => 'list-accept-design-business-wise', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@acceptdesignlistBusinessWise']);
        Route::get('/list-reject-design', ['as' => 'list-reject-design', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@rejectdesignlist']);
        Route::get('/list-revislist-material-reciveded-design', ['as' => 'list-revislist-material-reciveded-design', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@reviseddesignlist']);
      
        Route::get('/list-material-recived', ['as' => 'list-material-recived', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllListMaterialRecievedToProduction']);
        Route::get('/list-final-purchase-order-production/{id}', ['as' => 'list-final-purchase-order-production', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllListMaterialRecievedToProductionBusinessWise']);
        Route::get('/edit-recived-inprocess-production-material/{id}', ['as' => 'edit-recived-inprocess-production-material', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@editProduct']);
        Route::post('/update-recived-inprocess-production-material/{id}', ['as' => 'update-recived-inprocess-production-material', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@updateProductMaterial']);
        Route::post('/delete-addmore-production-material-item', ['as' => 'delete-addmore-production-material-item', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@destroyAddmoreStoreItem']);
        Route::post('/update-final-production-completed-status/{id}', ['as' => 'update-final-production-completed-status', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@acceptProductionCompleted']);

        Route::get('/list-final-production-completed', ['as' => 'list-final-production-completed', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllCompletedProduction']);
        Route::get('/list-final-prod-completed-send-to-logistics-tracking', ['as' => 'list-final-prod-completed-send-to-logistics-tracking', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllCompletedProductionSendToLogistics']);
        Route::get('/list-final-prod-completed-send-to-logistics-tracking-product-wise/{id}', ['as' => 'list-final-prod-completed-send-to-logistics-tracking-product-wise', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllCompletedProductionSendToLogisticsProductWise']);


        Route::get('/edit-recived-bussinesswise-quantity-tracking/{id}', ['as' => 'edit-recived-bussinesswise-quantity-tracking', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@editProductQuantityTracking']);

        // Route::get('/list-final-production-completed/{id}', ['as' => 'list-final-production-completed', 'uses' => 'App\Http\Controllers\Organizations\Productions\AllListController@getAllFinalProductionCompleted']);

    });
    Route::group(['prefix' => 'storedept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    
        Route::get('/list-requistion', ['as' => 'list-requistion', 'uses' => 'App\Http\Controllers\Organizations\Store\RequistionController@index']);
        Route::get('/add-requistion', ['as' => 'add-requistion', 'uses' => 'App\Http\Controllers\Organizations\Store\RequistionController@add']);
        Route::get('/edit-requistion', ['as' => 'edit-requistion', 'uses' => 'App\Http\Controllers\Organizations\Store\RequistionController@edit']);
    
        Route::get('/accepted-and-material-sent/{id}', ['as' => 'accepted-and-material-sent', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@orderAcceptedAndMaterialForwareded']);
        Route::get('/need-to-create-req/{id}', ['as' => 'need-to-create-req', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@createRequesition']);
        Route::post('/store-purchase-request-req', ['as' => 'store-purchase-request-req', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@storeRequesition']);
        //ALL List
        Route::get('/list-accepted-design-from-prod', ['as' => 'list-accepted-design-from-prod', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListDesignRecievedForMaterial']);
        Route::get('/list-accepted-design-from-prod-business-wise/{business_id}', ['as' => 'list-accepted-design-from-prod-business-wise', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListDesignRecievedForMaterialBusinessWise']);

        Route::get('/list-material-sent-to-prod', ['as' => 'list-material-sent-to-prod', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListMaterialSentToProduction']);
        Route::get('/list-material-sent-to-purchase', ['as' => 'list-material-sent-to-purchase', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListMaterialSentToPurchase']);
        Route::get('/list-material-received-from-quality', ['as' => 'list-material-received-from-quality', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListMaterialReceivedFromQuality']);
        Route::get('/list-material-received-from-quality-bussinesswise/{id}', ['as' => 'list-material-received-from-quality-bussinesswise', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@submitFinalPurchaseOrder']);
        Route::get('/list-grn-details/{purchase_orders_id}/{business_details_id}/{id}', ['as' => 'list-grn-details', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getGRNDetails']);
        Route::get('/list-grn-details-po-tracking/{purchase_orders_id}/{business_details_id}/{id}', ['as' => 'list-grn-details-po-tracking', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getGRNDetailsPOTracking']);
       
        Route::post('/generate-sr-store-dept', ['as' => 'generate-sr-store-dept', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@generateSRstoreDept']);

        
        Route::get('/list-material-received-from-quality-po-tracking', ['as' => 'list-material-received-from-quality-po-tracking', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListMaterialReceivedFromQualityPOTracking']);
        Route::get('/list-material-received-from-quality-bussinesswise-tracking/{id}', ['as' => 'list-material-received-from-quality-bussinesswise-tracking', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllListMaterialReceivedFromQualityPOTrackingBusinessWise']);

        Route::get('/accepted-store-material-sent-to-production/{purchase_orders_id}/{business_id}', ['as' => 'accepted-store-material-sent-to-production', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@genrateStoreReciptAndForwardMaterialToTheProduction']);
    
        Route::get('/list-rejected-chalan', ['as' => 'list-rejected-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\RejectedChalanController@index']);
        Route::get('/add-rejected-chalan/{purchase_orders_id}/{id}', ['as' => 'add-rejected-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\RejectedChalanController@add']);
        Route::post('/store-rejected-chalan', ['as' => 'store-rejected-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\RejectedChalanController@store']);
        Route::get('/list-rejected-chalan-updated', ['as' => 'list-rejected-chalan-updated', 'uses' => 'App\Http\Controllers\Organizations\Store\RejectedChalanController@getAllRejectedChalanList']);
        Route::get('/list-rejected-chalan-details/{purchase_orders_id}/{id}', ['as' => 'list-rejected-chalan-details', 'uses' => 'App\Http\Controllers\Organizations\Store\RejectedChalanController@getAllRejectedChalanDetailsList']);

        Route::get('/edit-material-list-bom-wise-new-req/{id}', ['as' => 'edit-material-list-bom-wise-new-req', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@editProductMaterialWiseAddNewReq']);
        Route::post('/update-material-list-bom-wise-new-req/{id}', ['as' => 'update-material-list-bom-wise-new-req', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@updateProductMaterialWiseAddNewReq']);
        Route::post('/deleted-addmore-store-material-item', ['as' => 'deleted-addmore-store-material-item', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@destroyAddmoreStoreItem']);

        Route::get('/check-stock-quantity', ['as' => 'check-stock-quantity', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@checkStockQuantity']);

        // Route::get('/check-stock-quantity', [YourController::class, 'checkStockQuantity'])->name('check-stock-quantity');


        // Route::get('/edit-material-list-bom-wise/{purchase_orders_id}/{business_id}', ['as' => 'edit-material-list-bom-wise', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@editProductMaterialWiseAdd']);
        // Route::post('/update-material-list-bom-wise/{id}', ['as' => 'update-material-list-bom-wise', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreController@updateProductMaterialWiseAdd']);

        Route::get('/list-product-inprocess-received-from-production', ['as' => 'list-product-inprocess-received-from-production', 'uses' => 'App\Http\Controllers\Organizations\Store\AllListController@getAllInprocessProductProduction']);

   
        Route::any('/list-unit', ['as' => 'list-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@index']);
        Route::any('/add-unit', ['as' => 'add-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@add']);
        Route::any('/store-unit', ['as' => 'store-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@store']);
        Route::any('/edit-unit/{id}', ['as' => 'edit-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@edit']);
        Route::any('/update-unit', ['as' => 'update-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@update']);
        Route::any('/delete-unit/{id}', ['as' => 'delete-unit', 'uses' => 'App\Http\Controllers\Organizations\Master\UnitController@destroy']);

        Route::any('/list-hsn', ['as' => 'list-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@index']);
        Route::any('/add-hsn', ['as' => 'add-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@add']);
        Route::any('/store-hsn', ['as' => 'store-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@store']);
        Route::any('/edit-hsn/{id}', ['as' => 'edit-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@edit']);
        Route::any('/update-hsn', ['as' => 'update-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@update']);
        Route::any('/delete-hsn/{id}', ['as' => 'delete-hsn', 'uses' => 'App\Http\Controllers\Organizations\Master\HSNController@destroy']);

        Route::any('/list-group', ['as' => 'list-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@index']);
        Route::any('/add-group', ['as' => 'add-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@add']);
        Route::any('/store-group', ['as' => 'store-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@store']);
        Route::any('/edit-group/{id}', ['as' => 'edit-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@edit']);
        Route::any('/update-group', ['as' => 'update-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@update']);
        Route::any('/delete-group/{id}', ['as' => 'delete-group', 'uses' => 'App\Http\Controllers\Organizations\Master\GroupController@destroy']);

        Route::any('/list-rack', ['as' => 'list-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@index']);
        Route::any('/add-rack', ['as' => 'add-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@add']);
        Route::any('/store-rack', ['as' => 'store-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@store']);
        Route::any('/edit-rack/{id}', ['as' => 'edit-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@edit']);
        Route::any('/update-rack', ['as' => 'update-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@update']);
        Route::any('/delete-rack/{id}', ['as' => 'delete-rack', 'uses' => 'App\Http\Controllers\Organizations\Master\RackController@destroy']);
        
        Route::any('/list-process', ['as' => 'list-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@index']);
        Route::any('/add-process', ['as' => 'add-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@add']);
        Route::any('/store-process', ['as' => 'store-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@store']);
        Route::any('/edit-process/{id}', ['as' => 'edit-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@edit']);
        Route::any('/update-process', ['as' => 'update-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@update']);
        Route::any('/delete-process/{id}', ['as' => 'delete-process', 'uses' => 'App\Http\Controllers\Organizations\Master\ProcessController@destroy']);

        Route::any('/list-accessories', ['as' => 'list-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@index']);
        Route::any('/add-accessories', ['as' => 'add-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@add']);
        Route::any('/store-accessories', ['as' => 'store-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@store']);
        Route::any('/edit-accessories/{id}', ['as' => 'edit-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@edit']);
        Route::any('/update-accessories', ['as' => 'update-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@update']);
        Route::any('/delete-accessories/{id}', ['as' => 'delete-accessories', 'uses' => 'App\Http\Controllers\Organizations\Master\AccessoriesController@destroy']);
        
        Route::any('/list-inventory-material', ['as' => 'list-inventory-material', 'uses' => 'App\Http\Controllers\Organizations\Inventory\InventoryController@getMaterialList']);
        Route::any('/add-product-stock', ['as' => 'add-product-stock', 'uses' => 'App\Http\Controllers\Organizations\Inventory\InventoryController@add']);
        Route::any('/store-product-stock', ['as' => 'store-product-stock', 'uses' => 'App\Http\Controllers\Organizations\Inventory\InventoryController@store']);
        Route::any('/edit-product-stock/{id}', ['as' => 'edit-product-stock', 'uses' => 'App\Http\Controllers\Organizations\Inventory\InventoryController@edit']);
        Route::any('/update-product-stock', ['as' => 'update-product-stock', 'uses' => 'App\Http\Controllers\Organizations\Inventory\InventoryController@update']);

        Route::get('/list-delivery-chalan', ['as' => 'list-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@index']);
        // Route::post('/list-delivery-chalan', ['as' => 'list-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Purchase\DeliveryChalanController@index']);
        Route::post('/add-delivery-chalan', ['as' => 'add-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@create']);
        Route::post('/store-delivery-chalan', ['as' => 'store-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@store']);
        Route::get('/show-delivery-chalan/{id}', ['as' => 'show-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@show']);
        Route::get('/edit-delivery-chalan/{id}', ['as' => 'edit-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@edit']);
        Route::any('/update-delivery-chalan', ['as' => 'update-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@update']);
        Route::any('/delete-delivery-chalan/{id}', ['as' => 'delete-delivery-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@destroy']);
        Route::post('/delete-addmore-delivery', ['as' => 'delete-addmore-delivery', 'uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@destroyAddmore']);
        Route::get('/get-hsn-for-part-item-store', ['as' => 'get-hsn-for-part-item-store','uses' => 'App\Http\Controllers\Organizations\Store\DeliveryChalanController@getHsnForPartItemInDelivery']);

        Route::get('/list-returnable-chalan', ['as' => 'list-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@index']);
        // Route::post('/list-returnable-chalan', ['as' => 'list-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ReturnableChalanController@index']);
        Route::post('/add-returnable-chalan', ['as' => 'add-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@create']);
        Route::post('/store-returnable-chalan', ['as' => 'store-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@store']);
        Route::get('/show-returnable-chalan/{id}', ['as' => 'show-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@show']);
        Route::get('/edit-returnable-chalan/{id}', ['as' => 'edit-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@edit']);
        Route::any('/update-returnable-chalan', ['as' => 'update-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@update']);
        Route::any('/delete-returnable-chalan/{id}', ['as' => 'delete-returnable-chalan', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@destroy']);
        Route::post('/delete-addmore-returnable', ['as' => 'delete-addmore-returnable', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@destroyAddmore']);
        Route::get('/fetch-po-numbers', ['as' => 'fetch-po-numbers', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@fetchPONumbers']);
        Route::get('/get-po-numbers/{vendor_id}', ['as' => 'get-po-numbers', 'uses' => 'App\Http\Controllers\Organizations\Store\ReturnableChalanController@getPONumbers']);
    });
    Route::group(['prefix' => 'purchase', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/list-purchase', ['as' => 'list-purchase', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListMaterialReceivedForPurchase']);
    
    
        // Route::get('/submit-bom-to-owner/{id}', ['as' => 'submit-bom-to-owner', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseController@submitBOMToOwner']);
    
    
        Route::get('/list-purchase-order/{requistition_id}/{business_details_id}', ['as' => 'list-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@index']);
        Route::get('/list-purchase-order-rejected', ['as' => 'list-purchase-order-rejected', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@rejectedPurchaseOrder']);

        // Route::post('/list-purchase-order', ['as' => 'list-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@index']);
        Route::post('/add-purchase-order', ['as' => 'add-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@create']);
        Route::post('/store-purchase-order', ['as' => 'store-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@store']);
        Route::get('/show-purchase-order/{id}', ['as' => 'show-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@show']);
        Route::get('/edit-purchase-order/{id}', ['as' => 'edit-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@edit']);
        Route::post('/update-purchase-order', ['as' => 'update-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@update']);
        Route::any('/delete-purchase-order/{id}', ['as' => 'delete-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@destroy']);
        Route::post('/delete-addmore', ['as' => 'delete-addmore', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@destroyAddmore']);
        // Route::get('/get-hsn-for-part', ['as' => 'get-hsn-for-part', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@getHsnForPart']);

        Route::get('/get-hsn-for-part', [
            'as' => 'get-hsn-for-part',
            'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@getHsnForPart'
        ]);
        
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('/list-vendor', ['as' => 'list-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@index']);
            Route::get('/add-vendor', ['as' => 'add-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@add']);
            Route::post('/store-vendor', ['as' => 'store-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@store']);
            Route::get('/edit-vendor/{id}', ['as' => 'edit-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@edit']);
            Route::post('/update-vendor', ['as' => 'update-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@update']);
            Route::any('/delete-vendor/{id}', ['as' => 'delete-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\VendorController@destroy']);
        });
    
        // Route::group(['prefix' => 'purchaseorderstatus'], function () {
        Route::get('/list-approved-purchase-orders', ['as' => 'list-approved-purchase-orders', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListApprovedPurchaseOrder']);
        Route::get('/check-details-of-po-before-send-vendor/{purchase_order_id}', ['as' => 'check-details-of-po-before-send-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@checkDetailsBeforeSendPOToVendor']);
        Route::get('/list-check-final-purchase-order/{purchase_order_id}', ['as' => 'list-check-final-purchase-order', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@listAllApprovedPOToBeChecked']);
        Route::get('/finalize-and-submit-mail-to-vendor/{purchase_order_id}/{business_id}', ['as' => 'finalize-and-submit-mail-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@submitAndSentEmailToTheVendorFinalPurchaseOrder']);
    
        Route::post('/submit-purchase-order-to-owner-for-review', ['as' => 'submit-purchase-order-to-owner-for-review', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@submitPurchaseOrderToOwnerForReview']);
    
        Route::get('/list-purchase-order-approved-sent-to-vendor', ['as' => 'list-purchase-order-approved-sent-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListPurchaseOrderMailSentToVendor']);
        Route::get('/list-purchase-order-approved-sent-to-vendor-businesswise/{id}', ['as' => 'list-purchase-order-approved-sent-to-vendor-businesswise', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListPurchaseOrderMailSentToVendorBusinessWise']);
        Route::get('/list-purchase-orders-sent-to-owner', ['as' => 'list-purchase-orders-sent-to-owner', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListPurchaseOrderTowardsOwner']);
        Route::get('/list-purchase-orders-sent-to-owner-details/{purchase_order_id}', ['as' => 'list-purchase-orders-sent-to-owner-details', 'uses' => 'App\Http\Controllers\Organizations\Purchase\PurchaseOrderController@getAllListPurchaseOrderTowardsOwnerDetails']);
        Route::get('/list-purchase-order-sent-to-owner-for-approval-busineswise/{purchase_order_id}', ['as' => 'list-purchase-order-sent-to-owner-for-approval-busineswise', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getPurchaseOrderSentToOwnerForApprovalBusinesWise']);
        // });
    
        Route::get('/list-submited-po-to-vendor', ['as' => 'list-submited-po-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListSubmitedPurchaeOrderByVendor']);
        Route::get('/list-submited-po-to-vendor-businesswise/{id}', ['as' => 'list-submited-po-to-vendor-businesswise', 'uses' => 'App\Http\Controllers\Organizations\Purchase\AllListController@getAllListSubmitedPurchaeOrderByVendorBusinessWise']);

        Route::any('/list-tax', ['as' => 'list-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@index']);
        Route::any('/add-tax', ['as' => 'add-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@add']);
        Route::any('/store-tax', ['as' => 'store-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@store']);
        Route::any('/edit-tax/{id}', ['as' => 'edit-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@edit']);
        Route::any('/update-tax', ['as' => 'update-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@update']);
        Route::any('/delete-tax/{id}', ['as' => 'delete-tax', 'uses' => 'App\Http\Controllers\Organizations\Purchase\TaxController@destroy']);
    
        Route::any('/list-part-item', ['as' => 'list-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@index']);
        Route::any('/add-part-item', ['as' => 'add-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@add']);
        Route::any('/store-part-item', ['as' => 'store-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@store']);
        Route::any('/edit-part-item/{id}', ['as' => 'edit-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@edit']);
        Route::any('/update-part-item', ['as' => 'update-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@update']);
        Route::any('/delete-part-item/{id}', ['as' => 'delete-part-item', 'uses' => 'App\Http\Controllers\Organizations\Purchase\ItemController@destroy']);
    

    });
    Route::group(['prefix' => 'securitydept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/search-by-po-no', ['as' => 'search-by-po-no', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@searchByPONo']);
        // Route::get('/list-purchase-order-approved-sent-to-vendor-security', ['as' => 'list-purchase-order-approved-sent-to-vendor-security', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@searchByPONo']);
        Route::get('/list-gatepass', ['as' => 'list-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@index']);
        Route::get('/list-po-details/{id}/{purchase_order_id}', ['as' => 'list-po-details', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@getPurchaseDetails']);
        Route::get('/list-po-details-after-gatepass/{id}/{purchase_order_id}', ['as' => 'list-po-details-after-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@getPurchaseDetailsAfterGatepass']);

        Route::get('/add-gatepass', ['as' => 'add-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@add']);
    
        Route::get('/edit-gatepass/{id}', ['as' => 'edit-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@edit']);
        Route::post('/update-gatepass', ['as' => 'update-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@update']);

        Route::get('/add-gatepass-with-po/{id}', ['as' => 'add-gatepass-with-po', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@addGatePassWithPO']);
        Route::post('/store-gatepass', ['as' => 'store-gatepass', 'uses' => 'App\Http\Controllers\Organizations\Security\GatepassController@store']);
    
        Route::post('/list-all-po-number', ['as' => 'list-all-po-number', 'uses' => 'App\Http\Controllers\Organizations\Security\AllListController@getAllListMaterialRecieved']);
    });
    Route::group(['prefix' => 'quality', 'middleware' => 'admin'], function () {

        // ========================Quality Department Start========
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);

        Route::get('/list-grn', ['as' => 'list-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@index']);
        Route::get('/add-grn/{purchase_orders_id}/{id}', ['as' => 'add-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@add']);
        Route::post('/store-grn', ['as' => 'store-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@store']);
        Route::get('/get-balance-quantity', ['as' => 'get-balance-quantity', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@getBalanceQuantity']);

        Route::get('/edit-grn', ['as' => 'edit-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@edit']);
        // Route::post('/store-grn', ['as' => 'store-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@store']);
        // Route::get('/edit-grn/{id}', ['as' => 'edit-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@edit']);
        // Route::post('/update-grn', ['as' => 'update-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@update']);
        // Route::any('/delete-grn/{id}', ['as' => 'delete-grn', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@destroy']);
        // ========================Quality Department End========
        //All list
        Route::get('/list-material-sent-to-quality', ['as' => 'list-material-sent-to-quality', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@getAllListMaterialSentFromQuality']);
        Route::get('/list-material-sent-to-quality-businesswise/{id}', ['as' => 'list-material-sent-to-quality-businesswise', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@getAllListMaterialSentFromQualityBusinessWise']);

        Route::get('/list-rejected-chalan-po-wise', ['as' => 'list-rejected-chalan-po-wise', 'uses' => 'App\Http\Controllers\Organizations\Quality\GRNController@getAllListMaterialSentFromQuality']);

        
    });
    Route::group(['prefix' => 'financedept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    
        Route::get('/forward-the-purchase-order-to-the-owner-for-sanction/{purchase_orders_id}/{business_id}', ['as' => 'forward-the-purchase-order-to-the-owner-for-sanction', 'uses' => 'App\Http\Controllers\Organizations\Finance\FinanceController@forwardPurchaseOrderToTheOwnerForSanction']);
        Route::get('/list-accepted-grn-srn-finance/{purchase_orders_id}', ['as' => 'list-accepted-grn-srn-finance', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@listAcceptedGrnSrnFinance']);
        //ALL List
        Route::get('/list-sr-and-gr-genrated-business', ['as' => 'list-sr-and-gr-genrated-business', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@getAllListSRAndGRNGeanrated']);
        Route::get('/list-sr-and-gr-genrated-business-wise/{id}', ['as' => 'list-sr-and-gr-genrated-business-wise', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@getAllListSRAndGRNGeanratedBusinessWise']);
        Route::get('/list-po-sent-for-approval', ['as' => 'list-po-sent-for-approval', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@listPOSentForApprovaTowardsOwner']);
    
        Route::get('/list-po-sanction-and-need-to-do-payment-to-vendor', ['as' => 'list-po-sanction-and-need-to-do-payment-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@listPOSanctionAndNeedToDoPaymentToVendor']);
        Route::get('/send-payment-to-vendor/{purchase_orders_id}/{business_id}', ['as' => 'send-payment-to-vendor', 'uses' => 'App\Http\Controllers\Organizations\Finance\FinanceController@forwardedPurchaseOrderPaymentToTheVendor']);
        Route::get('/recive-logistics-list', ['as' => 'recive-logistics-list', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@getAllListBusinessReceivedFromLogistics']);
        Route::get('/send-to-dispatch/{id}/{business_details_id}', ['as' => 'send-to-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Finance\FinanceController@sendToDispatch']);
        Route::get('/list-send-to-dispatch', ['as' => 'list-send-to-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Finance\AllListController@getAllListBusinessFianaceSendToDispatch']);
        
    });
    Route::group(['prefix' => 'logisticsdept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/list-final-production-completed-recive-to-logistics', ['as' => 'list-final-production-completed-recive-to-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\AllListController@getAllCompletedProduction']);
        Route::get('/add-logistics/{business_id}', ['as' => 'add-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@addLogistics']);
        Route::post('/store-logistics', ['as' => 'store-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@storeLogistics']);
        Route::get('/list-logistics', ['as' => 'list-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\AllListController@getAllLogistics']);
        Route::get('/send-to-fianance/{id}/{business_details_id}', ['as' => 'send-to-fianance', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@sendToFianance']);
    Route::get('/list-send-to-fianance-by-logistics', ['as' => 'list-send-to-fianance-by-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\AllListController@getAllListSendToFiananceByLogistics']);

    
        Route::any('/list-vehicle-type', ['as' => 'list-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@index']);
        Route::any('/add-vehicle-type', ['as' => 'add-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@add']);
        Route::any('/store-vehicle-type', ['as' => 'store-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@store']);
        Route::any('/edit-vehicle-type/{id}', ['as' => 'edit-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@edit']);
        Route::any('/update-vehicle-type', ['as' => 'update-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@update']);
        Route::any('/delete-vehicle-type/{id}', ['as' => 'delete-vehicle-type', 'uses' => 'App\Http\Controllers\Organizations\Logistics\VehicleTypeController@destroy']);

        Route::any('/list-transport-name', ['as' => 'list-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@index']);
        Route::any('/add-transport-name', ['as' => 'add-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@add']);
        Route::any('/store-transport-name', ['as' => 'store-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@store']);
        Route::any('/edit-transport-name/{id}', ['as' => 'edit-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@edit']);
        Route::any('/update-transport-name', ['as' => 'update-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@update']);
        Route::any('/delete-transport-name/{id}', ['as' => 'delete-transport-name', 'uses' => 'App\Http\Controllers\Organizations\Logistics\NameOfTransportController@destroy']);
    });
    Route::group(['prefix' => 'dispatchdept', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::get('/list-final-production-completed-received-from-fianance', ['as' => 'list-final-production-completed-received-from-fianance', 'uses' => 'App\Http\Controllers\Organizations\Dispatch\AllListController@getAllReceivedFromFianance']);
        Route::get('/add-dispatch/{business_id}/{business_details_id}', ['as' => 'add-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Dispatch\DispatchController@addDispatch']);
        Route::post('/store-dispatch', ['as' => 'store-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Dispatch\DispatchController@storeDispatch']);
        Route::get('/list-dispatch', ['as' => 'list-dispatch', 'uses' => 'App\Http\Controllers\Organizations\Dispatch\AllListController@getAllDispatch']);
        
        Route::get('/list-dispatch-final-product-close', ['as' => 'list-dispatch-final-product-close', 'uses' => 'App\Http\Controllers\Organizations\Dispatch\AllListController@getAllDispatchClosedProduct']);

        // Route::get('/add-logistics/{business_id}', ['as' => 'add-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@addLogistics']);
        // Route::post('/store-logistics', ['as' => 'store-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@storeLogistics']);
        // Route::get('/list-logistics', ['as' => 'list-logistics', 'uses' => 'App\Http\Controllers\Organizations\Logistics\AllListController@getAllLogistics']);
        // Route::get('/send-to-fianance/{id}', ['as' => 'send-to-fianance', 'uses' => 'App\Http\Controllers\Organizations\Logistics\LogisticsController@sendToFianance']);
    });
    Route::group(['prefix' => 'hr', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        // Route::get('/hr-dashboard', ['as' => '/hr-dashboard', 'uses' => 'App\Http\Controllers\Organizations\Dashboard\DashboardController@index']);
        Route::get('/hr-list-employees', ['as' => 'hr-list-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@index']);
        Route::get('/hr-add-employees', ['as' => 'hr-add-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@add']);
        Route::post('/hr-store-employees', ['as' => 'hr-store-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@store']);
        Route::get('/hr-edit-employees/{id}', ['as' => 'hr-edit-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@edit']);
        Route::post('/hr-update-employees', ['as' => 'hr-update-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@update']);
        Route::any('/hr-delete-employees/{id}', ['as' => 'hr-delete-employees', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@destroy']);

        Route::get('/list-users', ['as' => 'list-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@index']);
        Route::get('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@addUsers']);
        Route::post('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@register']);
        Route::get('/edit-users/{edit_id}', ['as' => 'edit-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@editUsers']);
        Route::post('/update-users', ['as' => 'update-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@update']);
        Route::any('/delete-users/{id}', ['as' => 'delete-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@destroy']);
        Route::any('/show-users/{id}', ['as' => 'show-users', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@show']);
        Route::any('/users-leaves-details/{id}', ['as' => 'users-leaves-details', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@usersLeavesDetails']);

        Route::get('/cities', ['as' => 'cities', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@getCities']);
        Route::get('/states', ['as' => 'states', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@getState']);

        Route::get('/list-yearly-leave-management', ['as' => 'list-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@index']);
        Route::get('/add-yearly-leave-management', ['as' => 'add-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@add']);
        Route::post('/store-yearly-leave-management', ['as' => 'store-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@store']);
        Route::get('/edit-yearly-leave-management/{id}', ['as' => 'edit-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@edit']);
        Route::post('/update-yearly-leave-management', ['as' => 'update-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@update']);
        Route::any('/delete-yearly-leave-management/{id}', ['as' => 'delete-yearly-leave-management', 'uses' => 'App\Http\Controllers\Organizations\HR\LeaveManagment\LeaveManagmentController@destroy']);

        Route::get('/list-leaves', ['as' => 'list-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@index']);
        Route::get('/add-leaves', ['as' => 'add-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@add']);
        Route::post('/store-leaves', ['as' => 'store-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@store']);
        Route::get('/edit-leaves/{id}', ['as' => 'edit-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@edit']);
        Route::post('/update-leaves', ['as' => 'update-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@update']);
        Route::any('/delete-leaves/{id}', ['as' => 'delete-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@destroy']);


        Route::get('/list-leaves-acceptedby-hr', ['as' => 'list-leaves-acceptedby-hr', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@getAllLeavesRequest']);
        Route::any('/show-leaves/{id}', ['as' => 'show-leaves', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@show']);
        Route::get('/list-leaves-not-approvedby-hr', ['as' => 'list-leaves-not-approvedby-hr', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@getAllNotApprovedRequest']);
        Route::get('/list-leaves-approvedby-hr', ['as' => 'list-leaves-approvedby-hr', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@getAllApprovedRequest']);

        Route::post('/check-dates', ['as' => 'check-dates', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@checkDates']);

        // Route::post('/update-status-approved', ['as' => 'update-status-approved', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@updateLabourStatusApproved']);
        Route::post('/update-status', ['as' => 'update-status', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@updateLabourStatus']);

        Route::post('/update-status-not-approved', ['as' => 'update-status-not-approved', 'uses' => 'App\Http\Controllers\Organizations\HR\Leaves\LeavesController@updateLabourStatusNotApproved']);

        Route::get('/list-notice', ['as' => 'list-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@index']);
        Route::get('/add-notice', ['as' => 'add-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@add']);
        Route::post('/add-notice', ['as' => 'add-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@store']);
        Route::get('/edit-notice/{edit_id}', ['as' => 'edit-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@edit']);
        Route::post('/update-notice', ['as' => 'update-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@update']);
        Route::post('/show-notice', ['as' => 'show-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@show']);
        Route::any('/delete-notice/{id}', ['as' => 'delete-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@destroy']);
        Route::post('/update-active-notice', ['as' => 'update-active-notice', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@updateOne']);
        Route::get('/particular-notice-department-wise', ['as' => 'particular-notice-department-wise', 'uses' => 'App\Http\Controllers\Organizations\HR\NoticeController@departmentWiseNotice']);

    });
    Route::group(['prefix' => 'cms', 'middleware' => 'admin'], function () {

                Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
                Route::get('/list-vision-mission', ['as' => 'list-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@index']);
                Route::get('/add-vision-mission', ['as' => 'add-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@add']);
                Route::post('/add-vision-mission', ['as' => 'add-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@store']);
                Route::get('/edit-vision-mission/{edit_id}', ['as' => 'edit-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@edit']);
                Route::post('/update-vision-mission', ['as' => 'update-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@update']);
                Route::post('/show-vision-mission', ['as' => 'show-vision-mission', 'uses' => 'App\Http\Controllers\Admin\CMS\VisionMissionController@show']);
            
                // ==============media============
                Route::get('/list-services', ['as' => 'list-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@index']);
                Route::get('/add-services', ['as' => 'add-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@add']);
                Route::post('/add-services', ['as' => 'add-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@store']);
                Route::get('/edit-services/{edit_id}', ['as' => 'edit-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@edit']);
                Route::post('/update-services', ['as' => 'update-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@update']);
                Route::post('/show-services', ['as' => 'show-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@show']);
                Route::any('/delete-services/{id}', ['as' => 'delete-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@destroy']);
                Route::post('/update-active-services', ['as' => 'update-active-services', 'uses' => 'App\Http\Controllers\Admin\CMS\ServicesController@updateOne']);

                // ================
                Route::get('/list-testimonial', ['as' => 'list-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@index']);
                Route::get('/add-testimonial', ['as' => 'add-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@add']);
                Route::post('/add-testimonial', ['as' => 'add-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@store']);
                Route::get('/edit-testimonial/{edit_id}', ['as' => 'edit-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@edit']);
                Route::post('/update-testimonial', ['as' => 'update-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@update']);
                Route::post('/show-testimonial', ['as' => 'show-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@show']);
                Route::any('/delete-testimonial/{id}', ['as' => 'delete-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@destroy']);
                Route::post('/update-active-testimonial', ['as' => 'update-active-testimonial', 'uses' => 'App\Http\Controllers\Admin\CMS\TestimonialController@updateOne']);

                // ===============Our Products By Nandan 
                Route::get('/list-product', ['as' => 'list-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@index']);
                Route::get('/add-product', ['as' => 'add-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@add']);
                Route::post('/add-product', ['as' => 'add-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@store']);
                Route::get('/edit-product/{edit_id}', ['as' => 'edit-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@edit']);
                Route::post('/update-product', ['as' => 'update-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@update']);
                Route::post('/show-product', ['as' => 'show-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@showProduct']);
                Route::any('/delete-product/{id}', ['as' => 'delete-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@destroy']);
                Route::post('/update-active-product', ['as' => 'update-active-product', 'uses' => 'App\Http\Controllers\Admin\CMS\ProductController@updateOne']);

            // ===============Our Products Details By Nandan 
                Route::get('/list-director-desk', ['as' => 'list-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@index']);
                Route::get('/add-director-desk', ['as' => 'add-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@add']);
                Route::post('/add-director-desk', ['as' => 'add-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@store']);
                Route::get('/edit-director-desk/{edit_id}', ['as' => 'edit-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@edit']);
                Route::post('/update-director-desk', ['as' => 'update-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@update']);
                Route::post('/show-director-desk', ['as' => 'show-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@show']);
                Route::post('/delete-director-desk', ['as' => 'delete-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@destroy']);
                Route::post('/update-active-director-desk', ['as' => 'update-active-director-desk', 'uses' => 'App\Http\Controllers\Admin\CMS\DirectorDeskController@updateOne']);


                Route::get('/list-team', ['as' => 'list-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@index']);
                Route::get('/add-team', ['as' => 'add-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@add']);
                Route::post('/add-team', ['as' => 'add-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@store']);
                Route::get('/edit-team/{edit_id}', ['as' => 'edit-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@edit']);
                Route::post('/update-team', ['as' => 'update-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@update']);
                Route::post('/show-team', ['as' => 'show-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@show']);
                Route::any('/delete-team/{id}', ['as' => 'delete-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@destroy']);
                Route::post('/update-active-team', ['as' => 'update-active-team', 'uses' => 'App\Http\Controllers\Admin\CMS\TeamController@updateOne']);

                // ===============Contact 
                Route::get('/list-contactus-form', ['as' => 'list-contactus-form', 'uses' => 'App\Http\Controllers\Admin\CMS\ContactUsListController@index']);
                Route::post('/show-contactus-form', ['as' => 'show-contactus-form', 'uses' => 'App\Http\Controllers\Admin\CMS\ContactUsListController@show']);
                Route::any('/delete-contactus-form/{id}', ['as' => 'delete-contactus-form', 'uses' => 'App\Http\Controllers\Admin\CMS\ContactUsListController@destroy']);

        
    });
    Route::group(['prefix' => 'inventory', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    });

    Route::group(['prefix' => 'employee', 'middleware' => 'admin'], function () {
        Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
        Route::any('/show-employee-profile/{id}', ['as' => 'show-employee-profile', 'uses' => 'App\Http\Controllers\Organizations\HR\Employees\EmployeesHrController@showParticularDetails']);

    });
// frontend website shreerag path 
Route::get('/', ['as' => 'index', 'uses' => 'App\Http\Controllers\Website\PagesController@index']);
Route::get('/about', ['as' => 'about', 'uses' => 'App\Http\Controllers\Website\AboutController@index']);
Route::get('/services', ['as' => 'services', 'uses' => 'App\Http\Controllers\Website\ProductServicesController@getAllServices']);
Route::get('/product', ['as' => 'product', 'uses' => 'App\Http\Controllers\Website\ProductServicesController@index']);
Route::get('/contactus', ['as' => 'contactus', 'uses' => 'App\Http\Controllers\Website\ContactUsController@getContactUs']);
Route::post('/add-contactus', ['as' => 'add-contactus', 'uses' => 'App\Http\Controllers\Website\ContactUsController@addContactUs']);
Route::post('/product-details', ['as' => 'product-details', 'uses' => 'App\Http\Controllers\Website\ProductServicesController@showParticularPrdouct']);

Route::get('/production-dashboard', ['as' => '/production-dashboard', 'uses' => 'App\Http\Controllers\Organizations\Dashboard\DashboardController@index']);
Route::get('/list-products', ['as' => 'list-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@index']);
Route::get('/add-products', ['as' => 'add-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@add']);
Route::post('/store-products', ['as' => 'store-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@store']);
Route::get('/edit-products/{id}', ['as' => 'edit-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@edit']);
Route::post('/update-products', ['as' => 'update-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@update']);
Route::any('/delete-products/{id}', ['as' => 'delete-products', 'uses' => 'App\Http\Controllers\Organizations\Productions\ProductionController@destroy']);




// ========================  Start DocUploadFianace ========

Route::get('/list-doc-upload-fianace', ['as' => 'list-doc-upload-fianace', 'uses' => 'App\Http\Controllers\Organizations\Store\DocUploadFianaceController@index']);
Route::get('/add-doc-upload-fianace', ['as' => 'add-doc-upload-fianace', 'uses' => 'App\Http\Controllers\Organizations\Store\DocUploadFianaceController@add']);
Route::get('/edit-doc-upload-fianace', ['as' => 'edit-doc-upload-fianace', 'uses' => 'App\Http\Controllers\Organizations\Store\DocUploadFianaceController@edit']);
// ========================  End DocUploadFianace ========
// ======================== Start Security Remarkcontroller========
Route::get('/list-security-remark', ['as' => 'list-security-remark', 'uses' => 'App\Http\Controllers\Organizations\Security\SecurityRemarkController@index']);
Route::get('/add-security-remark', ['as' => 'add-security-remark', 'uses' => 'App\Http\Controllers\Organizations\Security\SecurityRemarkController@add']);
Route::get('/edit-security-remark', ['as' => 'edit-security-remark', 'uses' => 'App\Http\Controllers\Organizations\Security\SecurityRemarkController@edit']);
// ========================End Security Remarkcontroller========
// ======================== Start store receipt controller ========

Route::get('/list-store-receipt', ['as' => 'list-store-receipt', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreReceiptController@index']);
Route::get('/add-store-receipt', ['as' => 'add-store-receipt', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreReceiptController@add']);
Route::get('/edit-store-receipt', ['as' => 'edit-store-receipt', 'uses' => 'App\Http\Controllers\Organizations\Store\StoreReceiptController@edit']);
// ======================== End store receipt controller ========
// ========================  Start Vendor controller ========



// ========================  End Vendor controller ========
