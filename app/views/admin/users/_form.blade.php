<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-globe"></i>
                    <span class="caption-subject bold uppercase"> {{ isset($user) ? 'Name: ' . $user->name() : 'New Company' }}</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <!-- First_name Form Input -->
                <div class="form-group">
                    {{ Form::label('first_name', 'First_name:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('first_name', null, ['class' => 'form-control']) }}
                        {{ $errors->first('first_name', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>

                <!-- Last_name Form Input -->
                <div class="form-group">
                    {{ Form::label('last_name', 'Last_name:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('last_name', null, ['class' => 'form-control']) }}
                        {{ $errors->first('last_name', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>

                <!-- Email Form Input -->
                <div class="form-group">
                    {{ Form::label('email', 'Email:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::text('email', null, ['class' => 'form-control']) }}
                        {{ $errors->first('email', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>

                <!-- Password Form Input -->
                <div class="form-group">
                    {{ Form::label('password', 'Password:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::password('password', ['class' => 'form-control']) }}
                        {{ $errors->first('password', '<span class="text text-danger">* :message</span>') }}
                    </div>
                </div>


                <!-- Company Form Input -->
                <div class="form-group">
                    {{ Form::label('company', 'Company:', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('company_id', $companies, null, ['class' => 'form-control']) }}
                    </div>
                </div>

                @if (isset($is_created))
                <!-- Password Form Input -->
                <div class="form-group">
                    {{ Form::label('primary_contact', '', ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::checkbox('primary_contact', true, isset($user) ? $user->primary_contact : false, array('id'=>'primary_contact')) }}
                        {{ Form::label('primary_contact', 'Make this user The Primary Contact') }}
                    </div>
                </div>
                @endif

                @if (count($groups))
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Assigned groups<br/>
                            <small>Selected groups on the right</small></label>

                        <div class="col-sm-7">
                            <select multiple="multiple" name="groups[]" class="form-control multi-select" id="groups_select">
                                @foreach ($groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                                @if (isset($userGroups))
                                @foreach ($userGroups as $group)
                                <option value="{{ $group }}" selected>{{ $group }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-sm-3 control-label">Allowed permissions<br/>
                        <small>Allowed permissions on the right</small></label>

                    <div class="col-sm-7">
                        <select multiple="multiple" name="permissions_allowed[]" class="form-control multi-select">
                            @if (isset($allowedPermissionsDiff))
                                @foreach ($allowedPermissionsDiff as $permission)
                                    <option value="{{ $permission }}">{{ $permission }}</option>
                                @endforeach
                            @endif
                            @if (isset($allowedPermissions))
                                @foreach ($allowedPermissions as $permission)
                                    <option value="{{ $permission }}" selected>{{ $permission }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Denied permissions<br/>
                        <small>Denied permissions on the right</small></label>

                    <div class="col-sm-7">
                        <select multiple="multiple" name="permissions_denied[]" class="form-control multi-select">
                            @if (isset($deniedPermissionsDiff))
                                @foreach ($deniedPermissionsDiff as $permission)
                                    <option value="{{ $permission }}">{{ $permission }}</option>
                                @endforeach
                            @endif
                            @if (isset($deniedPermissions))
                                @foreach ($deniedPermissions as $permission)
                                    <option value="{{ $permission }}" selected>{{ $permission }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <hr/>
                <h4>System Notifications</h4>

                {{-- Notification Settings --}}
                <div class="form-group">
                    <label class="col-sm-3 control-label">Subscribed Notifications<br/>
                        <small>Selected notifications on the right</small></label>

                    <div class="col-sm-7">
                        <select multiple="multiple" name="notifications[]" class="form-control multi-select">
                            @if (isset($unSubscribedNotifications))
                                @foreach ($unSubscribedNotifications as $id => $notification)
                                    <option value="{{ $id }}">{{ $notification }}</option>
                                @endforeach
                            @endif
                            @if (isset($subscribedNotifications))
                                @foreach ($subscribedNotifications as $id => $notification)
                                    <option value="{{ $id }}" selected>{{ $notification }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <hr/>

                <!-- if creating new user -->

                    <!-- Send_email Form Input -->
                    <div class="form-group">
                        {{ Form::label('send_email', $submit === 'Submit' ? 'Send welcome email:' : 'Resend Credentials', ['class' => 'col-sm-3 control-label']) }}
                        <div class="col-sm-5">
                            {{ Form::checkbox('send_email', true, false, ['class' => 'form-control']) }}
                        </div>
                    </div>



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