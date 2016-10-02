{{ Form::model($company,
    ['route' => ['company-settings.update', $company->id],
    'method' => 'PATCH',
    'id' => 'company-settings-form',
    'class' => 'form-horizontal form-groups-bordered'
    ])
}}

<br/>

<div>
    {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn btn-primary']) }}
    {{ link_to_route('admin.companies.index', 'Cancel', null, array('class'=>'btn btn-default')) }}
</div>

<div class="clear"></div>
<br/>
<br/>


<div class="panel panel-info" data-collapsed="0">

    <!-- panel head -->
    <div class="panel-heading">
        <div class="panel-title">Approvals</div>

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

        <h4>Approvers</h4>

        {{-- Approver 1--}}
        <h5>Approver 1</h5>

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
                    Form::select('settings[product-requests][approvers][approver1][approver_id]', $userlist,
                    array_get($settings, 'product-requests.approvers.approver1.approver_id', null),
                    ['class' => 'form-control', 'id' => 'settings_product_requests_approvers_approver1_approver_id_input'])
                }}
            </div>
        </div>

        <div class="clear"></div>
        <hr/>

        {{-- Approver 2--}}
        <h5>Approver 2</h5>

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
                    Form::select('settings[product-requests][approvers][approver2][approver_id]', $userlist,
                    array_get($settings, 'product-requests.approvers.approver2.approver_id', null),
                    ['class' => 'form-control', 'id' => 'settings_product_requests_approvers_approver2_approver_id_input'])
                }}
            </div>
        </div>

        <div class="clear"></div>
        <hr/>
    </div>

</div>


{{-- only allow editing of reference fields if functionality is enabled for this customer --}}
@if(array_get($settings, 'product-requests.references.enabled'))
{{ Form::hidden('settings[product-requests][references][enabled]', 'true') }}

<div id="reference-fields" class="panel panel-info" data-collapsed="0">

    <!-- panel head -->
    <div class="panel-heading">
        <div class="panel-title">Product Reference Fields</div>

        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
        </div>
    </div>

    <!-- panel body -->
    <div class="panel-body">

        {{-- Reference 1--}}


        <h4>Reference Field 1</h4>

        <div class="row">
            <div class="col-md-3">
                <div class="make-switch switch-small" data-on-label="Enabled" data-off-label="Disabled"
                     data-on="success" data-off="warning" style="width:115px;">
                    {{
                        Form::checkbox('settings[product-requests][reference1][enabled]', 'true',
                        array_get($settings,'product-requests.reference1.enabled', false ),
                         ['id' => 'reference1_enabled_input'])
                    }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox checkbox-replace color-blue">
                    {{
                        Form::checkbox('settings[product-requests][reference1][required]', 'true',
                        array_get($settings,'product-requests.reference1.required', false ),
                         ['id' => 'reference1_enabled_input'])
                    }}
                    <label>Required</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                    </i>
                </div>
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
                <div class="make-switch switch-small" data-on-label="Enabled" data-off-label="Disabled"
                     data-on="success" data-off="warning" style="width:115px;">
                    {{
                        Form::checkbox('settings[product-requests][reference2][enabled]', 'true',
                        array_get($settings,'product-requests.reference2.enabled', false ),
                         ['id' => 'reference2_enabled_input'])
                    }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox checkbox-replace color-blue">
                    {{
                        Form::checkbox('settings[product-requests][reference2][required]', 'true',
                        array_get($settings,'product-requests.reference2.required', false ),
                         ['id' => 'reference2_enabled_input'])
                    }}
                    <label>Required</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                    </i>
                </div>
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
                <div class="make-switch switch-small" data-on-label="Enabled" data-off-label="Disabled"
                     data-on="success" data-off="warning" style="width:115px;">
                    {{
                        Form::checkbox('settings[product-requests][reference3][enabled]', 'true',
                        array_get($settings,'product-requests.reference3.enabled', false ),
                         ['id' => 'reference3_enabled_input'])
                    }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox checkbox-replace color-blue">
                    {{
                        Form::checkbox('settings[product-requests][reference3][required]', 'true',
                        array_get($settings,'product-requests.reference3.required', false ),
                         ['id' => 'reference3_enabled_input'])
                    }}
                    <label>Required</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                    </i>
                </div>
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
                <div class="make-switch switch-small" data-on-label="Enabled" data-off-label="Disabled"
                     data-on="success" data-off="warning" style="width:115px;">
                    {{
                        Form::checkbox('settings[product-requests][reference4][enabled]', 'true',
                        array_get($settings,'product-requests.reference4.enabled', false ),
                         ['id' => 'reference4_enabled_input'])
                    }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox checkbox-replace color-blue">
                    {{
                        Form::checkbox('settings[product-requests][reference4][required]', 'true',
                        array_get($settings,'product-requests.reference4.required', false ),
                         ['id' => 'reference4_enabled_input'])
                    }}
                    <label>Required</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="If selected, user's will be required to fill in this field when submitting product requests.">
                    </i>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Ref Form Input -->
                {{ Form::text('settings[product-requests][reference4][label]', array_get($settings,'product-requests.reference4.label', '' ), ['class' => 'form-control', 'id' => 'reference4_input', 'placeholder' => 'Field name']) }}
            </div>
        </div>
        <div class="clear"></div>


    </div>

</div>
@endif

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
                    array_get($settings,'product-requests.procurement-categories', null ),
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

<br/>

<div>
    {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn btn-primary']) }}
    {{ link_to_route('admin.companies.index', 'Cancel', null, array('class'=>'btn btn-default')) }}
</div>

{{ Form::close() }}