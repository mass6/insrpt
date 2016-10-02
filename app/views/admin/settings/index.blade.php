@extends($layout)

@section('links')
    @parent
     <link href="{{ asset('metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
    <link href="{{ asset('js/tag-it/jquery.tagit.css') }}" rel="stylesheet" type="text/css">
@stop
@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'System Settings'])
@stop

@section('content')



<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>
                    <span class="caption-subject bold uppercase"> Settings</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

            {{ Form::open(['route' => ['admin.settings.update'], 'class' => 'form-horizontal form-groups-bordered validate', 'method' => 'patch', 'role' => 'form']) }}

                <div class="row">


                        <div class="col-md-12">

                            <ul class="nav nav-tabs right-aligned"><!-- available classes "bordered", "right-aligned" -->
                                <li class="active">
                                    <a href="#general-settings" data-toggle="tab">
                                        <span class="visible-xs"><i class="entypo-vcard"></i></span>
                                        <span class="hidden-xs">General</span>
                                    </a>
                                </li>
                                {{--<li>--}}
                                    {{--<a href="#sourcing-requests-settings" data-toggle="tab">--}}
                                        {{--<span class="visible-xs"><i class="entypo-cog"></i></span>--}}
                                        {{--<span class="hidden-xs">Sourcing Requests</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                <li>
                                    <a href="#catalog-requests-settings" data-toggle="tab">
                                        <span class="visible-xs"><i class="entypo-address"></i></span>
                                        <span class="hidden-xs">Catalog Requests</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                {{-- Begin General Settings--}}
                                <div class="tab-pane active" id="general-settings">

                                    <div class="col-md-12">

                                        <div class="panel panel-info" data-collapsed="0">

                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    General Settings
                                                </div>

                                                <div class="panel-options">
                                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                                                </div>
                                            </div>

                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Site Owner</label>

                                                    <div class="form-group col-sm-5">
                                                        <!-- Site owner Form Input -->
                                                        {{
                                                            Form::select('settings[site_owner]',
                                                            $companies, array_get($settings, 'site_owner', ''),
                                                            ['class' => 'form-control', 'id' => 'site_owner_input'])
                                                        }}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Site Name</label>

                                                    <div class="form-group col-sm-5">
                                                        <!-- Site owner Form Input -->
                                                        {{
                                                            Form::text('settings[site_name]',
                                                            array_get($settings, 'site_name', ''),
                                                            ['class' => 'form-control', 'id' => 'site_name_input'])
                                                        }}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Default Public Holidays</label>

                                                    <div class="form-group col-sm-5">
                                                        {{
                                                            Form::hidden('settings[default_public_holidays]',
                                                            array_get($settings, 'default_public_holidays', ''),
                                                            ['class' => 'form-control', 'id' => 'default_public_holidays'])
                                                        }}
                                                        <ul id="tags" style="margin-left: 0">
                                                            @if(array_get($settings, 'default_public_holidays'))
                                                                @foreach(explode(',', array_get($settings, 'default_public_holidays')) as $day)
                                                                    <li>{{ $day }}</li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="panel panel-info" data-collapsed="0">

                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    System Notifications
                                                </div>

                                                <div class="panel-options">
                                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                                                </div>
                                            </div>

                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Send portal data updates?</label>

                                                    <div class="col-sm-5" data-on-label="Enabled" data-off-label="dissed">

                                                        {{
                                                            Form::checkbox('settings[notifications][send_portal_data_update_notifications]', 'true',
                                                            array_get($settings,'notifications.send_portal_data_update_notifications', false ),
                                                             [
                                                                'id' => 'send_portal_data_update_notifications_input',
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

                                            </div>

                                        </div>

                                        <div class="panel panel-info" data-collapsed="0">

                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    Live Search
                                                </div>

                                                <div class="panel-options">
                                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                                                </div>
                                            </div>

                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Enable Algolia Search?</label>

                                                    <div class="col-sm-5" data-on-label="Enabled" data-off-label="dissed">

                                                        {{
                                                            Form::checkbox('settings[live_search][enabled]', 'true',
                                                            array_get($settings,'live_search.enabled', false ),
                                                             [
                                                                'id' => 'live_search_enabled_input',
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

                                            </div>

                                        </div>

                                        <div class="panel panel-info" data-collapsed="0">

                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    Dashboards
                                                </div>

                                                <div class="panel-options">
                                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                                    <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                                                </div>
                                            </div>

                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Parent Category ID:</label>

                                                    <div class="col-sm-5">
                                                        <div>
                                                            {{
                                                                Form::text('settings[dashboards][parent_category]',
                                                                array_get($settings, 'dashboards.parent_category', ''),
                                                                ['class' => 'form-control', 'id' => 'parent_category_input'])
                                                            }}

                                                        </div>
                                                    </div>

                                                    <div class="btn-group">
														<button type="button" class="btn btn-primary">View All Category IDs</button>
														<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
														<ul id="categories_list" class="dropdown-menu" role="menu">
															@foreach($categoriesList as $category)
                                                                <li id="{{$category['id']}}"><a><label>{{$category['name'].' - ID: '.$category['id']}}</label></a></li>
                                                            @endforeach
														</ul>
													</div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                {{-- end General Settings --}}

                                {{-- Begin Sourcing Request Settings--}}
                                <div class="tab-pane" id="sourcing-requests-settings">

                                </div>
                                {{-- end Sourcing Request Settings--}}

                                {{-- Begin Catalouging Request Settings--}}
                                <div class="tab-pane" id="catalog-requests-settings">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">CSV Import Headers<br/><em>( JSON object )</em></label>

                                        <div class="form-group col-sm-5">
                                            <!-- Site owner Form Input -->
                                            {{
                                                Form::textarea('settings[catalog_requests][import_csv_headers]',
                                                array_get($settings, 'catalog_requests.import_csv_headers', ''),
                                                ['class' => 'form-control', 'id' => 'import_csv_headers_input'])
                                            }}
                                        </div>
                                    </div>

                                </div>
                                {{-- end Catalouging Request Settings--}}
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{ Form::submit(isset($submit)?$submit:'Save Changes', ['class' => 'btn btn-primary']) }}
                             <!-- Reset button -->
                            {{ link_to_route('admin.settings.index', 'Reset', null, array('type' => 'reset', 'class'=>'btn btn-default')) }}
                        </div>


                </div>

                {{ Form::close() }}

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


@stop

@section('bottomlinks')
    @parent
    <script src="{{ asset('metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('js/tag-it/tag-it.min.js') }}"></script>
    <script>
        $('#categories_list li').click(function(e){
            $('#parent_category_input').val($(this).attr('id'));
        });
        var onHolidaysChanged = function () {
            var holidays = $("#tags").tagit("assignedTags");
            $('#default_public_holidays').val(holidays.join(','));
        }
        $('#tags').tagit({
            afterTagAdded: onHolidaysChanged,
            afterTagRemoved: onHolidaysChanged
        });
    </script>

@stop