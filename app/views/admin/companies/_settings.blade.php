<ul id="settings-menu" class="nav nav-pills">
    <li class="active">
        <a href="#tab_2_1" data-toggle="tab">
        Portal Data </a>
    </li>
    <li>
        <a href="#tab_2_2" data-toggle="tab">
            Operations </a>
    </li>
    <li>
        <a href="#tab_2_3" data-toggle="tab">
        Product Cataloguing </a>
    </li>
    <li>
        <a href="#tab_2_4" data-toggle="tab">
        Product Requests </a>
    </li>
@if(isset($company) && $company->type == 'customer')
    <li>
        <a href="#tab_2_5" data-toggle="tab">
            Notifications </a>
    </li>
    <li>
        <a href="#tab_2_6" data-toggle="tab">
        Order Reports </a>
    </li>
@endif
</ul>
<br/>
<div>
    {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn btn-primary']) }}
    {{ link_to_route('admin.companies.index', 'Cancel', null, array('class'=>'btn btn-default')) }}
</div>

<div class="clear"></div>
<br/>
<br/>

<div class="tab-content">
    <div class="tab-pane fade active in" id="tab_2_1">

        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Data Group & Layout</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                <div class="col-md-5">

                    <!--Select Magento customer group-->
                    <div class="form-group">
                        {{ Form::label('magento_customer_group', 'Insight Customer Group:', ['class' => 'control-label']) }}
                        {{ Form::select('magento_customer_group_id', $customerGroups, null, ['class'=>'form-control', 'required','id'=>'magento_customer_group_id']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_portal_datagroup', 'Data Group:', ['class' => 'control-label']) }}
                        {{ Form::text('settings[portal][dataGroup]', isset($company->settings['portal']['dataGroup']) ? $company->settings['portal']['dataGroup'] : '', ['class' => 'form-control', 'id' => 'settings_portal_datagroup']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_portal_website_code', 'Portal Website Code:', ['class' => 'control-label']) }}
                        {{ Form::text('settings[portal][website_code]', isset($company->settings['portal']['website_code']) ? $company->settings['portal']['website_code'] : '', ['class' => 'form-control', 'id' => 'settings_portal_website_code']) }}
                    </div>

                </div>

                <div class="col-md-5 col-md-offset-1">

                    <div class="form-group">
                        {{ Form::label('settings_portal_store', 'Main Portal Store #:', ['class' => 'control-label']) }}
                        {{ Form::text('settings[portal][store]', isset($company->settings['portal']['store']) ? $company->settings['portal']['store'] : '' , ['class' => 'form-control', 'id' => 'settings_portal_store']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_design_layout', 'Layout:', ['class' => 'control-label']) }}
                        {{ Form::text('settings[design][layout]', isset($company->settings['design']['layout']) ? $company->settings['design']['layout'] : '' , ['class' => 'form-control', 'id' => 'settings_design_layout']) }}
                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="tab-pane fade" id="tab_2_2">

        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Materials Received Tracking</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                {{-- Approver 1--}}
                <h4>Materials Received Tracking Per Delivery</h4>

                <div class="row">
                    <div class="col-md-3">

                        {{
                            Form::checkbox('settings[operations][materials-received-tracking][enabled]', 'true',
                            array_get($settings,'operations.materials-received-tracking.enabled', false ),
                             [
                                'id' => 'materials-received-tracking_enabled_input',
                                'class' => 'make-switch',
                                'data-size' => 'small',
                                'data-on-color' => 'success',
                                'data-off-color' => 'default',
                                'data-on-text' => 'Enabled',
                                'data-off-text' => 'Disabled',
                            ]);
                        }}

                    </div>
                </div>

                <div class="clear"></div>
                <hr/>


            </div>

        </div>

    </div>
    <div class="tab-pane fade" id="tab_2_3">

        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Product Definitions</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                <div class="col-md-5">

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_template', 'Template:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][template]', isset($company->settings['productDefinitions']['template']) ? $company->settings['productDefinitions']['template'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_template']) }}
                        </div>
                    </div>

                </div>

                <div class="col-md-5 col-md-offset-1">

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customvalidationform', 'Custom Validation Form:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customValidationForm]', isset($company->settings['productDefinitions']['customValidationForm']) ? $company->settings['productDefinitions']['customValidationForm'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_customvalidationform']) }}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>

                <hr/>

                <div class="col-md-5">

                    <h4 class="image-labels">Product Image Labels</h4>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customimagelabels_imagelabel1', 'Image Label 1:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customImageLabels][imageLabel1]', isset($company->settings['productDefinitions']['customImageLabels']['imageLabel1']) ? $company->settings['productDefinitions']['customImageLabels']['imageLabel1'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_customimagelabels_imagelabel1']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customimagelabels_imagelabel2', 'Image Label 2:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customImageLabels][imageLabel2]', isset($company->settings['productDefinitions']['customImageLabels']['imageLabel2']) ? $company->settings['productDefinitions']['customImageLabels']['imageLabel2'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_customimagelabels_imagelabel2']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customimagelabels_imagelabel3', 'Image Label 3:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customImageLabels][imageLabel3]', isset($company->settings['productDefinitions']['customImageLabels']['imageLabel3']) ? $company->settings['productDefinitions']['customImageLabels']['imageLabel3'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_customimagelabels_imagelabel3']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customimagelabels_imagelabel4', 'Image Label 4:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customImageLabels][imageLabel4]', isset($company->settings['productDefinitions']['customImageLabels']['imageLabel4']) ? $company->settings['productDefinitions']['customImageLabels']['imageLabel4'] : '', ['class' => 'form-control', 'id' => 'productdefinitions_customimagelabels_imagelabel4']) }}
                        </div>
                    </div>

                </div>

                <div class="col-md-5 col-md-offset-1">

                    <h4 class="attachment-labels">Product Attachment Labels</h4>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customattachmentlabels_attachmentlabel1', 'Attachment Label 1:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customAttachmentLabels][attachmentLabel1]', isset($company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel1']) ? $company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel1'] : '', ['class' => 'form-control', 'id' => 'settings_productdefinitions_customattachmentlabels_attachmentlabel1']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customattachmentlabels_attachmentlabel2', 'Attachment Label 2:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customAttachmentLabels][attachmentLabel2]', isset($company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel2']) ? $company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel2'] : '', ['class' => 'form-control', 'id' => 'settings_productdefinitions_customattachmentlabels_attachmentlabel2']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customattachmentlabels_attachmentlabel3', 'Attachment Label 3:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customAttachmentLabels][attachmentLabel3]', isset($company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel3']) ? $company->settings['productDefinitions']['customAttachmentLabels']['attachmentLabel3'] : '', ['class' => 'form-control', 'id' => 'settings_productdefinitions_customattachmentlabels_attachmentlabel3']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('settings_productdefinitions_customattachmentlabels_attachmentlab', 'Attachment Label 4:', ['class' => 'control-label']) }}
                        <div class="">
                            {{ Form::text('settings[productDefinitions][customAttachmentLabels][attachmentLab]', isset($company->settings['productDefinitions']['customAttachmentLabels']['attachmentLab']) ? $company->settings['productDefinitions']['customAttachmentLabels']['attachmentLab'] : '', ['class' => 'form-control', 'id' => 'settings_productdefinitions_customattachmentlabels_attachmentlab']) }}
                        </div>
                    </div>

                </div>


            </div>

        </div>


    </div>
    <div class="tab-pane fade" id="tab_2_4">

        @if(isset($company))
        <div class="panel panel-info" data-collapsed="0">
            <?php $isDisabled = count($userDropdown) ? '' : 'disabled' ?>

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Product Requests Approvals</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                {{-- Auto Approvals --}}
                <h4>Auto Approvals</h4>
                <div class="row">
                    <div class="col-md-3">
                            <h5>Enabled</h5>
                            {{
                                Form::checkbox('settings[product-requests][proposals][auto-approvals][enabled]', 'true',
                                array_get($settings,'product-requests.proposals.auto-approvals.enabled', false ),
                                 [
                                    'id' => 'auto_approve_proposals_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}

                    </div>
                    <div class="col-md-5">
                        <h5>Timeout Window (in hours)</h5>
                        <div id="hour-spinner">
                            <div class="input-group input-small">
                                {{
                                    Form::text('settings[product-requests][proposals][auto-approvals][timeout-hours]',
                                    array_get($settings, 'product-requests.proposals.auto-approvals.timeout-hours', null),
                                    ['class' => 'spinner-input form-control', 'id' => 'auto_approve_proposals_timeout_hours_input', 'maxlength' => 3, 'readonly'])
                                }}
                                <div class="spinner-buttons input-group-btn btn-group-vertical">
                                    <button type="button" class="btn spinner-up btn-xs primary-36s">
                                    <i class="fa fa-angle-up"></i>
                                    </button>
                                    <button type="button" class="btn spinner-down btn-xs primary-36s">
                                    <i class="fa fa-angle-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>


                {{-- Approver 1--}}
                <h4>Approver 1</h4>

                <div class="row">
                    <div class="col-md-3">

                            {{
                                Form::checkbox('settings[product-requests][approvers][approver1][enabled]', 'true',
                                array_get($settings,'product-requests.approvers.approver1.enabled', false ),
                                 [
                                    'id' => 'approver1_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}

                    </div>
                    <div class="col-md-5">
                        {{
                            Form::select('settings[product-requests][approvers][approver1][approver_id]', $userDropdown,
                            array_get($settings, 'product-requests.approvers.approver1.approver_id', null),
                            ['class' => 'form-control', 'id' => 'settings_product_requests_approvers_approver1_approver_id_input'])
                        }}
                    </div>
                </div>

                <div class="clear"></div>
                <hr/>

                {{-- Approver 2--}}
                <h4>Approver 2</h4>

                <div class="row">
                    <div class="col-md-3">

                        {{
                             Form::checkbox('settings[product-requests][approvers][approver2][enabled]', 'true',
                                array_get($settings,'product-requests.approvers.approver2.enabled', false ),
                             [
                                'id' => 'approver2_enabled_input',
                                'class' => 'make-switch',
                                'data-size' => 'small',
                                'data-on-color' => 'success',
                                'data-off-color' => 'default',
                                'data-on-text' => 'Enabled',
                                'data-off-text' => 'Disabled',
                            ]);
                        }}

                    </div>
                    <div class="col-md-5">
                        {{
                            Form::select('settings[product-requests][approvers][approver2][approver_id]', $userDropdown,
                            array_get($settings, 'product-requests.approvers.approver2.approver_id', null),
                            ['class' => 'form-control', 'id' => 'settings_product_requests_approvers_approver2_approver_id_input'])
                        }}
                    </div>
                </div>

                <div class="clear"></div>
                <hr/>
            </div>

        </div>
        @endif

        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Product Reference Fields</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                <h4>Enable Product Reference Fields?</h4>
                <br/>
                <div class="row">
                    <div id="enable-refs" class="col-md-3">
                        {{
                             Form::checkbox('settings[product-requests][references][enabled]', 'true',
                                array_get($settings,'product-requests.references.enabled', false ),
                             [
                                'id' => 'references_enabled_input',
                                'class' => 'make-switch',
                                'data-size' => 'small',
                                'data-on-color' => 'success',
                                'data-off-color' => 'default',
                                'data-on-text' => 'Enabled',
                                'data-off-text' => 'Disabled',
                            ]);
                        }}
                    </div>
                </div>
                <hr/>
                <br/>

                <div id="references-container">
                    {{-- Reference 1--}}
                    <h4>Reference Field 1</h4>

                    <div class="row">
                        <div class="col-md-3">
                            {{
                                 Form::checkbox('settings[product-requests][reference1][enabled]', 'true',
                                array_get($settings,'product-requests.reference1.enabled', false ),
                                 [
                                    'id' => 'reference1_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}
                        </div>
                        <div class="col-md-3">

                                {{
                                    Form::checkbox('settings[product-requests][reference1][required]', 'true',
                                    array_get($settings,'product-requests.reference1.required', false ),
                                     [
                                        'id' => 'reference1_enabled_input',
                                        'class' => 'icheck',
                                        'data-checkbox' => "icheckbox_square-red"
                                     ])
                                }}
                                <label>Required</label>
                                <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                                </i>

                        </div>
                        <div class="col-md-6">
                            <!-- Ref Form Input -->
                            {{ Form::text('settings[product-requests][reference1][label]', array_get($settings,'product-requests.reference1.label', '' ), ['class' => 'form-control', 'id' => 'reference1_input', 'placeholder' => 'Field name']) }}
                        </div>
                    </div>

                    <div class="clear"></div>
                    <hr/>

                    {{-- Reference 2--}}

                    <h4>Reference Field 2</h4>

                    <div class="row">
                        <div class="col-md-3">
                            {{
                                 Form::checkbox('settings[product-requests][reference2][enabled]', 'true',
                                array_get($settings,'product-requests.reference2.enabled', false ),
                                 [
                                    'id' => 'reference2_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}
                        </div>
                        <div class="col-md-3">

                                {{
                                    Form::checkbox('settings[product-requests][reference2][required]', 'true',
                                    array_get($settings,'product-requests.reference2.required', false ),
                                     [
                                        'id' => 'reference2_enabled_input',
                                        'class' => 'icheck',
                                        'data-checkbox' => "icheckbox_square-red"
                                     ])
                                }}
                                <label>Required</label>
                                <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                                </i>

                        </div>
                        <div class="col-md-6">
                            <!-- Ref Form Input -->
                            {{ Form::text('settings[product-requests][reference2][label]', array_get($settings,'product-requests.reference2.label', '' ), ['class' => 'form-control', 'id' => 'reference2_input', 'placeholder' => 'Field name']) }}
                        </div>
                    </div>

                    <div class="clear"></div>
                    <hr/>


                    {{-- Reference 3--}}


                    <h4>Reference Field 3</h4>

                    <div class="row">
                        <div class="col-md-3">
                            {{
                                 Form::checkbox('settings[product-requests][reference3][enabled]', 'true',
                                array_get($settings,'product-requests.reference3.enabled', false ),
                                 [
                                    'id' => 'reference3_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}
                        </div>
                        <div class="col-md-3">

                                {{
                                    Form::checkbox('settings[product-requests][reference3][required]', 'true',
                                    array_get($settings,'product-requests.reference3.required', false ),
                                     [
                                        'id' => 'reference3_enabled_input',
                                        'class' => 'icheck',
                                        'data-checkbox' => "icheckbox_square-red"
                                     ])
                                }}
                                <label>Required</label>
                                <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                                </i>

                        </div>
                        <div class="col-md-6">
                            <!-- Ref Form Input -->
                            {{ Form::text('settings[product-requests][reference3][label]', array_get($settings,'product-requests.reference3.label', '' ), ['class' => 'form-control', 'id' => 'reference3_input', 'placeholder' => 'Field name']) }}
                        </div>
                    </div>
                    <div class="clear"></div>
                    <hr/>


                    {{-- Reference 4--}}

                    <h4>Reference Field 4</h4>

                    <div class="row">
                        <div class="col-md-3">
                            {{
                                 Form::checkbox('settings[product-requests][reference4][enabled]', 'true',
                                array_get($settings,'product-requests.reference4.enabled', false ),
                                 [
                                    'id' => 'reference4_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}
                        </div>
                        <div class="col-md-3">

                                {{
                                    Form::checkbox('settings[product-requests][reference4][required]', 'true',
                                    array_get($settings,'product-requests.reference4.required', false ),
                                     [
                                        'id' => 'reference4_enabled_input',
                                        'class' => 'icheck',
                                        'data-checkbox' => "icheckbox_square-red"
                                     ])
                                }}
                                <label>Required</label>
                                <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                                </i>

                        </div>
                        <div class="col-md-6">
                            <!-- Ref Form Input -->
                            {{ Form::text('settings[product-requests][reference4][label]', array_get($settings,'product-requests.reference4.label', '' ), ['class' => 'form-control', 'id' => 'reference4_input', 'placeholder' => 'Field name']) }}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>


            </div>

        </div>

        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Procurement Categories</div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <!-- panel body -->
            <div class="panel-body">

                <!-- Category1 Form Input -->
                <div class="row">
                    <div class="col-md-6">
                        {{ Form::label('settings[product-requests][procurement-categories]', 'Categories:') }}
                        <p>List the procurement categories names in the box below, one category per line.</p>
                        {{ Form::textarea('settings[product-requests][procurement-categories]',
                             array_get($settings, 'product-requests.procurement-categories')
                                ? implode("\r\n", array_get($settings, 'product-requests.procurement-categories'))
                                : '',
                            [
                                'class' => 'form-control',
                                'id' => 'procurement_categories'
                            ])
                        }}
                    </div>
                    <div class="clear"></div>
                </div>

            </div>

        </div>

    </div>
    @if(isset($company) && $company->type == 'customer')
    <div class="tab-pane fade" id="tab_2_5">

            <div class="panel panel-info" data-collapsed="0">

                <!-- panel head -->
                <div class="panel-heading">
                    <div class="panel-title">Reports Settings</div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>

                <!-- panel body -->
                <div class="panel-body">

                    {{-- Approver 1--}}
                    <h4>Orders Pending Approval</h4>

                    <div class="row">
                        <div class="col-md-3">

                            {{
                                Form::checkbox('settings[report-delivery][orders-pending-approval][enabled]', 'true',
                                array_get($settings,'report-delivery.orders-pending-approval.enabled', false ),
                                 [
                                    'id' => 'orders-pending-approval_enabled_input',
                                    'class' => 'make-switch',
                                    'data-size' => 'small',
                                    'data-on-color' => 'success',
                                    'data-off-color' => 'default',
                                    'data-on-text' => 'Enabled',
                                    'data-off-text' => 'Disabled',
                                ]);
                            }}

                        </div>
                        <?php $userRecipients = $userDropdown; ?>
                        <?php unset($userRecipients['']); ?>
                        <div class="col-md-5">
                            {{
                                Form::select('settings[report-delivery][orders-pending-approval][recipients][]', $userRecipients,
                                array_get($settings, 'report-delivery.orders-pending-approval.recipients', null),
                                ['class' => 'form-control select2 report-recipients', 'id' => 'settings_orders-pending-approval_recipients_input', 'multiple'])
                            }}
                        </div>
                    </div>

                    <div class="clear"></div>
                    <hr/>


                </div>

            </div>

    </div>
    <div class="tab-pane fade" id="tab_2_6">

        <h4>Enable Daily Order Report?</h4>
        <br/>
        <div class="row">
            <div id="enable-product-report" class="col-md-3">
                {{
                     Form::checkbox('settings[order_report][enabled]', 'true',
                        array_get($settings,'order_report.enabled', false ),
                     [
                        'id' => 'order_report_enabled_input',
                        'class' => 'make-switch',
                        'data-size' => 'small',
                        'data-on-color' => 'success',
                        'data-off-color' => 'default',
                        'data-on-text' => 'Enabled',
                        'data-off-text' => 'Disabled',
                    ]);
                }}
            </div>
        </div>

        <br/>
        <br/>

        {{-- Report Field Selector --}}
        <div class="panel panel-info order_report_container" data-collapsed="0">
            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Choose Report Fields</div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>
            <!-- panel body -->
            <div class="panel-body">

                <div class="note note-warning">
                    <h4 class="block">Select fields to add to the report builder below</h4>
                </div>

                <div class="form-group">

                    <div class="col-md-10" id="field-chooser">
                        <select id="custom-headers" multiple="multiple" name="" class="form-control multi-select">
                            @foreach ($reportFieldSelectionOptions['selectable'] as $key => $field)
                                <option value="{{ $key }}">{{ $field['default_label'] }}</option>
                            @endforeach
                            @if (isset($reportFieldSelectionOptions['selected']))
                                @foreach ($reportFieldSelectionOptions['selected'] as $key => $field)
                                    <option value="{{ $key }}" selected>{{ $field['default_label'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <br/>

        {{-- Report Field Configuration --}}
        <div class="panel panel-info order_report_container" data-collapsed="0" id="order_report">
            <!-- panel head -->
            <div class="panel-heading ">
                <div class="panel-title pull-left">Report Builder</div>
                <div class="panel-action pull-right">
                    <a href="#" class="btn btn-circle btn-sm green-jungle" id="add-custom-field">
                        <i class="fa fa-plus"></i> Custom Field
                    </a>
                </div>
            </div>
            <!-- panel body -->
            <div class="panel-body">
                <div class="row report-field-headers">
                    <div class="col-md-2">
                       <h5 class="">Field Name</h5>
                    </div>
                    <div class="col-md-2">
                       <h5>Enabled</h5>
                    </div>
                    <div class="col-md-4">
                       <h5>Custom Label</h5>
                    </div>
                    <div class="col-md-4">
                       <h5>Custom Value</h5>
                    </div>
                </div>

                <div class="dd" id="nestable_list_3">
                    <ol class="dd-list">
                        <?php $position = 0; ?>
                        @foreach(array_get($settings, 'order_report.field_definitions', []) as $key => $field)
                            <li class="dd-item dd3-item {{ array_get($field, 'formatting') ? 'formatting-item': '' }}" data-id="{{ $key }}" data-src="{{$field['src']}}">
                                <div class="dd-handle dd3-handle">
                                </div>
                                <div class="dd3-content">

                                    {{ Form::hidden("settings[order_report][field_definitions][{$key}][type]", array_get($field,'type', '' ), ['class' => 'form-control', 'id' => "{$key}_type_input"]) }}
                                    {{ Form::hidden("settings[order_report][field_definitions][{$key}][position]", $position++, ['class' => 'form-control', 'id' => "{$key}_position_input"]) }}
                                    {{ Form::hidden("settings[order_report][field_definitions][{$key}][src]", $field['src'], ['class' => 'form-control', 'id' => "{$key}_src_input"]) }}
                                    {{ Form::hidden("settings[order_report][field_definitions][{$key}][default]", $field['default'], ['class' => 'form-control', 'id' => "{$key}_default_input"]) }}
                                    {{ Form::hidden("settings[order_report][field_definitions][{$key}][default_label]", $field['default_label'], ['class' => 'form-control', 'id' => "{$key}_default_label_input"]) }}

                                    <div class="row">


                                        <div class="col-md-2">
                                            <!--  Field Name -->
                                            {{ Form::label($field['src'], $field['default_label']) }}

                                        </div>
                                        <div class="col-md-2">
                                            <!--  Enabled Input -->
                                            {{ Form::checkbox("settings[order_report][field_definitions][{$key}][enabled]", true,
                                                isset($settings['order_report']['field_definitions'][$key]['enabled']) ? $settings['order_report']['field_definitions'][$key]['enabled']: false,
                                                ['class' => 'form-control', 'id' => "{$key}_enabled_input"]) }}
                                        </div>
                                        <div class="col-md-4">
                                            <!--  Field Label Input -->
                                            {{ Form::text("settings[order_report][field_definitions][{$key}][label]",
                                                isset($settings['order_report']['field_definitions'][$key]['label']) ? $settings['order_report']['field_definitions'][$key]['label']: false,
                                                ['class' => 'form-control', 'id' => "{$key}_label_input"]) }}
                                        </div>

                                        <div class="col-md-3">
                                            @if ($field['src'] == 'user_defined')
                                                <!--  Custom Field Value Input -->
                                                {{ Form::text("settings[order_report][field_definitions][{$key}][value]", array_get($settings,"order_report.field_definitions.{$key}.value", '' ), ['class' => 'form-control', 'id' => "{$key}_value_input", "maxlength" => "4", "size" => "4"]) }}
                                            @endif
                                        </div>

                                        <div class="col-md-1">
                                            <button class="delete-button">&#10007;</button>
                                        </div>

                                    </div>

                                @if (array_get($field, 'formatting', true))
                                    <?php $type = array_get($field, 'type'); ?>

                                    <div class="row formatting">
                                        @if (View::exists("admin.companies.partials._{$type}_formatting"))
                                            @include("admin.companies.partials._{$type}_formatting")
                                        @endif
                                    </div>
                                @endif




                                </div>
                            </li>

                        @endforeach
                    </ol>
                </div>




            </div>
        </div>




    </div>
    @endif
</div>