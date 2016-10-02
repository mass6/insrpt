<div class="sidebar-menu">


<header class="logo-env">

    @include((isset($layoutDirectory) ? $layoutDirectory : 'layouts.default') . '.partials._logo')

    <!-- logo collapse icon -->

    <div class="sidebar-collapse">
        <a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
            <i class="entypo-menu"></i>
        </a>
    </div>



    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
    <div class="sidebar-mobile-menu visible-xs">
        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
            <i class="entypo-menu"></i>
        </a>
    </div>

</header>

@if (Sentry::check())
    <ul id="main-menu" class="auto-inherit-active-class">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
        @if ($currentUser->hasAccess('dashboards'))
            <li id="nav-dashboards" class="{{ isPath('dashboard') }}">
                <a href="{{ route('dashboards.home') }}">
                    <i class="entypo-gauge"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
        @if ($currentUser->hasAccess('portal.*'))

            @if ($currentUser->hasAccess('portal.orders'))
                <li class="auto-inherit-active-class {{ isPath('portal/orders/*', true) }}">
                    <a href="">
                        <i class="entypo-ticket"></i>
                        <span>Orders</span>
                    </a>
                    <ul>
                        <li id="search">
                            {{ Form::open(['route'=>'portal.orders.search', 'method'=>'GET']) }}
                            <input type="text" name="s" class="search-input" placeholder="Search Weborders..."/>
                            <button type="submit">
                                <i class="entypo-search"></i>
                            </button>
                            {{ Form::close() }}
                        </li>
                        <li class="{{ isPath('portal/orders/search') }}">
                            <a href="{{ route('portal.orders.search') }}">
                                <span>Search</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/approvals') }}">
                            <a href="{{ route('portal.orders.pending-approval') }}">
                                <span>Pending Approval</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/today') }}">
                            <a href="{{ route('portal.orders.period', 'today') }}">
                                <span>Today</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/yesterday') }}">
                            <a href="{{ route('portal.orders.period', 'yesterday') }}">
                                <span>Yesterday</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/this-month') }}">
                            <a href="{{ route('portal.orders.period', 'this-month') }}">
                                <span>This Month</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/last-month') }}">
                            <a href="{{ route('portal.orders.period', 'last-month') }}">
                                <span>Last Month</span>
                            </a>
                        </li>
                        <li class="{{ isPath('portal/orders/ytd') }}">
                            <a href="{{ route('portal.orders.period', 'ytd') }}">
                                <span>Year to Date</span>
                            </a>
                        </li>
                    @if($currentUser->hasAccess('portal.orders.thirdparty'))
                        <li class="{{ isPath('portal/orders/third-party-this-month') }}">
                            <a href="{{ route('portal.orders.period', 'third-party-this-month') }}">
                                <span>Third Party This Month</span>
                            </a>
                        </li>
                    @endif
                    </ul>
                </li>
            @endif

            @if ($currentUser->hasAccess('portal.orders.products-ordered*'))
                <li class="auto-inherit-active-class {{ isPath('portal/report*', true) }}">
                    <a href="#">
                        <i class="entypo-check"></i>
                        <span>Reports</span>
                    </a>
                    <ul>
                        <li class="{{ isPath('portal/report/products-ordered') }}">
                            <a href="{{ route('portal.report.products-ordered') }}">
                                <span>Products Ordered</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if ($currentUser->hasAccess('portal.contracts'))
                <li class="{{ isPath('portal/contracts') }}">
                    <a href="{{ route('portal.contracts', 'filter') }}">
                        <i class="entypo-docs"></i>
                        <span>Contracts</span>
                    </a>
                </li>
            @endif
            @if ($currentUser->hasAccess('portal.users'))
                <li class="{{ isPath('portal/users') }}">
                    <a href="{{ route('portal.users') }}">
                        <i class="entypo-cc-by"></i>
                        <span>Portal Users</span>
                    </a>
                </li>
            @endif
            @if ($currentUser->hasAccess('portal.products'))
                <li class="{{ isPath('portal/products') }}">
                    <a href="{{ route('portal.products','filter') }}">
                        <i class="entypo-basket"></i>
                        <span>Products</span>
                    </a>
                </li>
            @endif
            @if ($currentUser->hasAccess('portal.approvals'))
                <li class="{{ isPath('portal/approval-statistics') }}">
                    <a href="{{ route('portal.approval-statistics') }}">
                        <i class="entypo-check"></i>
                        <span>Approval Statistics</span>
                    </a>
                </li>
            @endif
            @if ($currentUser->hasAccess('portal.doa'))
                <li class="{{ isPath('portal/doa') }}">
                    <a href="{{ route('portal.doa') }}">
                        <i class="entypo-flag"></i>
                        <span>DOA</span>
                    </a>
                </li>
            @endif
        @endif

        @if ($currentUser->hasAccess('product-request*'))
        <li id="product-requests" class="auto-inherit-active-class {{ isPath('product-request*', true) }}{{ isPath('product-proposals*', true) }}">
                <a href="#">
                    <i class="entypo-globe"></i>
                    <span>Product Requests</span>
                </a>
                <ul>
                    <li class="{{ isPath('product-requests') }}">
                        <a href="{{ route('product-requests.index') }}">
                            <i class="entypo-inbox"></i>
                            <span>All Requests</span>
                        </a>
                    </li>
                    @if ($currentUser->hasAccess('product-requests.create'))
                    <li class="{{ isPath('product-requests/create') }}">
                        <a href="{{ route('product-requests.create') }}">
                            <i class="entypo-plus-squared"></i>
                            <span>New Product Request</span>
                        </a>
                    </li>
                    @endif
                    @if ($currentUser->hasAccess('product-request-lists.create'))
                    <li class="{{ isPath('product-request-lists/create') }}">
                        <a href="{{ route('product-request-lists.create') }}">
                            <i class="entypo-upload"></i>
                            <span>Upload Requests</span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ isPath('product-request-lists') }}">
                        <a href="{{ route('product-request-lists.index') }}">
                            <i class="entypo-list"></i>
                            <span>Request Lists</span>
                        </a>
                    </li>
                @if($currentUser->hasAccess('product-proposals.workbench'))
                    <li class="{{ isPath('product-proposals/workbench') }}">
                        <a href="{{ route('product-proposals.workbench') }}">
                            <i class="entypo-chart-line"></i>
                            <span>Proposals Workbench</span>
                        </a>
                    </li>
                @endif
                    <li class="{{ isPath('product-proposals') }}">
                        <a href="{{ route('product-proposals.index') }}">
                            <i class="entypo-attach"></i>
                            <span>Proposals</span>
                        </a>
                    </li>
                    <li class="{{ isPath('product-proposals/my-approvals') }}">
                        <a href="{{ route('product-proposals.my-approvals') }}">
                            <i class="entypo-thumbs-up"></i>
                            <span>My Approvals</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if($currentUser->hasAccess('quotation*'))
            <li id="quotations" class="auto-inherit-active-class {{ isPath('quotation*', true) }}  ">
                <a href="#">
                    <i class="entypo-globe"></i>
                    <span>Quotations</span>
                </a>
                <ul>
                    <li class="{{ isPath('quotations') }}">
                        <a href="{{ route('quotations.index') }}">
                            <i class="entypo-list"></i>
                            <span>Quotations</span>
                        </a>
                    </li>
                    <li class="{{ isPath('quotation-requests') }}">
                        <a href="{{ route('quotation-requests.index') }}">
                            <i class="entypo-list"></i>
                            <span>All Quotation Requests</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if ($currentUser->hasAccess('cataloguing.*'))
            <li class="auto-inherit-active-class {{ isPath('catalogue/product-definitions*', true) }}">
                <a href="#">
                    <i class="entypo-archive"></i>
                    <span>Product Cataloguing</span>
                </a>
                <ul>
                    <li class="{{ isPath('catalogue/product-definitions/myrequests') }}">
                        <a href="{{ route('catalogue.product-definitions.queue') }}">
                            <i class="entypo-inbox"></i>
                            <span>My Requests Queue</span>
                        </a>
                    </li>
                @if($currentUser->hasAccess('cataloguing.products.*'))
                    <li class="{{ isPath('catalogue/product-definitions/view') }}">
                        <a href="{{ route('catalogue.product-definitions.index.company_id','company') }}">
                            <i class="entypo-doc-text"></i>
                            <span>All Requests</span>
                        </a>
                    </li>
                @endif
                @if($currentUser->hasAccess('cataloguing.products.customer'))
                    <li class="{{ isPath('catalogue/product-definitions/completed') }}">
                        <a href="{{ route('catalogue.product-definitions.completed') }}">
                            <i class="entypo-check"></i>
                            <span>Completed Requests</span>
                        </a>
                    </li>
                @endif
                @if($currentUser->hasAccess('cataloguing.products.admin'))
                    <li class="{{ isPath('catalogue/product-definitions/export') }}">
                        <a href="{{ route('catalogue.product-definitions.export') }}">
                            <i class="entypo-export"></i>
                            <span>Export Data</span>
                        </a>
                    </li>
                @endif
                @if($currentUser->hasAccess('cataloguing.products.admin'))
                    <li class="{{ isPath('catalogue/product-definitions/import') }}">
                        <a href="{{ route('catalogue.product-definitions.import') }}">
                            <i class="entypo-export"></i>
                            <span>Import Product Attributes</span>
                        </a>
                    </li>
                @endif
                @if($currentUser->hasAccess('cataloguing.products.add'))
                    <li class="{{ isPath('catalogue/product-definitions/create') }}">
                        <a href="{{ route('catalogue.product-definitions.create') }}">
                            <i class="entypo-plus-squared"></i>
                            <span>New Request</span>
                        </a>
                    </li>
                @endif
                </ul>
            </li>
        @endif

        <!-- Admin Links -->
        @if ($currentUser->hasAccess('admin*'))
            <li class="auto-inherit-active-class {{ isPath('admin/*', true) }}">
            <a href="{{ route('admin.index') }}">
                <i class="entypo-tools"></i>
                <span>Admin</span>
            </a>
            <ul>
                @if ($currentUser->hasAccess('admin.companies*'))
                    <li class="{{ isPath('admin/companies') }}">
                        <a href="{{ route('admin.companies.index') }}">
                            <i class="entypo-flow-tree"></i>
                            <span>Companies</span>
                        </a>
                    </li>
                @endif
                @if ($currentUser->hasAccess('admin.users*'))
                    <li class="{{ isPath('admin/users') }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="entypo-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                @endif
                @if ($currentUser->hasAccess('admin.permissions*'))
                    <li class="{{ isPath('admin/permissions') }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="entypo-thumbs-up"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                @endif
                @if ($currentUser->hasAccess('admin.groups*'))
                    <li class="{{ isPath('admin/groups') }}">
                        <a href="{{ route('admin.groups.index') }}">
                            <i class="entypo-network"></i>
                            <span>Groups</span>
                        </a>
                    </li>
                @endif
                @if ($currentUser->hasAccess('admin.logs*'))
                <li class="{{ isPath('admin/logviewer*') }}">
                    <a href="{{ url('admin/logviewer') }}">
                        <i class="entypo-archive"></i>
                        <span>Logs</span>
                    </a>
                </li>
                @endif
                @if ($currentUser->hasAccess('admin.settings*'))
                <li class="{{ isPath('admin/settings') }}">
                    <a href="{{ url('admin/settings') }}">
                        <i class="entypo-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

    </ul>

@endif



</div>