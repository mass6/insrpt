    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-globe"></i>
                        <span class="caption-subject bold uppercase"> {{ isset($company) ? 'Company: ' . $company->name : 'New Company' }}</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="tabbable-custom">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a aria-expanded="true" href="#tab_15_1" data-toggle="tab">
                                General </a>
                            </li>
                            <li class="">
                                <a aria-expanded="false" href="#tab_15_2" data-toggle="tab">
                                Addresses </a>
                            </li>
                        @if (isset($company))
                            <li class="">
                                <a aria-expanded="false" href="#tab_15_3" data-toggle="tab" id="suppliers-tab">
                                Suppliers </a>
                            </li>
                        @endif
                            <li class="">
                                <a aria-expanded="false" href="#tab_15_4" data-toggle="tab">
                                Settings </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_15_1">
                                @include('admin.companies._general')
                            </div>
                            <div class="tab-pane" id="tab_15_2">
                                @include('admin.companies._addresses')
                            </div>
                        @if (isset($company))
                            <div class="tab-pane" id="tab_15_3">
                                @include('admin.companies._suppliers')
                            </div>
                        @endif
                            <div class="tab-pane" id="tab_15_4">
                                @include('admin.companies._settings')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>



<!-- Bottom Scripts -->
