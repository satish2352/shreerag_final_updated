<style>
/* ============= pratiksha (21/08/24) ============= */
.navbar-btn-wb{
    color: #fff!important;
    border: 1px solid #fff!important;
}
.notification-menu{
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
</style>
<!-- ============= pratiksha (21/08/24) ============= change for sidebar changes and change icon -->

<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <img class="main-logo1"
            src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}"  style="height: 3.9rem!important;" alt="">
                <a href="{{ route('login') }}"><img class="main-logo"
                        src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}" alt=""></a>
                <!-- <strong><img src="{{ asset('img/logo/logo_updated.png') }}" alt="" ></strong> -->
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                   

                
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SUPER'))
                    <li class="nav-item {{ request()->is('/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>
                        <li
                            class="{{ Request::is('list-organizations', 'organizations-list-employees', 'list-departments', 'list-roles') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-organizations') }}" aria-expanded="false">
                                <i class="fa big-icon fa-building icon-wrap"></i>
                                <span class="mini-click-non">Organizations</span>
                            </a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-organizations') ? 'active' : '' }}">
                                    <a  href="{{ route('list-organizations') }}">
                                        <i class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Organizations</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('organizations-list-employees') ? 'active' : '' }}">
                                    <a  href="{{ route('organizations-list-employees') }}">
                                        <i class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Employees</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('list-departments') ? 'active' : '' }}">
                                    <a  href="{{ route('list-departments') }}">
                                        <i class="fa big-icon fa-sitemap  icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Departments</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('list-roles') ? 'active' : '' }}">
                                    <a  href="{{ route('list-roles') }}">
                                        <i class="fa big-icon fa-user-tag icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Roles</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                        <ul class="sidebar-menu" id="nav-accordion">
                            <li class="nav-item {{ request()->is('owner/dashboard') ? 'active' : '' }}">
                                <a  href="{{ route('dashboard') }}"><i
                                class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                class="mini-click-non">Dashboard</span></a></li>
        

                            <li>
                                <a  href="{{ route('list-users') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Add Employees</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-business') ? 'active' : '' }}">
                                <a  href="{{ route('list-business') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-briefcase icon-wrap"></i> <span
                                        class="mini-click-non">Business</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-forwarded-to-design') ? 'active' : '' }}">
                                <a  href="{{ route('list-forwarded-to-design') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-paper-plane icon-wrap"></i> <span
                                        class="mini-click-non">Business Sent For Design</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-design-uploaded-owner') ? 'active' : '' }}">
                                <a href="{{ route('list-design-uploaded-owner') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-check icon-wrap"></i> <span
                                        class="mini-click-non">Design Received For Production</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-design-correction') ? 'active' : '' }}">
                                <a href="{{ route('list-design-correction') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-check icon-wrap"></i> <span
                                        class="mini-click-non">Design Received For Design Correction</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/material-ask-by-prod-to-store') ? 'active' : '' }}">
                                <a href="{{ route('material-ask-by-prod-to-store') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-warehouse  icon-wrap"></i> <span
                                        class="mini-click-non">Material Ask By Production To Store</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/material-ask-by-store-to-purchase') ? 'active' : '' }}">
                                <a href="{{ route('material-ask-by-store-to-purchase') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-warehouse icon-wrap"></i> <span
                                        class="mini-click-non">Purchase Material Ask By Store To Purchase</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-purchase-orders') ? 'active' : '' }}">
                                <a href="{{ route('list-purchase-orders') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-file-invoice icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Purchase order need to check</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-approved-purchase-orders-owner') ? 'active' : '' }}">
                                <a href="{{ route('list-approved-purchase-orders-owner') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-check icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Purchase Order Approved</span></a>
                            </li>
                            <li
                                class="nav-item {{ request()->is('owner/list-owner-submited-po-to-vendor') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('list-owner-submited-po-to-vendor') }}"><i
                                        class="fa big-icon fa-user-tag icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Submitted PO by Vendor</span></a>
                            </li>
                            <li class="nav-item {{ request()->is('owner/list-owner-gatepass') ? 'active' : '' }}">
                            <a href="{{ route('list-owner-gatepass') }}"><i
                                    class="fa big-icon fa-shield-alt icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">Security Created Gate Pass</span></a>
                            </li>
                            <li
                            class="nav-item {{ request()->is('owner/list-owner-grn') ? 'active' : '' }}">
                            <a href="{{ route('list-owner-grn') }}"><i
                                    class="fa big-icon fa-clipboard-check icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">Material Received for GRN Generate</span></a>
                             </li>

                             <li
                             class="nav-item {{ request()->is('owner/list-material-sent-to-store-generated-grn') ? 'active' : '' }}">
                             <a href="{{ route('list-material-sent-to-store-generated-grn') }}"><i
                                     class="fa big-icon fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                     class="mini-click-non">Generated GRN Material send Quality Dept to Store </span></a>
                         </li>
                        
                         <li
                         class="nav-item {{ request()->is('owner/list-owner-material-recived-from-store') ? 'active' : '' }}">
                         <a href="{{ route('list-owner-material-recived-from-store') }}"><i
                                 class="fa fa-boxes icon-wrap" aria-hidden="true"></i> <span
                                 class="mini-click-non">Store Dept Material send to Production Dept</span></a>
                         </li>
                         <li
                         class="nav-item {{ request()->is('owner/list-owner-final-production-completed') ? 'active' : '' }}">
                         <a href="{{ route('list-owner-final-production-completed') }}"><i
                                 class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                 class="mini-click-non">Production Department Completed Production</span></a>
                          </li>
                     <li
                     class="nav-item {{ request()->is('owner/list-owner-final-production-completed-recive-to-logistics') ? 'active' : '' }}">
                     <a href="{{ route('list-owner-final-production-completed-recive-to-logistics') }}"><i
                             class="fa fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                             class="mini-click-non">Logistics Dept Received Product completed list</span></a>
                      </li>

                      <li
                     class="nav-item {{ request()->is('owner/recive-owner-logistics-list') ? 'active' : '' }}">
                     <a href="{{ route('recive-owner-logistics-list') }}"><i
                             class="fa fa-receipt  icon-wrap" aria-hidden="true"></i> <span
                             class="mini-click-non">Fianance Dept Production Recevied from Logistics Dept</span></a>
                      </li>

                      <li
                      class="nav-item {{ request()->is('owner/list-owner-send-to-dispatch') ? 'active' : '' }}">
                      <a href="{{ route('list-owner-send-to-dispatch') }}"><i
                              class="fa fa-paper-plane icon-wrap" aria-hidden="true"></i> <span
                              class="mini-click-non">Fianance Dept Production Request Send to Dispatch Dept</span></a>
                       </li>
                     
                       <li
                       class="nav-item {{ request()->is('owner/list-product-dispatch-completed') ? 'active' : '' }}">
                       <a href="{{ route('list-product-dispatch-completed') }}"><i
                               class="fa fa-truck icon-wrap" aria-hidden="true"></i> <span
                               class="mini-click-non">Dispatch Dept Production Dispatch Completed</span></a>
                   </li> 
                            <li
                            class="nav-item {{ Request::is('list-rejected-chalan-po-wise') ? 'active' : '' }}">
                            <a href="{{ route('list-rejected-chalan-po-wise') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-times-circle icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">PO wise Rejected Chalan</span></a>
                        </li>
                           <li
                            class="nav-item {{ request()->is('owner/list-approved-purchase-orders-owner') ? 'active' : '' }}">
                            <a href="{{ route('list-approved-purchase-orders-owner') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-rupee-sign icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">Fianance Dept Received GRN and SR</span></a>
                        </li> 
                            <li
                                class="nav-item {{ request()->is('owner/list-po-recived-for-approval-payment') ? 'active' : '' }}">
                                <a href="{{ route('list-po-recived-for-approval-payment') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-file-invoice icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">PO Payment Release Request</span></a>
                            </li>
                           
                            
                            <li><a href="{{ route('list-rules-regulations') }}" aria-expanded="false"><i
                                class="fa big-icon fa-file-alt  icon-wrap" aria-hidden="true"></i> <span
                                class="mini-click-non">Rules and Regulations</span></a></li>
                            <li>
                                <li>
                                    <a class="has-arrow" href="{{ route('list-product-completed-report') }}" aria-expanded="false"><i
                                            class="fa fa-check-circle icon-wrap"></i> <span class="mini-click-non">Report
                                            </span></a>
                                    <ul class="submenu-angle" aria-expanded="false">
                                        <li><a  href="{{ route('list-product-completed-report') }}"><i
                                                    class="fa fa-check-circle icon-wrap" aria-hidden="true"></i> <span
                                                    class="mini-click-non">List Product Completed</span></a></li>
                                    </ul>
                                </li>
                                                        </li>
                                {{-- <li class="nav-item {{ Request::is('list-roles') ? 'active' : '' }}">
                                    <a href="{{ route('list-roles') }}" aria-expanded="false">
                                        <i class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i>
                                        <span class="mini-click-non">List Roles</span>
                                    </a>
                                </li> --}}
                        </ul>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PURCHASE'))
                    <li class="nav-item {{ request()->is('purchase/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>

                        <li class="{{ Request::is('list-purchase') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-purchase') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-file-invoice icon-wrap"></i> <span
                                    class="mini-click-non">Purchase Orders</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-purchase') ? 'active' : '' }}"><a
                                         href="{{ route('list-purchase') }}"><i
                                            class="fa big-icon fa-list-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Purchase Orders To Be Finalize</span></a></li>
                                            
                            </ul>
                        </li>
                        <li class="nav-item {{ Request::is('list-vendor') ? 'active' : '' }}"><a
                            href="{{ route('list-vendor') }}"><i
                               class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i> <span
                               class="mini-click-non">Vendor List</span></a></li>
                        {{-- <li class="{{ Request::is('list-vendor') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-vendor') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-users icon-wrap"></i> <span
                                    class="mini-click-non">Vendor</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-vendor') ? 'active' : '' }}"><a
                                         href="{{ route('list-vendor') }}"><i
                                            class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Vendor List</span></a></li>
                            </ul>
                        </li> --}}
                        <li class="nav-item {{ request()->is('purchase/list-tax') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-tax') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-receipt icon-wrap"></i> <span
                                    class="mini-click-non">Tax</span></a>
                        </li>
                        <li class="{{ Request::is('list-approved-purchase-orders') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-approved-purchase-orders') }}"
                                aria-expanded="false"><i class="fa big-icon fa-file-signature icon-wrap"></i> <span
                                    class="mini-click-non">Purchase Order Status</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li
                                    class="nav-item {{ request()->is('purchase/list-purchase-orders-sent-to-owner') ? 'active' : '' }}">
                                    <a href="{{ route('list-purchase-orders-sent-to-owner') }}"
                                        aria-expanded="false"><i class="fa big-icon fa-paper-plane icon-wrap"></i> <span
                                            class="mini-click-non">Purchase Order Submited For Approval</span></a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('list-approved-purchase-orders') ? 'active' : '' }}">
                                    <a  href="{{ route('list-approved-purchase-orders') }}" aria-expanded="false"><i
                                            class="fa big-icon fa-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Purchase Order Approved</span></a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('list-purchase-order-approved-sent-to-vendor') ? 'active' : '' }}">
                                    <a 
                                        href="{{ route('list-purchase-order-approved-sent-to-vendor') }}"><i
                                            class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
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
                        <li class="nav-item {{ request()->is('purchase/list-submited-po-to-vendor') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-submited-po-to-vendor') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-check-circle icon-wrap"></i> <span
                                    class="mini-click-non">Submited PO by Vendor List</span></a>
                        </li>
                        <li
                        class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking') ? 'active' : '' }}">
                        <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                            <i class="fa fa-clipboard-list icon-wrap"></i>
                            <span class="mini-click-non">Material Received PO Tracking</span>
                        </a>
                    </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.DESIGNER'))
                    <li class="nav-item {{ request()->is('designdept/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>
                        <li 
                            class="nav-item {{ request()->is('designdept/list-new-requirements-received-for-design') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-new-requirements-received-for-design') }}"
                                aria-expanded="false"><i class="fa big-icon fa-briefcase   icon-wrap"></i> <span
                                    class="mini-click-non">New Business<br> Received For Design</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-design-upload') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-paper-plane icon-wrap"></i> <span
                                    class="mini-click-non">Designs Sent To Production</span></a>
                        </li>

                        <li class="nav-item {{ request()->is('designdept/list-reject-design-from-prod') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-reject-design-from-prod') }}"
                                aria-expanded="false"><i class="fa big-icon fa-ban  icon-wrap"></i> <span
                                    class="mini-click-non">Rejected Design List</span></a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-accept-design-by-production*') ? 'active' : '' }}">
                            <a href="{{ route('list-accept-design-by-production') }}">
                                <i class="fa fa-check-circle icon-wrap"></i>
                                <span class="mini-click-non">Accepted Design List</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('designdept/list-design-report*') ? 'active' : '' }}">
                            <a href="{{ route('list-design-report') }}">
                                <i class="fa fa-check-circle icon-wrap"></i>
                                <span class="mini-click-non">Design Report List</span>
                            </a>
                        </li>

                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Designs</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-design-upload') }}"><i
                                            class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Designs</span></a></li>
                            </ul>
                        </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PRODUCTION'))

                        <ul class="sidebar-menu">
                            <li class="nav-item {{ request()->is('proddept/dashboard') ? 'active' : '' }}">
                                <a  href="{{ route('dashboard') }}"><i
                                class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                class="mini-click-non">Dashboard</span></a></li>

                            <li
                                class="nav-item {{ request()->is('proddept/list-new-requirements-received-for-production') ? 'active' : '' }}">
                                <a href="{{ route('list-new-requirements-received-for-production') }}">
                                    <i class="fa big-icon fa-files-o icon-wrap"></i>
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
                                    <i class="fa fa-ban  icon-wrap"></i>
                                    <span class="mini-click-non">Rejected Design List</span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('proddept/list-revised-design') ? 'active' : '' }}">
                                <a href="{{ route('list-revised-design') }}">
                                    <i class="fa fa-list-alt icon-wrap"></i>
                                    <span class="mini-click-non">Revised Design List</span>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('proddept/list-material-recived') ? 'active' : '' }}">
                                <a href="{{ route('list-material-recived') }}">
                                    <i class="fa fa-box   icon-wrap"></i>
                                    <span class="mini-click-non">Material Received For Production</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('proddept/list-final-production-completed') ? 'active' : '' }}">
                                <a href="{{ route('list-final-production-completed') }}">
                                    <i class="fa fa-check-circle icon-wrap"></i>
                                    <span class="mini-click-non">Production Completed</span>
                                </a>
                            </li>
                        </ul>

                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-purchases') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Purchase</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-purchases') }}"><i
                                            class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Purchase</span></a></li>
                            </ul>
                        </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SECURITY'))
                        <li>
                        <li class="nav-item {{ request()->is('search-by-po-no') ? 'active' : '' }}">
                            <a href="{{ route('search-by-po-no') }}">
                                <i class="fa fa-search icon-wrap"></i>
                                <span class="mini-click-non">Search By PO No</span>
                            </a>
                        </li>
{{-- 
                        <li
                        class="nav-item {{ Request::is('list-purchase-order-approved-sent-to-vendor-security') ? 'active' : '' }}">
                        <a 
                            href="{{ route('list-purchase-order-approved-sent-to-vendor') }}"><i
                                class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                class="mini-click-non">Purchase Order Sent To Vendor</span></a>
                    </li> --}}

                        <li><a class="nav-item" href="{{ route('list-gatepass') }}" aria-expanded="false"><i
                                class="fa big-icon fa-id-badge icon-wrap"></i> <span class="mini-click-non">List Gate
                                Pass</span></a></li>
                                <li><a class="nav-item" href="{{ route('list-dispatch-final-product-close') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-id-badge icon-wrap"></i> <span class="mini-click-non">Closed PO List</span></a></li>
                        {{-- <ul class="submenu-angle" aria-expanded="false">
                            <li><a  href="{{ route('list-gatepass') }}"><i
                                        class="fa big-icon fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">List Gate Pass</span></a></li>
                        </ul> --}}
                        </li>
                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-security-remark') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-edit icon-wrap"></i> <span
                                    class="mini-click-non">Remark</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-security-remark') }}"><i
                                            class="fa big-icon fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Remark</span></a></li>
                            </ul>
                        </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.QUALITY'))
                    <li class="nav-item {{ request()->is('quality/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-grn') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-file-invoice  icon-wrap"></i> <span class="mini-click-non">GRN
                                    Form</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-grn') }}"><i
                                            class="fa big-icon fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List GRN</span></a></li>
                            </ul>
                        </li>
                       

                        <li class="nav-item {{ request()->is('list-material-sent-to-quality') ? 'active' : '' }}">
                            <a href="{{ route('list-material-sent-to-quality') }}">
                                <i class="fa big-icon fa-truck  icon-wrap"></i>
                                <span class="mini-click-non">Material Sent to Store</span>
                            </a>
                        </li>
                        <li><a  href="{{ route('list-rejected-chalan-updated') }}"><i
                            class="fa big-icon fa-ban  icon-wrap" aria-hidden="true"></i> <span
                            class="mini-click-non">List Rejected Chalan</span></a></li>
                            <li
                            class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                                <i class="fa fa-clipboard-list icon-wrap"></i>
                                <span class="mini-click-non">GRN List</span>
                            </a>
                        </li>
                        {{-- <li
                                class="nav-item {{ Request::is('list-rejected-chalan-po-wise') ? 'active' : '' }}">
                                <a 
                                    href="{{ route('list-rejected-chalan-po-wise') }}"><i
                                        class="fa big-icon fa-times-circle  icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">PO wise Rejected Chalan</span></a>
                            </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.STORE'))
                    
                    <li class="nav-item {{ request()->is('storedept/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>

                        <li class="nav-item {{ request()->is('storedept/list-accepted-design-from-prod') ? 'active' : '' }}">
                            <a href="{{ route('list-accepted-design-from-prod') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-list icon-wrap"></i> <span class="mini-click-non">All
                                    New Requirements</span></a>
                        </li>

                        <li class="nav-item {{ request()->is('storedept/list-material-sent-to-prod') ? 'active' : '' }}">
                            <a href="{{ route('list-material-sent-to-prod') }}">
                                <i class="fa big-icon fa-paper-plane icon-wrap"></i>
                                <span class="mini-click-non">Requirements Sent To Production</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('storedept/list-material-sent-to-purchase') ? 'active' : '' }}">
                            <a href="{{ route('list-material-sent-to-purchase') }}">
                                <i class="fa big-icon fa-shopping-cart icon-wrap"></i>
                                <span class="mini-click-non">Material For Purchase</span>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->is('storedept/list-material-received-from-quality') ? 'active' : '' }}">
                            <a href="{{ route('list-material-received-from-quality') }}">
                                <i class="fa big-icon fa-box-open icon-wrap"></i>
                                <span class="mini-click-non">Material Received From Quality</span>
                            </a>
                        </li>
                        <li
                        class="nav-item {{ request()->is('storedept/list-material-received-from-quality-po-tracking') ? 'active' : '' }}">
                        <a href="{{ route('list-material-received-from-quality-po-tracking') }}">
                            <i class="fa fa-clipboard-list icon-wrap"></i>
                            <span class="mini-click-non">Material Received PO Tracking</span>
                        </a>
                    </li>
                        {{-- <li
                        class="nav-item {{ request()->is('storedept/list-product-inprocess-received-from-production') ? 'active' : '' }}">
                        <a href="{{ route('list-product-inprocess-received-from-production') }}">
                            <i class="fa big-icon fa-box-open icon-wrap"></i>
                            <span class="mini-click-non">Production Department send material list</span>
                        </a>
                    </li> --}}
                    <li><a  href="{{ route('list-rejected-chalan') }}"><i
                        class="fa big-icon fa-ban  icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Create Rejected Chalan</span></a></li>
                        <li><a  href="{{ route('list-rejected-chalan-updated') }}"><i
                            class="fa big-icon fa-ban  icon-wrap" aria-hidden="true"></i> <span
                            class="mini-click-non">List Rejected Chalan</span></a></li>
                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-rejected-chalan') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-ban  icon-wrap"></i> <span class="mini-click-non">Rejected Chalan
                                    </span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-rejected-chalan') }}"><i
                                            class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Rejected Chalan</span></a></li>
                            </ul>
                        </li> --}}


                        <li>
                            <a class="has-arrow" href="{{ route('list-unit') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-list icon-wrap"></i> <span class="mini-click-non">Master
                                    </span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-unit') }}"><i
                                            class="fa fa-receipt  icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Unit</span></a></li>
                                <li><a  href="{{ route('list-hsn') }}"><i
                                    class="fa big-icon fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">List HSN</span></a></li>
                                    <li><a  href="{{ route('list-group') }}"><i
                                        class="fa big-icon fa-clipboard-check icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">List Group</span></a></li>
                                        <li><a  href="{{ route('list-rack') }}"><i
                                            class="fa big-icon fa-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">List Rack</span></a></li>
                                            <li><a  href="{{ route('list-process') }}"><i
                                                class="fa big-icon fa-warehouse  icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">List Process</span></a></li>
                                                <li><a  href="{{ route('list-accessories') }}"><i
                                                    class="fa big-icon fa-file-invoice icon-wrap" aria-hidden="true"></i> <span
                                                    class="mini-click-non">List Accessories</span></a></li>
                                                    <li class="nav-item {{ request()->is('purchase/list-part-item') ? 'active' : '' }}">
                                                        <a class="" href="{{ route('list-part-item') }}" aria-expanded="false"><i
                                                                class="fa big-icon fa-cogs icon-wrap"></i> <span
                                                                class="mini-click-non">Part Item</span></a>
                                                    </li>
                                        
                            </ul>
                        </li>
                        <li><a  href="{{ route('list-inventory-material') }}"><i
                            class="fa big-icon fa-box-open icon-wrap" aria-hidden="true"></i> <span
                            class="mini-click-non">Inventory Material List</span></a></li>
                        <li><a  href="{{ route('list-delivery-chalan') }}"><i
                            class="fa fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                            class="mini-click-non">Delivery Challan</span></a></li>
                            <li><a  href="{{ route('list-returnable-chalan') }}"><i
                                class="fa fa-clipboard-list icon-wrap" aria-hidden="true"></i> <span
                                class="mini-click-non">Returnable Challan</span></a></li>
                     @endif
                     @if (session()->get('role_id') == config('constants.ROLE_ID.INVENTORY'))
                     <li class="nav-item {{ request()->is('inventory/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>
                     <li class="nav-item {{ request()->is('storedept/list-accepted-design-from-prod') ? 'active' : '' }}">
                        <a href="{{ route('list-accepted-design-from-prod') }}" aria-expanded="false"><i
                                class="fa big-icon fa-list icon-wrap"></i> <span class="mini-click-non">All
                                New Requirements</span></a>
                    </li>
                    <li>
                        <a class="has-arrow" href="{{ route('list-inventory-material') }}" aria-expanded="false"><i
                                class="fa big-icon fa-ban  icon-wrap"></i> <span class="mini-click-non">Inventory
                                </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a  href="{{ route('list-inventory-material') }}"><i
                                        class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Material List</span></a></li>
                           
                                    
                        </ul>
                    </li>
                    <li
                    class="nav-item {{ request()->is('storedept/list-product-inprocess-received-from-production') ? 'active' : '' }}">
                    <a href="{{ route('list-product-inprocess-received-from-production') }}">
                        <i class="fa big-icon fa-box-open icon-wrap"></i>
                        <span class="mini-click-non">Production Department send material list</span>
                    </a>
                </li>
                     @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HR'))
                    <li class="nav-item {{ request()->is('hr/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-users') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-user icon-wrap"></i> <span
                                    class="mini-click-non">Employee</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-users') }}"><i
                                            class="fa big-icon fa-users icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Add Employee</span></a></li>
                            </ul>
                        </li>
                        <li><a  href="{{ route('list-yearly-leave-management') }}"><i
                                    class="fa big-icon fa-calendar-day icon-wrap" aria-hidden="true"></i> <span
                                    class="mini-click-non">Add Yearly Leave</span></a></li>



                        <li>
                            <a class="has-arrow" href="{{ route('list-leaves-acceptedby-hr') }}"
                                aria-expanded="false"><i class="fa big-icon fa-calendar-alt icon-wrap"></i> <span
                                    class="mini-click-non">Leave Management</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a  href="{{ route('list-leaves-acceptedby-hr') }}"><i
                                            class="fa big-icon fa-envelope  icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Leave Request</span></a></li>

                                <li><a  href="{{ route('list-leaves-approvedby-hr') }}"><i
                                            class="fa big-icon fa-calendar-check icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Leave Approved</span></a></li>

                                <li><a  href="{{ route('list-leaves-not-approvedby-hr') }}"><i
                                            class="fa big-icon fa-times-circle icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Leave Not Approved</span></a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('list-notice') ? 'active' : '' }}">
                            <a href="{{ route('list-notice') }}">
                                <i class="fa big-icon fa-bell  icon-wrap"></i>
                                <span class="mini-click-non">Add Notice</span>
                            </a>
                        </li>

                        <li><a href="{{ route('list-notice') }}">
                            <i class="fa big-icon fa-bell icon-wrap"></i>
                            <span class="mini-click-non">Notice</span>
                        </a></li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.EMPOLYEE'))
                    <li class="nav-item {{ request()->is('list-sr-and-gr-genrated-business') ? 'active' : '' }}">
                        <a href="{{ route('list-sr-and-gr-genrated-business') }}">
                            <i class="fa big-icon fa-money-check icon-wrap"></i>
                            <span class="mini-click-non">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="{{ route('list-inventory-material') }}" aria-expanded="false"><i
                                class="fa big-icon fa-ban  icon-wrap"></i> <span class="mini-click-non">Inventory
                                </span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a  href="{{ route('list-inventory-material') }}"><i
                                        class="fa big-icon fa-list icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Material List</span></a></li>
                           
                                    
                        </ul>
                    </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.FINANCE'))
                        <li class="nav-item {{ request()->is('list-sr-and-gr-genrated-business') ? 'active' : '' }}">
                            <a href="{{ route('list-sr-and-gr-genrated-business') }}">
                                <i class="fa big-icon fa-money-check icon-wrap"></i>
                                <span class="mini-click-non">Need to check for Payment</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('list-po-sent-for-approval') ? 'active' : '' }}">
                            <a href="{{ route('list-po-sent-for-approval') }}">
                                <i class="fa big-icon fa-file-signature icon-wrap"></i>
                                <span class="mini-click-non">PO Submited For Sanction For Payment</span>
                            </a>
                        </li>


                        <li
                            class="nav-item {{ request()->is('list-po-sanction-and-need-to-do-payment-to-vendor') ? 'active' : '' }}">
                            <a href="{{ route('list-po-sanction-and-need-to-do-payment-to-vendor') }}">
                                <i class="fa big-icon fa-file-signature icon-wrap"></i>
                                <span class="mini-click-non">PO Pyament Need To Release</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('recive-logistics-list') ? 'active' : '' }}">
                            <a href="{{ route('recive-logistics-list') }}">
                                <i class="fa big-icon fa-list-check icon-wrap"></i>
                                <span class="mini-click-non">Receive Logistics List</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('list-send-to-dispatch') ? 'active' : '' }}">
                            <a href="{{ route('list-send-to-dispatch') }}">
                                <i class="fa big-icon fa-truck  icon-wrap"></i>
                                <span class="mini-click-non">Product Submited to Dispatch</span>
                            </a>
                        </li>
                    @endif


                    @if (session()->get('role_id') == config('constants.ROLE_ID.LOGISTICS'))
                    <li class="nav-item {{ request()->is('logisticsdept/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>

                    <li class="nav-item {{ request()->is('list-final-production-completed-recive-to-logistics') ? 'active' : '' }}">
                        <a href="{{ route('list-final-production-completed-recive-to-logistics') }}">
                            <i class="fa big-icon fa-check-circle icon-wrap"></i>
                            <span class="mini-click-non">Production Completed</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('list-logistics') ? 'active' : '' }}">
                        <a href="{{ route('list-logistics') }}">
                            <i class="fa big-icon fa-list-check icon-wrap"></i>
                            <span class="mini-click-non">List Logistics</span>
                        </a>
                    </li>


                    <li
                        class="nav-item {{ request()->is('list-send-to-fianance-by-logistics') ? 'active' : '' }}">
                        <a href="{{ route('list-send-to-fianance-by-logistics') }}">
                            <i class="fa big-icon fa-paper-plane icon-wrap"></i>
                            <span class="mini-click-non">Submited by Fianance</span>
                        </a>
                    </li>
                    <li>
                    <a class="" href="{{ route('list-vehicle-type') }}" aria-expanded="false"><i
                        class="fa big-icon fa-cogs icon-wrap"></i> <span
                        class="mini-click-non">Vehicle Type</span></a>
                   </li>
                   <li>
                    <a class="" href="{{ route('list-transport-name') }}" aria-expanded="false"><i
                        class="fa big-icon fa-truck icon-wrap"></i> <span
                        class="mini-click-non">Transport Name</span></a>
                   </li>
                @endif

                @if (session()->get('role_id') == config('constants.ROLE_ID.DISPATCH'))
                <li class="nav-item {{ request()->is('dispatchdept/dashboard') ? 'active' : '' }}">
                    <a  href="{{ route('dashboard') }}"><i
                    class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                    class="mini-click-non">Dashboard</span></a></li>

                <li class="nav-item {{ request()->is('list-final-production-completed-received-from-fianance') ? 'active' : '' }}">
                    <a href="{{ route('list-final-production-completed-received-from-fianance') }}">
                        <i class="fa big-icon fa-receipt  icon-wrap"></i>
                        <span class="mini-click-non">Received From Finance</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('list-dispatch') ? 'active' : '' }}">
                    <a href="{{ route('list-dispatch') }}">
                        <i class="fa big-icon fa-truck icon-wrap"></i>
                        <span class="mini-click-non">Product Dispatch Quantity Wise </span>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('list-dispatch-final-product-close') ? 'active' : '' }}">
                    <a href="{{ route('list-dispatch-final-product-close') }}">
                        <i class="fa big-icon fa-truck icon-wrap"></i>
                        <span class="mini-click-non">Product Dispatch Completed (Close Product)</span>
                    </a>
                </li>
            @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.CMS'))
                    <li class="nav-item {{ request()->is('cms/dashboard') ? 'active' : '' }}">
                        <a  href="{{ route('dashboard') }}"><i
                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                        class="mini-click-non">Dashboard</span></a></li>
                       <!-- <li>
                        <a class="has-arrow" href="{{ route('list-product') }}"
                            aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                class="mini-click-non">CMS</span></a>
                        <ul class="submenu-angle" aria-expanded="false"> -->
                            <li class="nav-item"><a  href="{{ route('list-product') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-cube icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Product</span></a></li>
                            <li class="nav-item"><a  href="{{ route('list-services') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-tools icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Services</span></a></li>

                            <li class="nav-item"><a  href="{{ route('list-testimonial') }}" aria-expanded="false"><i
                                        class="fa big-icon fa-quote-right icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">Testimonial</span></a></li>
                                        
                            <li class="nav-item"><a  href="{{ route('list-director-desk') }}" aria-expanded="false"><i
                                            class="fa big-icon fa-briefcase icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Director Desk</span></a></li> 
                            <li class="nav-item"><a  href="{{ route('list-vision-mission') }}" aria-expanded="false"><i
                                                class="fa big-icon fa-bullseye icon-wrap" aria-hidden="true"></i> <span
                                                class="mini-click-non">Vision Mission</span></a></li> 
                            <li class="nav-item"><a  href="{{ route('list-team') }}" aria-expanded="false"><i
                                                    class="fa big-icon fa-user-friends icon-wrap" aria-hidden="true"></i> <span
                                                    class="mini-click-non">Team</span></a></li>   
                            <!-- <li class="nav-item"><a  href="{{ route('list-testimonial') }}" aria-expanded="false"><i
                                                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                                        class="mini-click-non">Testimonial</span></a></li>  -->
                            <li class="nav-item"><a  href="{{ route('list-contactus-form') }}" aria-expanded="false"><i
                                                            class="fa big-icon fa-edit icon-wrap" aria-hidden="true"></i> <span
                                                            class="mini-click-non">Contact Us Form </span></a></li>                               
                        <!-- </ul>
                    </li>                    -->
                    @endif

                    @if (session()->get('user_id'))
                        <li class="nav-item">
                            <a class="nav-item" href="{{ route('list-leaves') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-paper-plane icon-wrap"></i> <span class="mini-click-non">Leaves
                                    Request</span></a>
                            <ul class="nav-item" aria-expanded="false">
                                <li><a  href="{{ route('list-leaves') }}"><i
                                            class="fa big-icon fa-calendar icon-wrap" aria-hidden="true"></i> <span
                                            class="mini-click-non">Add Leaves Request</span></a></li>
                            </ul>
                        </li>
                        {{-- <li class="nav-item {{ request()->is('particular-notice-department-wise') ? 'active' : '' }}">
                            <a href="{{ route('particular-notice-department-wise') }}">
                                <i class="fa big-icon fa-bell  icon-wrap"></i>
                                <span class="mini-click-non">Notice</span>
                            </a>
                        </li> --}}
                        
                    @endif

                    

                    {{-- =====sample routing============= --}}
                    {{-- <li>
                        <a class="has-arrow" href="{{ route('list-newproducts') }}" aria-expanded="false"><i
                                class="fa big-icon fa-envelope icon-wrap"></i> <span
                                class="mini-click-non">NEW Product List</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a  href="{{ route('list-newproducts') }}"><i
                                        class="fa big-icon fa-envelope icon-wrap" aria-hidden="true"></i> <span
                                        class="mini-click-non">NEW Product List</span></a></li>
                        </ul>
                    </li> --}}

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
                                            {{-- <li class="nav-item dropdown">
                                                <a href="#" data-toggle="dropdown" role="button"
                                                    aria-expanded="false" class="nav-link dropdown-toggle"><i
                                                        class="fa fa-envelope-o adminpro-chat-pro"
                                                        aria-hidden="true"></i><span class="indicator-ms"></span></a>
                                                <div role="menu"
                                                    class="author-message-top dropdown-menu animated zoomIn">
                                                    <div class="message-single-top">
                                                        <h1>Message</h1>
                                                    </div>
                                                    <ul class="message-menu">
                                                        <li>
                                                            <a href="#">
                                                                <div class="message-img">
                                                                    <img src="{{ asset('img/contact/1.jpg') }}"
                                                                        alt="">
                                                                </div>
                                                                <div class="message-content">
                                                                    <span class="message-date">16 Sept</span>
                                                                    <h2>Advanda Cro</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                    <div class="message-view">
                                                        <a href="#">View All Messages</a>
                                                    </div>
                                                </div>
                                            </li> --}}
                                             
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button"
                                                    aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa fa-bell" style="padding-right: 32px;"></i></i><span
                                                        class="satish" style="position: fixed; top: 9px;translate: -149%;" id="notification-count"></span>
                                                </a>
                                                
                                                <div role="menu"
                                                    class="notification-author dropdown-menu animated zoomIn">
                                                    <div class="notification-single-top" style="background-color: linear-gradient(178deg, #175CA2 0%, #121416 100%)">
                                                        <h1 style="color: #fff; ">Notifications</h1>
                                                    </div>
                                                    <ul class="notification-menu" id="notification-messages" style="background-color:#fff;">
                                                        <li>
                                                        </li>
                                                    </ul>
                                                    <div class="notification-view">
                                                        

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button"
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
    {{-- <form method="POST" action="{{ url('/particular-notice-department-wise') }}" id="showform">
        @csrf
        <input type="hidden" name="show_id" id="show_id" value="">
    </form> --}}

    {{-- <script>
        function fetch_new_hold() {
            var TestVal = '1';
            if (TestVal !== '') {
                $.ajax({
                    url: '{{ route('get-notification') }}',
                    type: 'GET',
                    data: { TestVal: TestVal },
                    success: function(response) {
                        console.log("response");
                        console.log(response);

                        if (response.notification_count > 0) {
                            $('#notification-count').text(response.notification_count).show(); // Show when count > 0
                            
                            var notificationMessages = '';
                            $.each(response.notifications, function(index, notification) {
                                var urlvar = notification.url;
                                if (notification.admin_count > 0) {        
                                    notificationMessages += `
                                        <li>
                                            <a href="${urlvar}">
                                                <div class="notification-content">
                                                    <h2 style="color:#444;">${notification.message}</h2>
                                                </div>
                                            </a>
                                        </li>`;
                                }
                            });
                            $('#notification-messages').html(notificationMessages);
                        
                        } else {
                            $('#satish').hide(); // Hide when count is 0
                        }
                    }
                });
            }
        }
    
        $(document).ready(function(){
            setInterval(fetch_new_hold, 1000);
        });
    </script> --}}