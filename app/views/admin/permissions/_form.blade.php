<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-globe"></i>
                    <span class="caption-subject bold uppercase"> {{ isset($permission) ? 'Permission: ' . $permission->name() : 'New Permission' }}</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <!-- Permission Name Form Input -->
                <div class="form-group">
                    {{ Form::label('name', 'name:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                        {{ $errors->first('name', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-3">
                        {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn primary-36s']) }}
                        {{ link_to_route('admin.permissions.index', 'Cancel', null, array('class'=>'btn red')) }}
                    </div>
                </div>

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


