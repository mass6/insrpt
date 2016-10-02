<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-globe"></i>
                    <span class="caption-subject bold uppercase"> {{ isset($group) ? 'Group: ' . $group->name : 'New Group' }}</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <!-- Group Name Form Input -->
                <div class="form-group">
                    {{ Form::label('name', 'name:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                        {{ $errors->first('name', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>

                @if (count($permissions))
                <div class="form-group">
                    <label class="col-sm-3 control-label">Allowed permissions<br/>
                        <small>Selected Permissions on the right</small></label>

                    <div class="col-sm-9">
                        <select multiple="multiple" name="permissions[]" class="form-control multi-select">
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission }}">{{ $permission }}</option>
                            @endforeach
                            @if (isset($groupPermissions))
                                @foreach ($groupPermissions as $permission)
                                    <option value="{{ $permission }}" selected>{{ $permission }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                @endif

                <!-- Buttons -->
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




