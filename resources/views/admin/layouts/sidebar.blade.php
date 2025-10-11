<style>
    /* ============= pratiksha (21/08/24) ============= */
    .navbar-btn-wb {
        color: #fff !important;
        border: 1px solid #fff !important;
    }

    .notification-menu {
        overflow-y: auto !important;
    }

    .nav-link .fa-bell {
        position: relative;
    }

    .notification-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;


        /* .notification-count { */
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
        /* } */
    }

    .nav-item.active > a,
.nav-item.active > a .mini-click-non {
    background-color: #1A5DA0 !important;
    color: #fff !important;
    
}
.active-submenu {
    background-color: #1A5DA0 !important; /* or any color that matches your theme */
    color: #fff !important;
    display: block;
}


</style>
<!-- ============= pratiksha (21/08/24) ============= change for sidebar changes and change icon -->

<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <img class="main-logo1" src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}"
                style="height: 3.9rem!important;" alt="">
            <a href="{{ route('login') }}"><img class="main-logo"
                    src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}" alt=""></a>
            <!-- <strong><img src="{{ asset('img/logo/logo_updated.png') }}" alt="" ></strong> -->
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">



                    @if (session()->get('role_id') == config('constants.ROLE_ID.SUPER'))
                        <li class="nav-item {{ request()->is('/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa big-icon fa-envelope icon-wrap"
                                    aria-hidden="true"></i> <span class="mini-click-non">Dashboard</span></a>
                        </li>
                        <li class="{{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fa fa-dashboard icon-wrap"></i>
                                    <span class="mini-click-non">Dashboard</span>
                                </a>
                            </li>

                        <li
                            class="{{ Request::is('list-organizations', 'organizations-list-employees', 'list-departments', 'list-roles') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-organizations') }}" aria-expanded="false">
                                <i class="fa big-icon fa-building icon-wrap"></i>
                                <span class="mini-click-non">Organizations</span>
                            </a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-organizations') ? 'active' : '' }}">
                                    <a href="{{ route('list-organizations') }}">
                                        <i class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Organizations</span>
                                    </a>
                                </li>
                                
                                <li class="{{ Request::is('hr/users-leaves-details/*') || Request::is('hr/show-users/*') || Request::is('edit-users/*') ? 'active' : '' }}">
                                    <a href="{{ route('list-users') }}" aria-expanded="false"><i
                                            class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Add Employees</span></a>
                                </li>

                                <li class="nav-item {{ Request::is('list-roles') ? 'active' : '' }}">
                                    <a href="{{ route('list-roles') }}">
                                        <i class="fa big-icon fa-user-tag icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Department</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                        <ul class="sidebar-menu" id="nav-accordion">

                                                        <li class="nav-item {{ request()->is('owner/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}" aria-expanded="false"><i class="fa fa-tachometer-alt icon-wrap"></i><span
                                        class="mini-click-non">Dashboard</span></a>
                            </li>

                         
                          @php
    $reportRoutes = [
        'list-dispatch-bar-chart',
        'list-vendor-through-taken-material',
        'list-consumption-report',
        'list-product-completed-report',
        'stock-item',
    ];
    $isReportActive = in_array(Route::currentRouteName(), $reportRoutes);
@endphp

<li class="{{ $isReportActive ? 'active' : '' }}">
    <a class="has-arrow" href="javascript:void(0);" 
       aria-expanded="{{ $isReportActive ? 'true' : 'false' }}">
        <i class="fa fa-chart-bar icon-wrap"></i>
        <span class="mini-click-non">Report</span>
    </a>
 <ul class="submenu-angle"
    aria-expanded="{{ $isReportActive ? 'true' : 'false' }}"
    style="{{ $isReportActive ? 'display: block; background-color: #2437720d;' : '' }}">

        <li>
            <a title="Product Completed Report" 
               href="{{ route('list-product-completed-report') }}"
               class="{{ Route::currentRouteName() == 'list-product-completed-report' ? 'active-submenu' : '' }}">
                <i class="fa fa-check-circle icon-wrap"></i> 
                <span class="mini-sub-pro">Product Completed</span>
            </a>
        </li>

        <li>
            <a title="Stock Item" 
               href="{{ route('stock-item') }}"
               class="{{ Route::currentRouteName() == 'stock-item' ? 'active-submenu' : '' }}">
               <i class="fa fa-boxes icon-wrap"></i> 
               <span class="mini-sub-pro">Stock Item</span>
            </a>
        </li>

        <li>
            <a title="Consumption Report" 
               href="{{ route('list-consumption-report') }}"
               class="{{ Route::currentRouteName() == 'list-consumption-report' ? 'active-submenu' : '' }}">
               <i class="fa fa-chart-pie icon-wrap"></i> 
               <span class="mini-sub-pro">Consumption</span>
            </a>
        </li>

        <li>
            <a title="Dispatch Bar Chart" 
               href="{{ route('list-dispatch-bar-chart') }}"
               class="{{ Route::currentRouteName() == 'list-dispatch-bar-chart' ? 'active-submenu' : '' }}">
                <i class="fa fa-truck-loading icon-wrap"></i>
                <span class="mini-sub-pro">Dispatch Bar Chart</span>
            </a>
        </li>

        <li>
            <a title="Vendor Taken Material" 
               href="{{ route('list-vendor-through-taken-material') }}"
               class="{{ Route::currentRouteName() == 'list-vendor-through-taken-material' ? 'active-submenu' : '' }}">
               <i class="fa fa-people-carry icon-wrap"></i> 
               <span class="mini-sub-pro">Vendor Taken Material</span>
            </a>
        </li>
    </ul>
</li>



                             {{-- <li>
                                <a class="has-arrow" href="{{ route('list-product-completed-report') }}"
                                    aria-expanded="false"><i class="fa fa-check-circle icon-wrap"></i> <span
                                        class="mini-click-non">Report
                                    </span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li class="{{ Request::is('list-dispatch-bar-chart') ? 'active' : '' }}"><a href="{{ route('list-dispatch-bar-chart') }}" aria-expanded="false"><i
                                                class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">Dispatch Bar Chart</span></a></li>
                                                <li class="{{ Request::is('list-vendor-through-taken-material') ? 'active' : '' }}"><a href="{{ route('list-vendor-through-taken-material') }}" aria-expanded="false"><i
                                                class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">Vendor Through Taken Material</span></a></li>
                                    <li class="{{ Request::is('list-consumption-report') ? 'active' : '' }}"><a href="{{ route('list-consumption-report') }}"><i
                                                class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">List Consumption</span></a></li>                                                
                                                <li class="{{ Request::is('list-product-completed-report') ? 'active' : '' }}"><a href="{{ route('list-product-completed-report') }}"><i
                                                class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">List Product Completed</span></a></li>
                                                 <li class="{{ Request::is('stock-item') ? 'active' : '' }}"><a href="{{ route('stock-item') }}"><i
                                                class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">Item Stock</span></a></li>
                                                
                                </ul>
                            </li> --}}

                            <li class="nav-item {{ request()->is('hr/list-users') ? 'active' : '' }}">
                                <a href="{{ route('list-users') }}" aria-expanded="false"> <i class="fa fa-user-friends icon-wrap"></i><span
                                        class="mini-click-non">Add Employees</span></a>
                            </li>
                             <li class="nav-item {{ request()->is('owner/list-business') || request()->is('owner/edit-business/*') ? 'active' : '' }}">
                                <a href="{{ route('list-business') }}" aria-expanded="false"><i class="fa fa-briefcase icon-wrap"></i><span
                                        class="mini-click-non">Business</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-forwarded-to-design') ? 'active' : '' }}">
                                <a href="{{ route('list-forwarded-to-design') }}" aria-expanded="false"> <i class="fa fa-paper-plane icon-wrap"></i><span
                                        class="mini-click-non">Business Sent For Design</span></a>
                            </li>
                             <li class="nav-item {{ request()->is('estimationdept/list-new-requirements-received-for-estimation') || request()->is('estimationdept/list-new-requirements-received-for-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-new-requirements-received-for-estimation') }}" aria-expanded="false"> <i class="fa fa-project-diagram icon-wrap"></i><span
                                        class="mini-click-non">Design Dept Sent Design and BOM to Estimation Dept</span></a>
                            </li>
                              <li class="nav-item {{ request()->is('owner/list-design-received-estimation') || request()->is('owner/list-design-received-estimation-business-wise/*') ? 'active' : '' }}">
                             
                                <a href="{{ route('list-design-received-estimation') }}" aria-expanded="false"><i class="fa fa-file-alt icon-wrap"></i><span class="mini-click-non">Design and BOM 
                                        Received Estimation</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-accept-bom-estimation') || request()->is('owner/list-accept-bom-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-accept-bom-estimation') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-check icon-wrap"></i> <span class="mini-click-non">Accepted Estimation</span></a>
                            </li>
                             <li class="nav-item {{ request()->is('owner/list-rejected-bom-estimation') || request()->is('owner/list-rejected-bom-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-rejected-bom-estimation') }}" aria-expanded="false"><i class="fa fa-times-circle icon-wrap"></i><span class="mini-click-non">Rejected Estimation</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-revised-bom-estimation') || request()->is('owner/list-revised-bom-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-revised-bom-estimation') }}">
                                   <i class="fa fa-list-alt icon-wrap"></i>
                                    <span class="mini-click-non">Revised Estimation</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-design-uploaded-owner') || request()->is('owner/list-design-uploaded-owner-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-design-uploaded-owner') }}" aria-expanded="false"><i class="fa big-icon fa-upload icon-wrap"></i> <span class="mini-click-non">Design
                                        Received For Production</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-design-correction') ? 'active' : '' }}">
                                <a href="{{ route('list-design-correction') }}" aria-expanded="false"><i class="fa big-icon fa-edit icon-wrap"></i><span class="mini-click-non">Design
                                        Received For Design Correction</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/material-ask-by-prod-to-store') ? 'active' : '' }}">
                                <a href="{{ route('material-ask-by-prod-to-store') }}" aria-expanded="false"><i class="fa fa-warehouse icon-wrap"></i><span
                                        class="mini-click-non">Material Ask By Production To Store</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/material-ask-by-store-to-purchase') ? 'active' : '' }}">
                                <a href="{{ route('material-ask-by-store-to-purchase') }}" aria-expanded="false"><i class="fa fa-truck-loading icon-wrap"></i><span
                                        class="mini-click-non">Purchase Material Ask By Store To Purchase</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-purchase-orders') || request()->is('owner/list-submit-final-purchase-order/*') || request()->is('owner/list-submit-final-purchase-order-particular-business/*')? 'active' : '' }}">
                                <a href="{{ route('list-purchase-orders') }}" aria-expanded="false"><i class="fa fa-file-invoice-dollar icon-wrap"></i><span
                                        class="mini-click-non">Purchase order need to check</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-approved-purchase-orders-owner') || request()->is('owner/list-purchase-order-approved-bussinesswise/*') || request()->is('owner/list-submit-final-purchase-order-particular-business/*')? 'active' : '' }}">
                                <a href="{{ route('list-approved-purchase-orders-owner') }}" aria-expanded="false"><i class="fa fa-check icon-wrap"></i><span
                                        class="mini-click-non">Purchase Order Approved</span></a>
                            </li>
                             <li class="nav-item {{ request()->is('owner/list-rejected-purchase-orders-owner') || request()->is('owner/list-purchase-order-rejected-bussinesswise/*') || request()->is('owner/list-submit-final-purchase-order-particular-business/*')? 'active' : '' }}">
                                <a href="{{ route('list-rejected-purchase-orders-owner') }}" aria-expanded="false"><i class="fa fa-times icon-wrap"></i><span
                                        class="mini-click-non">Purchase Order Rejected</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-owner-submited-po-to-vendor') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('list-owner-submited-po-to-vendor') }}"><i
                                        class="fa big-icon fa-user-tag icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Submitted PO by Vendor</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-owner-gatepass') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-gatepass') }}"><i class="fa big-icon fa-id-badge icon-wrap"></i> <span
                                        class="mini-click-non">Security Created Gate Pass</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-owner-grn') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-grn') }}"><i class="fa big-icon fa-clipboard-check icon-wrap"></i><span
                                        class="mini-click-non">Material Received for GRN Generate</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-material-sent-to-store-generated-grn') || request()->is('owner/list-material-sent-to-store-generated-grn-businesswise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-material-sent-to-store-generated-grn') }}"><i class="fa big-icon fa-truck-loading icon-wrap"></i> <span
                                        class="mini-click-non">Generated GRN Material send Quality Dept to Store
                                    </span></a>
                            </li>

                            <li
                                class="nav-item {{ request()->is('owner/list-owner-material-recived-from-store') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-material-recived-from-store') }}"><i class="fa big-icon fa-box icon-wrap"></i><span
                                        class="mini-click-non">Store Dept Material send to Production Dept</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-owner-final-production-completed') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-final-production-completed') }}"><i class="fa big-icon fa-check-circle icon-wrap"></i> <span
                                        class="mini-click-non">Production Department Completed Production</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-owner-final-production-completed-recive-to-logistics') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-final-production-completed-recive-to-logistics') }}"><i class="fa big-icon fa-warehouse icon-wrap"></i><span
                                        class="mini-click-non">Logistics Dept Received Product completed
                                        list</span></a>
                            </li>

                            <li
                                class="nav-item {{ request()->is('owner/recive-owner-logistics-list') ? 'active' : '' }}">
                                <a href="{{ route('recive-owner-logistics-list') }}"><i class="fa big-icon fa-receipt icon-wrap"></i><span
                                        class="mini-click-non">Fianance Dept Production Received from Logistics
                                        Dept</span></a>
                            </li>

                            <li
                                class="nav-item {{ request()->is('owner/list-owner-send-to-dispatch') ? 'active' : '' }}">
                                <a href="{{ route('list-owner-send-to-dispatch') }}"><i class="fa big-icon fa-paper-plane icon-wrap"></i> <span
                                        class="mini-click-non">Fianance Dept Production Request Send to Dispatch
                                        Dept</span></a>
                            </li>

                            <li
                                class="nav-item {{ request()->is('owner/list-product-dispatch-completed') ? 'active' : '' }}">
                                <a href="{{ route('list-product-dispatch-completed') }}"><i class="fa big-icon fa-truck icon-wrap"></i><span
                                        class="mini-click-non">Dispatch Dept Production Dispatch Completed</span></a>
                            </li>
                             <li class="nav-item {{ request()->is('storedept/list-rejected-chalan-updated') || request()->is('owner/list-revised-bom-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-rejected-chalan-updated') }}" aria-expanded="false"><i class="fa big-icon fa-times-circle icon-wrap"></i><span
                                        class="mini-click-non">List Rejected Chalan</span></a>
                            </li>
                            <li
                            class="nav-item {{ request()->is('owner/list-approved-purchase-orders-owner') ? 'active' : '' }}">
                            <a href="{{ route('list-approved-purchase-orders-owner') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-rupee-sign icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">Fianance Dept Received GRN and SR</span></a>
                        </li> 
                            <li
                                class="nav-item {{ request()->is('owner/list-po-recived-for-approval-payment') ? 'active' : '' }}">
                                <a href="{{ route('list-po-recived-for-approval-payment') }}"
                                    aria-expanded="false"><i class="fa big-icon fa-file-invoice-dollar icon-wrap"></i><span class="mini-click-non">PO Payment Release
                                        Request</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-release-approval-payment-by-vendor') ? 'active' : '' }}">
                                <a href="{{ route('list-release-approval-payment-by-vendor') }}"
                                    aria-expanded="false"><i class="fa big-icon fa-hand-holding-usd icon-wrap"></i><span class="mini-click-non">PO Payment Released to Vendor</span></a>
                            </li>

                          <li
                            class="nav-item {{ request()->is('owner/list-login-history') ? 'active' : '' }}">
                            <a href="{{ route('list-login-history') }}"
                                aria-expanded="false"><i class="fa fa-history icon-wrap"></i><span class="mini-click-non">Login History</span></a>
                        </li>
                      </ul>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PURCHASE'))
                        <li class="nav-item {{ request()->is('purchase/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"> <i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
 <li class="{{ Request::is('purchase-report') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('purchase-report') }}" aria-expanded="false"><i class="fa fa-chart-line icon-wrap"></i><span
                                    class="mini-click-non">Report</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('purchase-report') ? 'active' : '' }}"><a
                                        href="{{ route('purchase-report') }}"> <i class="fa fa-list-alt icon-wrap"></i> <span
                                            class="mini-click-non">Purchase Report</span></a></li>

                                              <li class="{{ Request::is('list-vendor-through-taken-material') ? 'active' : '' }}"><a href="{{ route('list-vendor-through-taken-material') }}" aria-expanded="false"><i class="fa fa-users icon-wrap"></i><span
                                                class="mini-click-non">Vendor Through Taken Material</span></a></li>
                                                 <li class="{{ Request::is('stock-item') ? 'active' : '' }}"><a href="{{ route('stock-item') }}"><i class="fa fa-box icon-wrap"></i> <span
                                                class="mini-click-non">Item Stock</span></a></li>

                                      <li class="nav-item {{ Request::is('party-report') ? 'active' : '' }}"><a
                                        href="{{ route('party-report') }}"><i
                                            class="fa big-icon fa-list-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Party Wise Report</span></a></li>     
                                      <li class="nav-item {{ Request::is('follow-up-report') ? 'active' : '' }}"><a
                                        href="{{ route('follow-up-report') }}"><i class="fa fa-clipboard-check icon-wrap"></i><span
                                            class="mini-click-non">Follow Up Report</span></a></li>       
                                
                            </ul>
                        </li>
                        <li class="{{ Request::is('list-purchase') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-purchase') }}" aria-expanded="false"><i class="fa fa-file-invoice icon-wrap"></i><span
                                    class="mini-click-non">Purchase Orders</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-purchase') ? 'active' : '' }}"><a
                                        href="{{ route('list-purchase') }}"><i class="fa fa-list-alt icon-wrap"></i> <span
                                            class="mini-click-non">List Purchase Orders To Be Finalize</span></a></li>

                                <li
                                    class="nav-item {{ Request::is('list-purchase-order-rejected') ? 'active' : '' }}">
                                    <a href="{{ route('list-purchase-order-rejected') }}"> <i class="fa fa-times-circle icon-wrap"></i><span
                                            class="mini-click-non">Rejected Purchase Orders List</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ Request::is('purchase/vendor/list-vendor') || request()->is('purchase/vendor/add-vendor') || request()->is('purchase/vendor/edit-vendor/*') ? 'active' : '' }}">
                            <a href="{{ route('list-vendor') }}"> <i class="fa fa-users icon-wrap"></i><span class="mini-click-non">Vendor List</span></a></li>

                        <li class="nav-item {{ request()->is('purchase/list-tax') || request()->is('purchase/add-tax') || request()->is('purchase/edit-tax/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-tax') }}" aria-expanded="false"><i class="fa fa-receipt icon-wrap"></i> <span
                                    class="mini-click-non">Tax</span></a>
                        </li>
                         <li class="nav-item {{ request()->is('purchase/list-vendor-type') || request()->is('purchase/add-vendor-type') || request()->is('purchase/edit-vendor-type/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-vendor-type') }}" aria-expanded="false"><i class="fa fa-id-badge icon-wrap"></i><span
                                    class="mini-click-non">Vendor Type</span></a>
                        </li>
                        
                        <li class="{{ Request::is('list-approved-purchase-orders') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-approved-purchase-orders') }}"
                                aria-expanded="false"><i class="fa fa-file-signature icon-wrap"></i> <span
                                    class="mini-click-non">Purchase Order Status</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li
                                    class="nav-item {{ request()->is('purchase/list-purchase-orders-sent-to-owner') ? 'active' : '' }}">
                                    <a href="{{ route('list-purchase-orders-sent-to-owner') }}"
                                        aria-expanded="false"><i class="fa fa-paper-plane icon-wrap"></i>
                                        <span class="mini-click-non">Purchase Order Submited For Approval</span></a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('list-approved-purchase-orders') ? 'active' : '' }}">
                                    <a href="{{ route('list-approved-purchase-orders') }}" aria-expanded="false"> <i class="fa fa-check-circle icon-wrap"></i> <span
                                            class="mini-click-non">Purchase Order Approved</span></a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('list-purchase-order-approved-sent-to-vendor') ? 'active' : '' }}">
                                    <a href="{{ route('list-purchase-order-approved-sent-to-vendor') }}"><i class="fa fa-envelope icon-wrap"></i> <span
                                            class="mini-click-non">Purchase Order Sent To Vendor</span></a>
                                </li>
                                <li
                                class="nav-item {{ Request::is('list-rejected-chalan-po-wise') ? 'active' : '' }}">
                                <a 
                                    href="{{ route('list-rejected-chalan-po-wise') }}"><i
                                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">PO wise Rejected Chalan List</span></a>
                            </li>
                            </ul>
                        </li>
                        <li  class="nav-item {{ request()->is('purchase/list-submited-po-to-vendor') || request()->is('purchase/list-submited-po-to-vendor-businesswise/*') || request()->is('purchase/check-details-of-po-before-send-vendor/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-submited-po-to-vendor') }}"
                                aria-expanded="false"><i class="fa fa-check-circle icon-wrap"></i><span
                                    class="mini-click-non">Submitted PO by Vendor List</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking') || request()->is('storedept/list-material-received-from-quality-bussinesswise-tracking/*') || request()->is('storedept/list-grn-details-po-tracking/*') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                                <i class="fa fa-clipboard-list icon-wrap"></i>
                                <span class="mini-click-non">Material Received PO Tracking</span>
                            </a>
                        </li>                           
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.DESIGNER'))
                        <li class="nav-item {{ request()->is('designdept/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap" aria-hidden="true"></i> <span class="mini-click-non">Dashboard</span></a>
                        </li>
                          <li class="{{ Request::is('list-design-report') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-design-report') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-file-invoice icon-wrap"></i> <span
                                    class="mini-click-non">Report</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-design-report') ? 'active' : '' }}"><a
                                        href="{{ route('list-design-report') }}"><i
                                            class="fa big-icon fa-list-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Design Report</span></a></li>
                                
                            </ul>
                        </li>
                         <li class="nav-item {{ request()->is('designdept/list-new-requirements-received-for-design') || request()->is('designdept/list-new-requirements-received-for-design-businesswise/*') || request()->is('designdept/add-design-upload/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-new-requirements-received-for-design') }}"
                                aria-expanded="false"> <i class="fa fa-briefcase icon-wrap"></i> <span
                                    class="mini-click-non">New Business<br> Received For Design</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-design-upload') || request()->is('owner/list-design-uploaded-owner-business-wise/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-design-upload') }}" aria-expanded="false"> <i class="fa fa-paper-plane icon-wrap"></i>  <span
                                    class="mini-click-non">Designs Sent To Estimation</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-updated-design') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-updated-design') }}" aria-expanded="false"><i class="fa fa-pencil-alt icon-wrap"></i> <span
                                    class="mini-click-non">Corrected Designs Sent To Production</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-reject-design-from-prod') || request()->is('designdept/add-re-upload-design/*') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-reject-design-from-prod') }}"
                                aria-expanded="false"><i class="fa fa-ban icon-wrap"></i>  <span
                                    class="mini-click-non">Rejected Design List</span></a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('designdept/list-accept-design-by-production*') ? 'active' : '' }}">
                            <a href="{{ route('list-accept-design-by-production') }}">
                                <i class="fa fa-check-circle icon-wrap"></i>
                                <span class="mini-click-non">Accepted Design List</span>
                            </a>
                        </li>
                       
                         

                    @endif
                      @if (session()->get('role_id') == config('constants.ROLE_ID.ESTIMATION'))
                        <ul class="sidebar-menu">
                            <li class="nav-item {{ request()->is('estimationdept/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}"><i class="fa big-icon fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('estimationdept/list-new-requirements-received-for-estimation') || request()->is('estimationdept/list-new-requirements-received-for-estimation-business-wise/*') || request()->is('estimationdept/edit-estimation/*') ? 'active' : '' }}">
                                <a href="{{ route('list-new-requirements-received-for-estimation') }}">
                                    <i class="fa big-icon fa-file-alt icon-wrap"></i>
                                    <span class="mini-click-non">New Design List</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('estimationdept/list-updated-estimation-send-to-owner') || request()->is('estimationdept/list-updated-estimation-send-to-owner-business-wise/*') ? 'active' : '' }}">                           
                                <a href="{{ route('list-updated-estimation-send-to-owner') }}">
                                    <i class="fa big-icon fa-paper-plane icon-wrap"></i>
                                    <span class="mini-click-non">Updated Estimation Send to Owner</span>
                                </a>
                            </li>
                             <li class="nav-item {{ request()->is('owner/list-accept-bom-estimation') || request()->is('owner/list-accept-bom-estimation-business-wise/*') ? 'active' : '' }}">
                                <a href="{{ route('list-accept-bom-estimation') }}">
                                    <i class="fa big-icon fa-check-circle icon-wrap"></i>
                                    <span class="mini-click-non">Accepted Estimation</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-rejected-bom-estimation') || request()->is('owner/list-rejected-bom-estimation-business-wise/*') || request()->is('estimationdept/edit-revised-bom-material-estimation/*') ? 'active' : '' }}">
                                <a href="{{ route('list-rejected-bom-estimation') }}">
                                    <i class="fa fa-ban  icon-wrap"></i>
                                    <span class="mini-click-non">Rejected Estimation</span>
                                </a>
                            </li>

                           
                            <li
                                class="nav-item {{ request()->is('estimationdept/list-send-to-production') ? 'active' : '' }}">
                                <a href="{{ route('list-send-to-production') }}">
                                    <i class="fa big-icon fa-industry icon-wrap"></i>
                                    <span class="mini-click-non">Request send to Production</span>
                                </a>
                            </li>
                          
                        </ul>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PRODUCTION'))
                        <ul class="sidebar-menu">
                            <li class="nav-item {{ request()->is('proddept/dashboard') ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                            </li>
                              <li class="nav-item {{ request()->is('proddept/list-production-report*') ? 'active' : '' }}">
                            <a href="{{ route('list-production-report') }}">
                               <i class="fa fa-chart-line icon-wrap"></i>
                                <span class="mini-click-non">Production Report </span>
                            </a>
                        </li>
                            <li class="nav-item {{ request()->is('proddept/list-new-requirements-received-for-production') || request()->is('proddept/list-new-requirements-received-for-production-business-wise/*') || request()->is('proddept/reject-design-edit/*') ? 'active' : '' }}">
                                <a href="{{ route('list-new-requirements-received-for-production') }}">
                                     <i class="fa fa-file-alt icon-wrap"></i>
                                    <span class="mini-click-non">New Design List</span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('proddept/list-accept-design*') ? 'active' : '' }}">
                                <a href="{{ route('list-accept-design') }}">
                                    <i class="fa fa-check-circle icon-wrap"></i>
                                    <span class="mini-click-non">Accepted Design List</span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('proddept/list-reject-design') ? 'active' : '' }}">
                                <a href="{{ route('list-reject-design') }}">
                                     <i class="fa fa-times-circle icon-wrap"></i>
                                    <span class="mini-click-non">Rejected Design List</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('proddept/list-revislist-material-reciveded-design') || request()->is('proddept/reject-design-edit/*') ? 'active' : '' }}">
                                <a href="{{ route('list-revislist-material-reciveded-design') }}">
                                    <i class="fa fa-edit icon-wrap"></i>
                                    <span class="mini-click-non">Revised Design List</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('proddept/list-material-received') || request()->is('proddept/list-final-purchase-order-production/*') || request()->is('proddept/edit-recived-inprocess-production-material/*') || request()->is('proddept/edit-recived-bussinesswise-quantity-tracking/*') ? 'active' : '' }}">                            
                                <a href="{{ route('list-material-received') }}">
                                   <i class="fa fa-boxes icon-wrap"></i>
                                    <span class="mini-click-non">Tracking Material</span>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('proddept/list-final-production-completed') ? 'active' : '' }}">
                                <a href="{{ route('list-final-production-completed') }}">
                                     <i class="fa fa-clipboard-check icon-wrap"></i>
                                    <span class="mini-click-non">Production Completed</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('proddept/list-final-prod-completed-send-to-logistics-tracking') || request()->is('proddept/list-final-prod-completed-send-to-logistics-tracking-product-wise/*')  ? 'active' : '' }}">                            
                                <a href="{{ route('list-final-prod-completed-send-to-logistics-tracking') }}">
                                    <i class="fa fa-shipping-fast icon-wrap"></i>
                                    <span class="mini-click-non">Tracking Of Send Material To Logistics Dept</span>
                                </a>
                            </li>
                           
                        </ul>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SECURITY'))
                    <li class="nav-item {{ request()->is('securitydept/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                    </li>
                     <li class="nav-item {{ request()->is('security-report*') ? 'active' : '' }}">
                            <a href="{{ route('security-report') }}">
                                 <i class="fa fa-file-alt icon-wrap"></i>
                                <span class="mini-click-non">Report </span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('securitydept/search-by-po-no') ? 'active' : '' }}">
                            <a href="{{ route('search-by-po-no') }}">
                                <i class="fa fa-search icon-wrap"></i>
                                <span class="mini-click-non">Search By PO No</span>
                            </a>
                        </li>

                       <li class="nav-item {{ request()->is('securitydept/list-gatepass') || request()->is('securitydept/edit-gatepass/*') || request()->is('securitydept/list-po-details/*') ? 'active' : '' }}">
                        <a class="nav-item" href="{{ route('list-gatepass') }}" aria-expanded="false">  <i class="fa fa-id-card icon-wrap"></i><span class="mini-click-non">List
                                    Gate
                                    Pass</span></a></li>
                        <li class="nav-item {{ request()->is('dispatchdept/list-dispatch-final-product-close') ? 'active' : '' }}">
                        <a class="nav-item" href="{{ route('list-dispatch-final-product-close') }}"
                                aria-expanded="false"> <i class="fa fa-lock icon-wrap"></i> <span
                                    class="mini-click-non">Closed PO List</span></a></li>

                        </li>
                     
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.QUALITY'))
                        <li class="nav-item {{ request()->is('quality/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('grn-report*') ? 'active' : '' }}">
                            <a href="{{ route('grn-report') }}">
                                  <i class="fa fa-chart-line icon-wrap"></i>
                                <span class="mini-click-non">Report </span>
                            </a>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-grn') }}" aria-expanded="false">  <i class="fa fa-file-invoice icon-wrap"></i><span
                                    class="mini-click-non">GRN
                                    Form</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ request()->is('quality/list-grn') || request()->is('quality/add-grn/*') ? 'active' : '' }}">
                                    <a href="{{ route('list-grn') }}"><i class="fa fa-clipboard-list icon-wrap"></i>
                                        <span class="mini-click-non">List GRN</span></a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('quality/list-material-sent-to-quality') || request()->is('quality/list-material-sent-to-quality-businesswise/*') ? 'active' : '' }}">                       
                            <a href="{{ route('list-material-sent-to-quality') }}">
                                 <i class="fa fa-truck-loading icon-wrap"></i>
                                <span class="mini-click-non">Material Sent to Store</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('storedept/list-rejected-chalan-updated') || request()->is('storedept/list-rejected-chalan-details/*') ? 'active' : '' }}">                       
                        <a href="{{ route('list-rejected-chalan-updated') }}">
                            <i class="fa fa-ban icon-wrap"></i> <span
                                    class="mini-click-non">List Rejected Chalan</span></a></li>
                        <li class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking')  || request()->is('storedept/list-grn-details-po-tracking/*') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                                <i class="fa fa-clipboard-check icon-wrap"></i>
                                <span class="mini-click-non">GRN List</span>
                            </a>
                        </li>

                          
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.STORE'))
                        <li class="nav-item {{ request()->is('storedept/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"> <i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
    <li>
                            <a class="has-arrow" href="{{ route('list-unit') }}" aria-expanded="false"><i class="fa fa-chart-pie icon-wrap"></i> <span class="mini-click-non">Report
                                </span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                               <li class="{{ Request::is('store-item-stock-list') ? 'active' : '' }}"><a href="{{ route('store-item-stock-list') }}"> <i class="fa fa-boxes icon-wrap"></i><span
                                                class="mini-click-non">Item Stock Report</span></a></li>    

                                 <li class="{{ Request::is('list-consumption-report') ? 'active' : '' }}"><a href="{{ route('list-consumption-report') }}"><i class="fa fa-chart-line icon-wrap"></i><span
                                                class="mini-click-non">Consumption Report</span></a></li>    
                                
                                 <li class="{{ Request::is('stock-daily-report') ? 'active' : '' }}"><a href="{{ route('stock-daily-report') }}"><i class="fa fa-chart-line icon-wrap"></i><span
                                                class="mini-click-non">Stock Item Report</span></a></li>    

                            </ul>
                        </li>
                         <li class="nav-item {{ request()->is('storedept/list-accepted-design-from-prod') || request()->is('storedept/list-accepted-design-from-prod-business-wise/*') || request()->is('storedept/need-to-create-req/*') || request()->is('storedept/edit-material-list-bom-wise-new-req/*') ? 'active' : '' }}">
                            <a href="{{ route('list-accepted-design-from-prod') }}" aria-expanded="false"><i class="fa fa-tasks icon-wrap"></i><span class="mini-click-non">All
                                    New Requirements</span></a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('storedept/list-material-sent-to-purchase') ? 'active' : '' }}">
                            <a href="{{ route('list-material-sent-to-purchase') }}">
                                <i class="fa fa-shopping-cart icon-wrap"></i>
                                <span class="mini-click-non">Material For Purchase</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('storedept/list-material-received-from-quality') || request()->is('storedept/list-grn-details-po-tracking/*') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality') }}">
                                <i class="fa fa-box-open icon-wrap"></i>
                                <span class="mini-click-non">Material Received From Quality</span>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                               <i class="fa fa-clipboard-list icon-wrap"></i><span class="mini-click-non">Tracking Material</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('storedept/list-rejected-chalan') || request()->is('storedept/add-rejected-chalan/*') ? 'active' : '' }}">
                        <a href="{{ route('list-rejected-chalan') }}"> <i class="fa fa-ban icon-wrap"></i><span class="mini-click-non">Create Rejected
                                    Chalan</span></a></li>
                           <li class="nav-item {{ request()->is('storedept/list-rejected-chalan-updated') || request()->is('storedept/list-rejected-chalan-details/*') ? 'active' : '' }}">           
                        <a href="{{ route('list-rejected-chalan-updated') }}"><i class="fa fa-list-alt icon-wrap"></i> <span
                                    class="mini-click-non">List Rejected Chalan</span></a></li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-unit') }}" aria-expanded="false"><i class="fa fa-database icon-wrap"></i><span class="mini-click-non">Master
                                </span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a href="{{ route('list-unit') }}"><i class="fa fa-ruler-combined icon-wrap"></i><span class="mini-click-non">List Unit</span></a>
                                </li>
                                <li><a href="{{ route('list-hsn') }}"><i class="fa fa-file-invoice icon-wrap"></i>
                                        <span class="mini-click-non">List HSN</span></a></li>
                                <li><a href="{{ route('list-group') }}"><i class="fa fa-object-group icon-wrap"></i>
                                        <span class="mini-click-non">List Group</span></a></li>
                                <li><a href="{{ route('list-rack') }}"><i class="fa fa-layer-group icon-wrap"></i> <span class="mini-click-non">List Rack</span></a>
                                </li>
                                <li><a href="{{ route('list-process') }}"><i class="fa fa-industry icon-wrap"></i> <span
                                            class="mini-click-non">List Process</span></a></li>
                                <li><a href="{{ route('list-accessories') }}"><i class="fa fa-cogs icon-wrap"></i>
                                        <span class="mini-click-non">List Accessories</span></a></li>
                                <li class="nav-item {{ request()->is('purchase/list-part-item') ? 'active' : '' }}">
                                    <a class="" href="{{ route('list-part-item') }}" aria-expanded="false"><i class="fa fa-tools icon-wrap"></i><span
                                            class="mini-click-non">Part Item</span></a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('storedept/list-inventory-material') || request()->is('storedept/add-product-stock/*') || request()->is('storedept/edit-product-stock/*') ? 'active' : '' }}">
                        <a href="{{ route('list-inventory-material') }}"> <i class="fa fa-warehouse icon-wrap"></i> <span
                                    class="mini-click-non">Inventory Material List</span></a></li>
                          <li class="nav-item {{ request()->is('storedept/list-delivery-chalan') || request()->is('storedept/show-delivery-chalan/*') || request()->is('storedept/edit-delivery-chalan/*') ? 'active' : '' }}">           
                        <a href="{{ route('list-delivery-chalan') }}"><i class="fa fa-truck icon-wrap"></i><span class="mini-click-non">Delivery Challan</span></a>
                        </li>
                         <li class="nav-item {{ request()->is('storedept/list-returnable-chalan') || request()->is('storedept/add-returnable-chalan/*') || request()->is('storedept/edit-returnable-chalan/*') || request()->is('storedept/show-returnable-chalan/*') ? 'active' : '' }}">           
                        <a href="{{ route('list-returnable-chalan') }}"><i class="fa fa-undo-alt icon-wrap"></i> <span
                                    class="mini-click-non">Returnable Challan</span></a></li>
                                    
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.INVENTORY'))
                        <li class="nav-item {{ request()->is('owner/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa big-icon fa-envelope icon-wrap"
                                    aria-hidden="true"></i> <span class="mini-click-non">Dashboard</span></a>
                        </li>

                        <li
                            class="nav-item {{ request()->is('storedept/list-accepted-design-from-prod') ? 'active' : '' }}">
                            <a href="{{ route('list-accepted-design-from-prod') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-list icon-wrap"></i> <span class="mini-click-non">All
                                    New Requirements</span></a>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-inventory-material') }}"
                                aria-expanded="false"><i class="fa big-icon fa-ban  icon-wrap"></i> <span
                                    class="mini-click-non">Inventory
                                </span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a href="{{ route('list-inventory-material') }}"><i
                                            class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Material List</span></a></li>


                            </ul>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HR'))
                        <li class="nav-item {{ request()->is('hr/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa-solid fa-user-group icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-users') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-user icon-wrap"></i> <span
                                    class="mini-click-non">Employee</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a href="{{ route('list-users') }}"> <i class="fa-solid fa-user-plus icon-wrap"></i> <span class="mini-click-non">Add
                                            Employee</span></a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('list-yearly-leave-management') }}"><i class="fa-solid fa-calendar-days icon-wrap"></i>  <span
                                    class="mini-click-non">Add Yearly Leave</span></a></li>



                        <li>
                            <a class="has-arrow" href="{{ route('list-leaves-acceptedby-hr') }}"
                                aria-expanded="false">  <i class="fa-solid fa-calendar-check icon-wrap"></i>  <span
                                    class="mini-click-non">Leave Management</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a href="{{ route('list-leaves-acceptedby-hr') }}"> <i class="fa-solid fa-envelope-open-text icon-wrap"></i> <span
                                            class="mini-click-non">Leave Request</span></a></li>

                                <li><a href="{{ route('list-leaves-approvedby-hr') }}"><i class="fa-solid fa-circle-check icon-wrap"></i> 
                                        <span class="mini-click-non">Leave Approved</span></a></li>

                                <li><a href="{{ route('list-leaves-not-approvedby-hr') }}"><i class="fa-solid fa-circle-xmark icon-wrap"></i> 
                                        <span class="mini-click-non">Leave Not Approved</span></a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('list-notice') ? 'active' : '' }}">
                            <a href="{{ route('list-notice') }}">
                                 <i class="fa-solid fa-bell icon-wrap"></i> 
                                <span class="mini-click-non">Add Notice</span>
                            </a>
                        </li>

                        <li><a href="{{ route('list-notice') }}">  <i class="fa-solid fa-bullhorn icon-wrap"></i> 
                                <span class="mini-click-non">Notice</span>
                            </a></li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.EMPOLYEE'))
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa big-icon fa-envelope icon-wrap"
                                    aria-hidden="true"></i> <span class="mini-click-non">Dashboard</span></a>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.FINANCE'))
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>

 <li class="{{ Request::is('list-fianance-report') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-fianance-report') }}" aria-expanded="false"><i class="fa fa-chart-line icon-wrap"></i><span
                                    class="mini-click-non">Report</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-fianance-report') ? 'active' : '' }}"><a
                                        href="{{ route('list-fianance-report') }}"> <i class="fa fa-list-alt icon-wrap"></i> <span
                                            class="mini-click-non">Product Submitted to Dispatch</span></a></li>
                                              <li class="{{ Request::is('list-vendor-payment-report') ? 'active' : '' }}"><a href="{{ route('list-vendor-payment-report') }}" aria-expanded="false"><i class="fa fa-users icon-wrap"></i><span
                                                class="mini-click-non">PO Payment Vendor List</span></a></li>
                               </ul>
                        </li>                        
                        <li class="nav-item {{ request()->is('list-sr-and-gr-genrated-business') ? 'active' : '' }}">
                            <a href="{{ route('list-sr-and-gr-genrated-business') }}">
                                  <i class="fa fa-wallet icon-wrap"></i>
                                <span class="mini-click-non">Need to check for Payment</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('list-po-sent-for-approval') ? 'active' : '' }}">
                            <a href="{{ route('list-po-sent-for-approval') }}">
                                <i class="fa fa-file-invoice-dollar icon-wrap"></i>
                                <span class="mini-click-non">PO Submited For Sanction For Payment</span>
                            </a>
                        </li>


                        <li
                            class="nav-item {{ request()->is('list-po-sanction-and-need-to-do-payment-to-vendor') ? 'active' : '' }}">
                            <a href="{{ route('list-po-sanction-and-need-to-do-payment-to-vendor') }}">
                               <i class="fa fa-hand-holding-usd icon-wrap"></i>
                                <span class="mini-click-non">PO Payment Needs to Be Released</span>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('owner/list-release-approval-payment-by-vendor') ? 'active' : '' }}">
                            <a href="{{ route('list-release-approval-payment-by-vendor') }}"
                                aria-expanded="false"><i class="fa fa-coins icon-wrap"></i><span class="mini-click-non">PO Payment Release to Vendor
                                    By Fianance</span></a>
                        </li>
                        
                         <li class="nav-item {{ request()->is('financedept/recive-logistics-list') ? 'active' : '' }}">           
                            <a href="{{ route('recive-logistics-list') }}">
                               <i class="fa fa-clipboard-list icon-wrap"></i>
                                <span class="mini-click-non">Receive Logistics List</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('financedept/list-send-to-dispatch') ? 'active' : '' }}">  
                            <a href="{{ route('list-send-to-dispatch') }}">
                                 <i class="fa fa-truck icon-wrap"></i>
                                <span class="mini-click-non">Product Submited to Dispatch</span>
                            </a>
                        </li>
                          
                        
                    @endif


                    @if (session()->get('role_id') == config('constants.ROLE_ID.LOGISTICS'))
                        <li class="nav-item {{ request()->is('logisticsdept/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"> <i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
                         <li class="nav-item {{ request()->is('list-logistics-report*') ? 'active' : '' }}">
                            <a href="{{ route('list-logistics-report') }}">
                                <i class="fa fa-file-alt icon-wrap"></i>
                                <span class="mini-click-non">Report </span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('logisticsdept/list-final-production-completed-recive-to-logistics') || request()->is('logisticsdept/add-logistics/*') ? 'active' : '' }}">
                            <a href="{{ route('list-final-production-completed-recive-to-logistics') }}">
                                 <i class="fa fa-clipboard-check icon-wrap"></i>
                                <span class="mini-click-non">Production Completed</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('logisticsdept/list-logistics') ? 'active' : '' }}">                        
                            <a href="{{ route('list-logistics') }}">
                                <i class="fa fa-list-ul icon-wrap"></i>
                                <span class="mini-click-non">List Logistics</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('logisticsdept/list-send-to-fianance-by-logistics') ? 'active' : '' }}">   
                            <a href="{{ route('list-send-to-fianance-by-logistics') }}">
                              <i class="fa fa-paper-plane icon-wrap"></i>
                                <span class="mini-click-non">Submited to Fianance</span>
                            </a>
                        </li>
                         <li class="nav-item {{ request()->is('logisticsdept/list-vehicle-type') || request()->is('logisticsdept/add-vehicle-type/*') || request()->is('logisticsdept/edit-vehicle-type/*') ? 'active' : '' }}">   
                            <a class="" href="{{ route('list-vehicle-type') }}" aria-expanded="false"><i class="fa fa-truck-moving icon-wrap"></i><span class="mini-click-non">Vehicle
                                    Type</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('logisticsdept/list-transport-name') || request()->is('logisticsdept/add-transport-name') || request()->is('logisticsdept/edit-transport-name/*') ? 'active' : '' }}">   
                            <a class="" href="{{ route('list-transport-name') }}" aria-expanded="false"><i class="fa fa-shipping-fast icon-wrap"></i><span class="mini-click-non">Transport
                                    Name</span></a>
                        </li>
                       
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.DISPATCH'))
                        <li class="nav-item {{ request()->is('dispatchdept/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>
                        <li class="{{ Request::is('list-dispatch-report') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-dispatch-report') }}" aria-expanded="false"><i class="fa fa-chart-line icon-wrap"></i><span
                                    class="mini-click-non">Report</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-dispatch-report') ? 'active' : '' }}"><a
                                        href="{{ route('list-dispatch-report') }}"> <i class="fa fa-list-alt icon-wrap"></i> <span
                                            class="mini-click-non">Completed Dispatch Report</span></a></li>
                                              <li class="{{ Request::is('dispatch-pending-report') ? 'active' : '' }}"><a href="{{ route('dispatch-pending-report') }}" aria-expanded="false"><i class="fa fa-users icon-wrap"></i><span
                                                class="mini-click-non">Pending Dispatch Report</span></a></li>
                               </ul>
                        </li>                          
                        <li class="nav-item {{ request()->is('dispatchdept/list-final-production-completed-received-from-fianance') || request()->is('dispatchdept/add-dispatch/*') ? 'active' : '' }}">
                            <a href="{{ route('list-final-production-completed-received-from-fianance') }}">
                                <i class="fa fa-money-bill-wave icon-wrap"></i>
                                <span class="mini-click-non">Received From Finance</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('dispatchdept/list-dispatch') ? 'active' : '' }}">
                            <a href="{{ route('list-dispatch') }}">
                                <i class="fa fa-truck-loading icon-wrap"></i>
                                <span class="mini-click-non">Product Dispatch Quantity Wise </span>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ request()->is('dispatchdept/list-dispatch-final-product-close') ? 'active' : '' }}">
                            <a href="{{ route('list-dispatch-final-product-close') }}">
                                <i class="fa fa-clipboard-check icon-wrap"></i>
                                <span class="mini-click-non">Product Dispatch Completed (Close Product)</span>
                            </a>
                        </li>
                       
                        
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.CMS'))
                        <li class="nav-item {{ request()->is('cms/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">  <i class="fa fa-tachometer-alt icon-wrap"></i><span class="mini-click-non">Dashboard</span></a>
                        </li>

                             <li class="nav-item {{ request()->is('cms/list-product') || request()->is('cms/add-product') || request()->is('cms/show-product') || request()->is('cms/edit-product/*')  ? 'active' : '' }}">                            
                            <a href="{{ route('list-product') }}" aria-expanded="false"> <i class="fa fa-cubes icon-wrap"></i> <span
                                    class="mini-click-non">Product</span></a></li>
                      <li class="nav-item {{ request()->is('cms/list-services') || request()->is('cms/add-services') || request()->is('cms/show-services') || request()->is('cms/edit-services/*')  ? 'active' : '' }}">   
                            <a href="{{ route('list-services') }}" aria-expanded="false"> <i class="fa fa-concierge-bell icon-wrap"></i> <span
                                    class="mini-click-non">Services</span></a></li>

                        <li class="nav-item {{ request()->is('cms/list-testimonial') || request()->is('cms/add-testimonial') || request()->is('cms/show-testimonial') || request()->is('cms/edit-testimonial/*')  ? 'active' : '' }}">   
                            <a href="{{ route('list-testimonial') }}" aria-expanded="false"><i class="fa fa-comment-dots icon-wrap"></i> <span
                                    class="mini-click-non">Testimonial</span></a></li>

                       <li class="nav-item {{ request()->is('cms/list-director-desk') || request()->is('cms/add-director-desk') || request()->is('cms/show-director-desk') || request()->is('cms/edit-director-desk/*')  ? 'active' : '' }}">   
                            <a href="{{ route('list-director-desk') }}" aria-expanded="false"><i class="fa fa-briefcase icon-wrap"></i><span
                                    class="mini-click-non">Director Desk</span></a></li>
                       <li class="nav-item {{ request()->is('cms/list-vision-mission') || request()->is('cms/add-vision-mission') || request()->is('cms/show-vision-mission') || request()->is('cms/edit-vision-mission/*')  ? 'active' : '' }}">   
                            <a href="{{ route('list-vision-mission') }}" aria-expanded="false"><i class="fa fa-bullseye icon-wrap"></i><span
                                    class="mini-click-non">Vision Mission</span></a></li>
                        <li class="nav-item {{ request()->is('cms/list-team') || request()->is('cms/add-team') || request()->is('cms/show-team') || request()->is('cms/edit-team/*')  ? 'active' : '' }}">   
                            <a href="{{ route('list-team') }}" aria-expanded="false"><i class="fa fa-users icon-wrap"></i><span
                                    class="mini-click-non">Team</span></a></li>

                        <li class="nav-item {{ request()->is('cms/list-contactus-form') || request()->is('cms/show-contactus-form') ? 'active' : '' }}">   
                            <a href="{{ route('list-contactus-form') }}" aria-expanded="false"><i class="fa fa-envelope-open-text icon-wrap"></i><span
                                    class="mini-click-non">Contact Us Form </span></a></li>
                        <!-- </ul>
                    </li>                    -->
                    @endif
                    @if (session()->get('user_id'))
                    {{-- @if (session()->get('role_id') != config('constants.ROLE_ID.CMS')) --}}
                    @if (
                        session()->get('role_id') != config('constants.ROLE_ID.CMS') &&
                        session()->get('role_id') != config('constants.ROLE_ID.SUPER')
                    )
                    
                    {{-- <li class="nav-item">
                            <a class="nav-item" href="{{ route('list-leaves') }}" aria-expanded="false">
                                 <i class="fa-solid fa-calendar-days icon-wrap"></i>
                                <span class="mini-click-non">Leaves Request</span>
                            </a>
                            <ul class="nav-item" aria-expanded="false">
                                <li>
                                    <a href="{{ route('list-leaves') }}">
                                        <i class="fa-solid fa-plus-circle icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">Add Leaves Request</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('particular-notice-department-wise') ? 'active' : '' }}">
                            <a href="{{ route('particular-notice-department-wise') }}">
                                <i class="fa-solid fa-bullhorn icon-wrap"></i> 
                                <span class="mini-click-non">Notice</span>
                            </a>
                        </li> --}}
                    @endif
                @endif

                
                    @if (session()->get('user_id'))
                        <li class="nav-item">
                            <a class="nav-item" href="{{ route('list-leaves') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-paper-plane icon-wrap"></i> <span
                                    class="mini-click-non">Leaves
                                    Request</span></a>
                            <ul class="nav-item" aria-expanded="false">
                                <li><a href="{{ route('list-leaves') }}"><i
                                            class="fa big-icon fa-calendar icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Add Leaves Request</span></a></li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->is('particular-notice-department-wise') ? 'active' : '' }}">
                            <a href="{{ route('particular-notice-department-wise') }}">
                                <i class="fa big-icon fa-bell  icon-wrap"></i>
                                <span class="mini-click-non">Notice</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </nav>
</div>
<div class="all-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="logo-pro">
                    <a href="{{ route('login') }}"><img class="main-logo"
                            src="{{ asset('img/logo/logo_updated.png') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-advance-area">
        <div class="header-top-area">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper">
                            <div class="row">
                                <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                    <div class="menu-switcher-pro">
                                        <!-- ============= pratiksha (21/08/24) ============= change for sidebars btn  -->
                                        <button type="button" id="sidebarCollapse"
                                            class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn navbar-btn-wb">
                                            <i class="fa fa-bars"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                    <div class="header-top-menu tabl-d-n">
                                        <ul class="nav navbar-nav mai-top-nav">

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <div class="header-right-info">
                                        <ul class="nav navbar-nav mai-top-nav header-right-menu">

                                            @if (
                                                session()->get('role_id') != config('constants.ROLE_ID.CMS') &&
                                                session()->get('role_id') != config('constants.ROLE_ID.SUPER')
                                            )
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button"
                                                    aria-expanded="false" class="nav-link dropdown-toggle"><i
                                                        class="fa fa-bell" style="padding-right: 32px;"></i></i><span
                                                        class="satish"
                                                        style="position: fixed; top: 9px;translate: -149%;"
                                                        id="notification-count"></span>
                                                </a>

                                                <div role="menu"
                                                    class="notification-author dropdown-menu animated zoomIn">
                                                    <div class="notification-single-top"
                                                        style="background-color: linear-gradient(178deg, #175CA2 0%, #121416 100%)">
                                                        <h1 style="color: #fff; ">Notifications</h1>
                                                    </div>
                                                    <ul class="notification-menu" id="notification-messages"
                                                        style="background-color:#fff;">
                                                        <li>
                                                        </li>
                                                    </ul>
                                                    <div class="notification-view">


                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            <li class="nav-item">
                                                {{-- <a href="#" data-toggle="dropdown" role="button"
                                                    aria-expanded="false" class="nav-link dropdown-toggle"
                                                    style="font-size: 16px !important;">
                                                    <i class="fa fa-user adminpro-user-rounded header-riht-inf"
                                                        aria-hidden="true"></i>
                                                    {{ ucwords(config('constants.ROLE_ID_NAME.' . Session::get('role_id'))) }}
                                                    Department
                                                    <span class="admin-name"></span>
                                                    <i class="fa fa-angle-down adminpro-icon adminpro-down-arrow"></i>
                                                </a>
                                                <ul role="menu"
                                                    class="dropdown-header-top author-log dropdown-menu animated zoomIn">

                                                    <li><a href="{{ route('log-out') }}"><span
                                                                class="fa fa-lock author-log-ic"></span>
                                                            Log Out</a>
                                                    </li>
                                                </ul> --}}

                                                <a href="#" data-bs-toggle="dropdown" role="button"
   aria-expanded="false" class="nav-link dropdown-toggle"
   style="font-size: 16px !important;">
    <i class="fa fa-user adminpro-user-rounded header-riht-inf"
       aria-hidden="true"></i>
    {{ ucwords(config('constants.ROLE_ID_NAME.' . Session::get('role_id'))) }}
    Department
    <span class="admin-name"></span>
</a>
<ul class="dropdown-menu animated zoomIn">
    <li>
        <a href="{{ route('log-out') }}">
            <span class="fa fa-lock author-log-ic"></span> Log Out
        </a>
    </li>
</ul>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
