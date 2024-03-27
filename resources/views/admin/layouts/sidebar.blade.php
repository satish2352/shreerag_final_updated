<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="index.html"><img class="main-logo" src="{{ asset('website/assets/img/logo/LANSCAPE LOG.png') }}"
                    alt=""></a>
            <strong><img src="{{ asset('img/logo/logo.png') }}" alt=""></strong>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">

                <ul class="metismenu" id="menu1">
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SUPER'))
                    <li class="{{ Request::is('list-organizations', 'organizations-list-employees', 'list-departments', 'list-roles') ? 'active' : '' }}">
                        <a class="has-arrow" href="{{ route('list-organizations') }}" aria-expanded="false">
                            <i class="fa big-icon fa-envelope icon-wrap"></i>
                            <span class="mini-click-non">Organizations</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="nav-item {{ Request::is('list-organizations') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('list-organizations') }}">
                                    <i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i>
                                    <span class="mini-sub-pro">List Organizations</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('organizations-list-employees') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('organizations-list-employees') }}">
                                    <i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i>
                                    <span class="mini-sub-pro">List Employees</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('list-departments') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('list-departments') }}">
                                    <i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i>
                                    <span class="mini-sub-pro">List Departments</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('list-roles') ? 'active' : '' }}">
                                <a title="Inbox" href="{{ route('list-roles') }}">
                                    <i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i>
                                    <span class="mini-sub-pro">List Roles</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                        <!-- <li class="">
                            <a class="has-arrow" href="index.html">
                                <i class="fa big-icon fa-home icon-wrap"></i>
                                <span class="mini-click-non">Basic Product </span>
                            </a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-products') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Products</span></a></li>
                            </ul>
                        </li> -->
                        <!-- <li>
                            <a class="has-arrow" href="{{ route('organizations-list-employees') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Employees</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('organizations-list-employees') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Add Employees</span></a></li>
                            </ul>
                        </li> -->

                        <!-- <li>
                            <a class="has-arrow" href="{{ route('organizations-list-employees') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Employees</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('organizations-list-employees') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Add Employees</span></a></li>
                            </ul>
                        </li> -->
                    @endif

                    @if (session()->get('role_id') == config('constants.ROLE_ID.HIGHER_AUTHORITY'))
                    <ul class="sidebar-menu" id="nav-accordion">
                        <li class="{{ request()->is('owner/organizations-list-employees') ? 'active' : '' }}">
                            <a title="Inbox" href="{{ route('organizations-list-employees') }}"><i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Add Employees</span></a>
                        </li>
                        <li class="{{ request()->is('owner/list-business') ? 'active' : '' }}">
                            <a title="Inbox" href="{{ route('list-business') }}"><i class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span class="mini-sub-pro">Business</span></a>
                        </li>
                        <li class="{{ request()->is('owner/list-forwarded-to-design') ? 'active' : '' }}">
                            <a href="{{ route('list-forwarded-to-design') }}" aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">Business Sent For Design</span></a>
                        </li>
                        <li class="{{ request()->is('owner/list-design-upload') ? 'active' : '' }}">
                            <a href="{{ route('list-design-upload') }}" aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">Design Received For Production</span></a>
                        </li>
                        <li class="{{ request()->is('owner/list-design-correction') ? 'active' : '' }}">
                            <a href="{{ route('list-design-correction') }}" aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">Design Received For Design Correction</span></a>
                        </li>
                    </ul>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PURCHASE'))
                        
                        <li class="{{ Request::is('list-purchase') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-purchase') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Purchase
                                    Orders</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-purchase') ? 'active' : '' }}"><a title="Inbox" href="{{ route('list-purchase') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Purchase Orders</span></a></li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('list-vendor') ? 'active' : '' }}">
                            <a class="has-arrow" href="{{ route('list-vendor') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Vendor</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="nav-item {{ Request::is('list-vendor') ? 'active' : '' }}"><a title="Inbox" href="{{ route('list-vendor') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Vendor List</span></a></li>
                            </ul>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.DESIGNER'))
                        
                        <li class="nav-item {{ Request::is('list-new-requirements-received-for-design') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-new-requirements-received-for-design') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">New Business<br> Received For Design</span></a>
                        </li>
                        <li class="nav-item {{ Request::is('list-design-upload') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Designs Sent To Porduction</span></a>
                        </li>

                        <li class="nav-item {{ Request::is('list-reject-design-from-prod') ? 'active' : '' }}">
                            <a class="" href="{{ route('list-reject-design-from-prod') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Rejected Design List</span></a>
                        </li>


                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Designs</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-design-upload') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Designs</span></a></li>
                            </ul>
                        </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PRODUCTION'))
                    <ul class="sidebar-menu">
                        <li class="{{ request()->is('list-new-requirements-received-for-production') ? 'active' : '' }}">
                            <a href="{{ route('list-new-requirements-received-for-production') }}">
                                <i class="fa big-icon fa-files-o icon-wrap"></i>
                                <span class="mini-click-non">New Design List</span>
                            </a>
                        </li>
                        
                        <li class="{{ request()->is('list-accept-design*') ? 'active' : '' }}">
                            <a href="{{ route('list-accept-design') }}">
                                <i class="fa fa-paper-plane icon-wrap"></i>
                                <span class="mini-click-non">Accepted Design List</span>
                            </a>
                        </li>
                        
                        <li class="{{ request()->is('list-reject-design') ? 'active' : '' }}">
                            <a href="{{ route('list-reject-design') }}">
                                <i class="fa fa-frown-o icon-wrap"></i>
                                <span class="mini-click-non">Rejected Design List</span>
                            </a>
                        </li>
                        
                        <li class="{{ request()->is('list-revised-design') ? 'active' : '' }}">
                            <a href="{{ route('list-revised-design') }}">
                                <i class="fa fa-fighter-jet icon-wrap"></i>
                                <span class="mini-click-non">Revised Design List</span>
                            </a>
                        </li>
                        
                        <li class="{{ request()->is('list-material-recived') ? 'active' : '' }}">
                            <a href="{{ route('list-material-recived') }}">
                                <i class="fa fa-inbox icon-wrap"></i>
                                <span class="mini-click-non">Material Received For Production</span>
                            </a>
                        </li>
                    </ul>

                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-purchases') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Purchase</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-purchases') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Purchase</span></a></li>
                            </ul>
                        </li> --}}
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SECURITY'))
                        <li>
                            <a class="has-arrow" href="{{ route('list-gatepass') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">Gate
                                    Pass</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-gatepass') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Gate Pass</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-security-remark') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Remark</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-security-remark') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Remark</span></a></li>
                            </ul>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.QUALITY'))
                        <li>
                            <a class="has-arrow" href="{{ route('list-grn') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">GRN
                                    Form</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-grn') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List GRN</span></a></li>
                            </ul>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.STORE'))
                        {{-- <li>
                    <a class="has-arrow" href="{{ route('list-store-purchase') }}" aria-expanded="false"><i
                            class="fa big-icon fa-envelope icon-wrap"></i> <span
                            class="mini-click-non">Purchase</span></a>
                    <ul class="submenu-angle" aria-expanded="false">
                        <li><a title="Inbox" href="{{ route('list-store-purchase') }}"><i
                                    class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                    class="mini-sub-pro">List Purchase</span></a></li>
                    </ul>
                </li> --}}
                        <!-- <li>
                            <a class="has-arrow" href="{{ route('list-requistion') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Requistion</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-requistion') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Requistion</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-store-receipt') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">Store
                                    Receipt</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-store-receipt') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Store Receipt</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-doc-upload-fianace') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Document Upload to Fianace</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-doc-upload-fianace') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Document Upload to Fianace</span></a></li>
                            </ul>
                        </li> -->


                        <!-- <li>
                            <a class="has-arrow" href="{{ route('list-accepted-design-from-prod') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">All List</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-accepted-design-from-prod') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List All New Requirements</span></a></li>
                                <li><a title="Inbox" href="{{ route('list-material-sent-to-prod') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Requirements Sent To Production</span></a></li>
                            </ul>
                        </li> -->

                        <li class="{{ request()->is('list-accepted-design-from-prod') ? 'active' : '' }}">
                            <a href="{{ route('list-accepted-design-from-prod') }}">
                                <i class="fa big-icon fa-envelope icon-wrap"></i>
                                <span class="mini-click-non">All New Requirements</span>
                            </a>
                        </li>


                        <li class="{{ request()->is('list-material-sent-to-prod') ? 'active' : '' }}">
                            <a href="{{ route('list-material-sent-to-prod') }}">
                                <i class="fa big-icon fa-envelope icon-wrap"></i>
                                <span class="mini-click-non">Requirements Sent To Production</span>
                            </a>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.HR'))
                        <li>
                            <a class="has-arrow" href="{{ route('hr-list-employees') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Staffs</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('hr-list-employees') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Add Staffs</span></a></li>
                            </ul>
                        </li>
                    @endif
                    {{-- =====sample routing============= --}}
                    {{-- <li>
                        <a class="has-arrow" href="{{ route('list-newproducts') }}" aria-expanded="false"><i
                                class="fa big-icon fa-envelope icon-wrap"></i> <span
                                class="mini-click-non">NEW Product List</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Inbox" href="{{ route('list-newproducts') }}"><i
                                        class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">NEW Product List</span></a></li>
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
                    <a href="index.html"><img class="main-logo" src="{{ asset('img/logo/logo.png') }}"
                            alt=""></a>
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
                                        <button type="button" id="sidebarCollapse"
                                            class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
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
                                            <li class="nav-item dropdown">
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
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown"
                                                    role="button" aria-expanded="false"
                                                    class="nav-link dropdown-toggle"><i class="fa fa-bell-o"
                                                        aria-hidden="true"></i><span class="indicator-nt"></span>
                                                </a>
                                                <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                                    <div class="notification-single-top">
                                                        <h1>Notifications</h1>
                                                    </div>
                                                    <ul class="notification-menu">
                                                        <li>
                                                            <a href="#">
                                                                <div class="notification-icon">
                                                                    <i class="fa fa-check adminpro-checked-pro admin-check-pro"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="notification-content">
                                                                    <span class="notification-date">16 Sept</span>
                                                                    <h2>Advanda Cro</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="notification-view">
                                                        <a href="#">View All Notification</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button"
                                                    aria-expanded="false" class="nav-link dropdown-toggle">
                                                    <i class="fa fa-user adminpro-user-rounded header-riht-inf"
                                                        aria-hidden="true"></i>
                                                    <span class="admin-name"></span>
                                                    <i class="fa fa-angle-down adminpro-icon adminpro-down-arrow"></i>
                                                </a>
                                                <ul role="menu"
                                                    class="dropdown-header-top author-log dropdown-menu animated zoomIn">

                                                    <li><a href="{{ route('log-out') }}"><span
                                                                class="fa fa-lock author-log-ic"></span>Log Out</a>
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
<!-- </div> -->

