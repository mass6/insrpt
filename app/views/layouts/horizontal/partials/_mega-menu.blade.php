<!-- BEGIN MEGA MENU -->
<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
<div class="hor-menu hor-menu-light">
<ul class="nav navbar-nav">
    @if ($currentUser->hasAccess('dashboards'))
        <li>
            <a href="{{ route('dashboards.home') }}">Dashboard</a>
        </li>
    @endif

    {{-- Begin Reports Menu --}}
    @if ($currentUser->hasAccess('portal.*'))
    <li class="menu-dropdown mega-menu-dropdown {{ isPath('portal/*', true) }}">
        <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
        Reports <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu" style="min-width: 710px">
            <li>
                <div class="mega-menu-content">
                    <div class="row">
                        @if ($currentUser->hasAccess('portal.orders'))
                            <div class="col-md-3">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Orders</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.pending-approval') }}" class="iconify">
                                        <i class="icon-hourglass"></i>
                                        Pending Approval </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.today') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        Today </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.yesterday') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        Yesterday </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.this-month') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        This Month </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.third-party-this-month') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        Third-Party This Month </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.last-month') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        Last Month </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.ytd') }}" class="iconify">
                                        <i class="fa fa-angle-right"></i>
                                        Year to Date </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.orders.custom') }}" class="iconify">
                                            <i class="fa fa-angle-right"></i>
                                            Custom </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.report.products-custom-order-lines') }}" class="iconify">
                                            <i class="fa fa-angle-right"></i>
                                            Custom Order Lines </a>
                                    </li>
                                </ul>
                            </div>
                            @if($currentUser->hasAccess('portal.deliveries'))
                            <div class="col-md-3">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Operations</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.deliveries.index') }}" class="iconify">
                                            <i class="icon-plane"></i>
                                            Deliveries </a>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        @endif
                        @if ($currentUser->hasAccess('portal.products'))
                            <div class="col-md-3">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Products</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.products') }}" class="iconify">
                                        <i class="icon-basket"></i>
                                        All Products </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('portal.report.products-ordered') }}" class="iconify">
                                        <i class="icon-credit-card"></i>
                                        Products Ordered </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        @if ($currentUser->hasAccess('portal.contracts') ||
                            $currentUser->hasAccess('portal.users') ||
                            $currentUser->hasAccess('portal.doa') ||
                            $currentUser->hasAccess('portal.approvals')
                            )
                        <div class="col-md-3">
                            <ul class="mega-menu-submenu">
                                <li>
                                    <h3>Portal</h3>
                                </li>
                                @if ($currentUser->hasAccess('portal.contracts'))
                                    <li>
                                        <a href="{{ route('portal.contracts') }}" class="iconify">
                                        <i class="icon-notebook"></i>
                                        Contracts </a>
                                    </li>
                                @endif
                                @if ($currentUser->hasAccess('portal.users'))
                                    <li>
                                        <a href="{{ route('portal.users') }}" class="iconify">
                                        <i class="icon-users"></i>
                                        Users </a>
                                    </li>
                                @endif
                                @if ($currentUser->hasAccess('portal.doa'))
                                    <li>
                                        <a href="{{ route('portal.doa') }}" class="iconify">
                                        <i class="icon-bubbles"></i>
                                        Delegation of Authority </a>
                                    </li>
                                @endif
                                @if ($currentUser->hasAccess('portal.approvals'))
                                    <li>
                                        <a href="{{ route('portal.approval-statistics') }}" class="iconify">
                                        <i class="icon-graph"></i>
                                        Approval Statistics </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </li>
    @endif
    {{-- End menu --}}

    {{-- Begin Product Requests Menu --}}
    @if ($currentUser->hasAccess('product-requests*'))
    <li class="menu-dropdown mega-menu-dropdown {{ isPath('product-requests*', true) }}">
        <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
        Procurement <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu" style="min-width: 710px">
            <li>
                <div class="mega-menu-content">
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="mega-menu-submenu">
                                <li>
                                    <h3>Product Requests</h3>
                                </li>
                                <li>
                                    <a href="{{ route('product-requests.index') }}" class="iconify">
                                    <i class="icon-docs"></i>
                                    View All </a>
                                </li>
                                @if ($currentUser->hasAccess('product-requests.create'))
                                <li>
                                    <a href="{{ route('product-requests.create') }}" class="iconify">
                                    <i class="icon-plus"></i>
                                    New Request </a>
                                </li>
                                @endif
                                @if ($currentUser->hasAccess('product-request-lists.create'))
                                <li>
                                    <a href="{{ route('product-request-lists.create') }}" class="iconify">
                                    <i class="icon-cloud-upload"></i>
                                    Upload Requests </a>
                                </li>
                                @endif
                                <li>
                                    <a href="{{ route('product-request-lists.index') }}" class="iconify">
                                    <i class="icon-list"></i>
                                    Request Lists </a>
                                </li>
                            </ul>
                        </div>
                        @if($currentUser->hasAccess('quotation*'))
                            <div class="col-md-4">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Quotations</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('quotation-requests.index') }}" class="iconify">
                                        <i class="icon-call-out"></i>
                                        Quotation Requests </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('quotations.index') }}" class="iconify">
                                        <i class="icon-note"></i>
                                        All Quotations </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        @if($currentUser->hasAccess('product-proposals*'))
                            <div class="col-md-4">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Proposals</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('product-proposals.index') }}" class="iconify">
                                        <i class="icon-users"></i>
                                        View All </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('product-proposals.my-approvals') }}" class="iconify">
                                        <i class="icon-hourglass"></i>
                                        Pending My Approval </a>
                                    </li>
                                    @if($currentUser->hasAccess('product-proposals.workbench'))
                                        <li>
                                            <a href="{{ route('product-proposals.workbench') }}" class="iconify">
                                            <i class="icon-wrench"></i>
                                            Workbench </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </li>
    @endif
    {{-- End menu --}}

    {{-- Begin Product Cataloguing Menu --}}
    @if ($currentUser->hasAccess('cataloguing.*'))
    <li class="menu-dropdown mega-menu-dropdown {{ isPath('catalogue/product-definitions*', true) }}">
        <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
        Cataloguing <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu" style="min-width: 710px">
            <li>
                <div class="mega-menu-content">
                    <div class="row">
                        <div class="col-md-4">

                            <ul class="mega-menu-submenu">
                            <li>
                                <h3>Cataloguing Requests</h3>
                            </li>
                            <li>
                                <a href="{{ route('catalogue.product-definitions.queue') }}" class="iconify">
                                <i class="icon-hourglass"></i>
                                My Requests Queue </a>
                            </li>
                            @if($currentUser->hasAccess('cataloguing.products.*'))
                            <li>
                                <a href="{{ route('catalogue.product-definitions.index.company_id','company') }}" class="iconify">
                                <i class="icon-docs"></i>
                                Active Requests </a>
                            </li>
                            @endif
                            @if($currentUser->hasAccess('cataloguing.products.customer'))
                            <li>
                                <a href="{{ route('catalogue.product-definitions.completed') }}" class="iconify">
                                <i class="icon-check"></i>
                                Completed Requests </a>
                            </li>
                            @endif
                            @if($currentUser->hasAccess('cataloguing.products.add'))
                            <li>
                                <a href="{{ route('catalogue.product-definitions.create') }}" class="iconify">
                                <i class="icon-plus"></i>
                                New Request </a>
                            </li>
                            @endif
                        </ul>

                        </div>
                        @if($currentUser->hasAccess('cataloguing.products.admin'))
                            <div class="col-md-4">
                                <ul class="mega-menu-submenu">
                                    <li>
                                        <h3>Import/Export</h3>
                                    </li>
                                    <li>
                                        <a href="{{ route('catalogue.product-definitions.export') }}" class="iconify">
                                        <i class="icon-cloud-download"></i>
                                        Export Data </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('catalogue.product-definitions.import') }}" class="iconify">
                                        <i class="icon-cloud-upload"></i>
                                        Import Product Attributes </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </li>
    @endif
    {{-- End menu--}}

    {{-- Beging Admin Menu --}}
    @if ($currentUser->hasAccess('admin*'))
    <li class="menu-dropdown mega-menu-dropdown {{ isPath('admin*', true) }}">
        <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
        Admin <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu" style="min-width: 710px">
            <li>
                <div class="mega-menu-content">
                    <div class="row">

                        <div class="col-md-4">
                            <ul class="mega-menu-submenu">
                                <li>
                                    <h3>Companies &amp; Users</h3>
                                </li>
                                <li>
                                    <a href="{{ route('admin.companies.index') }}" class="iconify">
                                    <i class="icon-globe"></i>
                                    Companies </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" class="iconify">
                                    <i class="icon-users"></i>
                                    Users </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-4">
                            <ul class="mega-menu-submenu">
                                <li>
                                    <h3>Access Control</h3>
                                </li>
                                <li>
                                    <a href="{{ route('admin.permissions.index') }}" class="iconify">
                                    <i class="icon-key"></i>
                                    Permissions </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.groups.index') }}" class="iconify">
                                    <i class="icon-folder"></i>
                                    Permission Groups </a>
                                </li>
                            </ul>
                        </div>


                        <div class="col-md-4">
                            <ul class="mega-menu-submenu">
                                <li>
                                    <h3>System</h3>
                                </li>
                                <li>
                                    <a href="{{ url('admin/settings') }}" class="iconify">
                                    <i class="icon-settings"></i>
                                    Settings </a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/logviewer') }}" class="iconify">
                                    <i class="icon-notebook"></i>
                                    Application Logs </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </li>
        </ul>
    </li>
    @endif
    {{-- End menu--}}
</ul>
</div>
<!-- END MEGA MENU -->