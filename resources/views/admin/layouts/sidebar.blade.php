<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="index.html"><img class="main-logo" src="{{ asset('img/logo/logo.png') }}" alt=""></a>
            <strong><img src="{{ asset('img/logo/logo.png') }}" alt=""></strong>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">

                <ul class="metismenu" id="menu1">
                    @if (session()->get('role_id') == config('constants.ROLE_ID.SUPER'))
                        <li>
                            <a class="has-arrow" href="{{ route('list-organizations') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Organizations</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-organizations') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Organizations</span></a></li>
                                <li><a title="Inbox" href="{{ route('organizations-list-employees') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Employees</span></a>
                                </li>
                                <li><a title="Inbox" href="{{ route('list-departments') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Departments</span></a>
                                </li>
                                <li><a title="Inbox" href="{{ route('list-roles') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Roles</span></a></li>
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
                        <li>
                            <a class="has-arrow" href="{{ route('organizations-list-employees') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Employees</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('organizations-list-employees') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Add Employees</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-business') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Business</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-business') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Business List</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-forwarded-to-design') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">List Business Sent For Design</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span class="mini-click-non">List
                                    Design Received <br> For Production</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-design-correction') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">List Design Received <br> For Design Correction</span></a>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.PURCHASE'))
                        {{-- <li>
                        <a class="has-arrow" href="{{ route('list-purchase') }}" aria-expanded="false"><i
                                class="fa big-icon fa-envelope icon-wrap"></i> <span
                                class="mini-click-non">Purchase</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Inbox" href="{{ route('list-purchase') }}"><i
                                        class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">List Purchase</span></a></li>
                        </ul>
                    </li> --}}
                        <li>
                            <a class="has-arrow" href="{{ route('list-purchase') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Purchase
                                    Orders</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-purchase') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Purchase Orders</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-vendor') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Vendor</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-vendor') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">Vendor List</span></a></li>
                            </ul>
                        </li>
                    @endif
                    @if (session()->get('role_id') == config('constants.ROLE_ID.DESIGNER'))
                        {{-- <li>
                            <a class="has-arrow" href="{{ route('list-designs') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Designs</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="{{ route('list-designs') }}"><i
                                            class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                            class="mini-sub-pro">List Designs</span></a></li>
                            </ul>
                        </li> --}}
                        <li>
                            <a class="has-arrow" href="{{ route('list-new-requirements-received-for-design') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">List New Requirements <br> Received For Design</span></a>
                        </li>
                        <li>
                            <a class="has-arrow" href="{{ route('list-design-upload') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Designs Sent To Porduction</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-reject-design-from-prod') }}"
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
                        <li>
                            <a class="has-arrow" href="{{ route('list-new-requirements-received-for-production') }}"
                                aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">New Design List</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-accept-design') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Accepted Design List</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-reject-design') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Rejected Design List</span></a>
                        </li>

                        <li>
                            <a class="has-arrow" href="{{ route('list-revised-design') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Revised Design List</span></a>
                        </li>

                         <li>
                            <a class="has-arrow" href="{{ route('list-material-recived') }}" aria-expanded="false"><i
                                    class="fa big-icon fa-envelope icon-wrap"></i> <span
                                    class="mini-click-non">Material Received For Production</span></a>
                        </li>

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
                        <li>
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
                        </li>


                        <li>
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
                                            <li class="nav-item"><a href="#" data-toggle="dropdown"
                                                    role="button" aria-expanded="false"
                                                    class="nav-link dropdown-toggle"><i class="fa fa-bell-o"
                                                        aria-hidden="true"></i><span class="indicator-nt"></span></a>
                                                <div role="menu"
                                                    class="notification-author dropdown-menu animated zoomIn">
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
                                                        <li>
                                                            <a href="#">
                                                                <div class="notification-icon">
                                                                    <i class="fa fa-cloud adminpro-cloud-computing-down"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="notification-content">
                                                                    <span class="notification-date">16 Sept</span>
                                                                    <h2>Sulaiman din</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <div class="notification-icon">
                                                                    <i class="fa fa-eraser adminpro-shield"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="notification-content">
                                                                    <span class="notification-date">16 Sept</span>
                                                                    <h2>Victor Jara</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <div class="notification-icon">
                                                                    <i class="fa fa-line-chart adminpro-analytics-arrow"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="notification-content">
                                                                    <span class="notification-date">16 Sept</span>
                                                                    <h2>Victor Jara</h2>
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
                                                    <span class="admin-name">Advanda Cro</span>
                                                    <i class="fa fa-angle-down adminpro-icon adminpro-down-arrow"></i>
                                                </a>
                                                <ul role="menu"
                                                    class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                    <li><a href="register.html"><span
                                                                class="fa fa-home author-log-ic"></span>Register</a>
                                                    </li>
                                                    <li><a href="#"><span
                                                                class="fa fa-user author-log-ic"></span>My
                                                            Profile</a>
                                                    </li>
                                                    <li><a href="lock.html"><span
                                                                class="fa fa-diamond author-log-ic"></span> Lock</a>
                                                    </li>
                                                    <li><a href="#"><span
                                                                class="fa fa-cog author-log-ic"></span>Settings</a>
                                                    </li>
                                                    <li><a href="{{ route('log-out') }}"><span
                                                                class="fa fa-lock author-log-ic"></span>Log Out</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item nav-setting-open"><a href="#"
                                                    data-toggle="dropdown" role="button" aria-expanded="false"
                                                    class="nav-link dropdown-toggle"><i class="fa fa-tasks"></i></a>

                                                <div role="menu"
                                                    class="admintab-wrap menu-setting-wrap menu-setting-wrap-bg dropdown-menu animated zoomIn">
                                                    <ul class="nav nav-tabs custon-set-tab">
                                                        <li class="active"><a data-toggle="tab"
                                                                href="#Notes">News</a>
                                                        </li>
                                                        <li><a data-toggle="tab" href="#Projects">Activity</a>
                                                        </li>
                                                        <li><a data-toggle="tab" href="#Settings">Settings</a>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content custom-bdr-nt">
                                                        <div id="Notes" class="tab-pane fade in active">
                                                            <div class="notes-area-wrap">
                                                                <div class="note-heading-indicate">
                                                                    <h2><i class="fa fa-comments-o"></i> Latest News
                                                                    </h2>
                                                                    <p>You have 10 New News.</p>
                                                                </div>
                                                                <div class="notes-list-area notes-menu-scrollbar">
                                                                    <ul class="notes-menu-list">
                                                                        <li>
                                                                            <a href="#">
                                                                                <div class="notes-list-flow">
                                                                                    <div class="notes-img">
                                                                                        <img src="{{ asset('img/contact/4.jpg') }}"
                                                                                            alt="">
                                                                                    </div>
                                                                                    <div class="notes-content">
                                                                                        <p> The point of using Lorem
                                                                                            Ipsum is that it has a
                                                                                            more-or-less normal.</p>
                                                                                        <span>Yesterday 2:45 pm</span>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="Projects" class="tab-pane fade">
                                                            <div class="projects-settings-wrap">
                                                                <div class="note-heading-indicate">
                                                                    <h2><i class="fa fa-cube"></i> Recent Activity</h2>
                                                                    <p> You have 20 Recent Activity.</p>
                                                                </div>
                                                                <div
                                                                    class="project-st-list-area project-st-menu-scrollbar">
                                                                    <ul class="projects-st-menu-list">
                                                                        <li>
                                                                            <a href="#">
                                                                                <div class="project-list-flow">
                                                                                    <div class="projects-st-heading">
                                                                                        <h2>New User Registered</h2>
                                                                                        <p> The point of using Lorem
                                                                                            Ipsum is that it has a more
                                                                                            or less normal.</p>
                                                                                        <span class="project-st-time">1
                                                                                            hours ago</span>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="Settings" class="tab-pane fade">
                                                            <div class="setting-panel-area">
                                                                <div class="note-heading-indicate">
                                                                    <h2><i class="fa fa-gears"></i> Settings Panel</h2>
                                                                    <p> You have 20 Settings. 5 not completed.</p>
                                                                </div>
                                                                <ul class="setting-panel-list">
                                                                    <li>
                                                                        <div class="checkbox-setting-pro">
                                                                            <div class="checkbox-title-pro">
                                                                                <h2>Show notifications</h2>
                                                                                <div class="ts-custom-check">
                                                                                    <div class="onoffswitch">
                                                                                        <input type="checkbox"
                                                                                            name="collapsemenu"
                                                                                            class="onoffswitch-checkbox"
                                                                                            id="example">
                                                                                        <label
                                                                                            class="onoffswitch-label"
                                                                                            for="example">
                                                                                            <span
                                                                                                class="onoffswitch-inner"></span>
                                                                                            <span
                                                                                                class="onoffswitch-switch"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="checkbox-setting-pro">
                                                                            <div class="checkbox-title-pro">
                                                                                <h2>Disable Chat</h2>
                                                                                <div class="ts-custom-check">
                                                                                    <div class="onoffswitch">
                                                                                        <input type="checkbox"
                                                                                            name="collapsemenu"
                                                                                            class="onoffswitch-checkbox"
                                                                                            id="example3">
                                                                                        <label
                                                                                            class="onoffswitch-label"
                                                                                            for="example3">
                                                                                            <span
                                                                                                class="onoffswitch-inner"></span>
                                                                                            <span
                                                                                                class="onoffswitch-switch"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>

                                                                    <div class="checkbox-setting-pro">
                                                                        <div class="checkbox-title-pro">
                                                                            <h2>Offline users</h2>
                                                                            <div class="ts-custom-check">
                                                                                <div class="onoffswitch">
                                                                                    <input type="checkbox"
                                                                                        name="collapsemenu"
                                                                                        checked=""
                                                                                        class="onoffswitch-checkbox"
                                                                                        id="example5">
                                                                                    <label class="onoffswitch-label"
                                                                                        for="example5">
                                                                                        <span
                                                                                            class="onoffswitch-inner"></span>
                                                                                        <span
                                                                                            class="onoffswitch-switch"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
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
<!-- Mobile Menu start -->
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">
                            <li>
                                <a class="has-arrow" href="{{ route('list-organizations') }}"
                                    aria-expanded="false"><i class="fa big-icon fa-envelope icon-wrap"></i> <span
                                        class="mini-click-non">Organizations</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Inbox" href="{{ route('list-organizations') }}"><i
                                                class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                                class="mini-sub-pro">List Organizations</span></a></li>
                                    <li><a title="Inbox" href="{{ route('list-employees') }}"><i
                                                class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                                class="mini-sub-pro">List Employees</span></a></li>
                                    <li><a title="Inbox" href="{{ route('list-departments') }}"><i
                                                class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                                class="mini-sub-pro">List Departments</span></a></li>
                                    <li><a title="Inbox" href="{{ route('list-roles') }}"><i
                                                class="fa fa-inbox sub-icon-mg" aria-hidden="true"></i> <span
                                                class="mini-sub-pro">List Roles</span></a></li>

                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demo" href="#">Mailbox <span
                                        class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                <ul id="demo" class="collapse dropdown-header-top">
                                    <li><a href="mailbox.html">Inbox</a>
                                    </li>
                                    <li><a href="mailbox-view.html">View Mail</a>
                                    </li>
                                    <li><a href="mailbox-compose.html">Compose Mail</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end -->
<div class="breadcome-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcome-list">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcome-heading">

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Super Admin</span>
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
