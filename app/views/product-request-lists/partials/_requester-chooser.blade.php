<div class="row">

    <div class="col-md-8">

        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet box blue-chambray">
            <div class="portlet-title">
                <div class="caption">
                    Upload on behalf of another user?
                </div>
                <div class="tools">
                    <span class="caption-helper"> Click to expand</span>
                    <a href="javascript:;" class="expand" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div id="requester-list" class="portlet-body display-hide">

                <div class="row">

                    <div class="col-md-6">
                        <!-- Company id Form Input -->
                        {{ Form::label('company_id', 'Company:') }}
                        <div class="input-group">
                            {{ Form::select('company_id', $companies, null, ['class' => 'form-control', 'id' => 'company_id_input', 'disabled']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Company id Form Input -->
                        {{ Form::label('requested_by_id', 'Requester:') }}
                        <div class="input-group">
                            {{ Form::select('requested_by_id', isset($requesterList) ? $requesterList : [], null, ['class' => 'form-control', 'id' => 'requested_by_id_input', 'disabled']) }}
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- END Portlet PORTLET-->

    </div>

</div>

