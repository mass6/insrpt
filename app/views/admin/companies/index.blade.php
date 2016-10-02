@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Companies'])
@stop

@section('content')

    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-globe"></i>
                <span class="caption-subject bold uppercase"> Companies List</span>
                <span class="caption-helper"></span>
            </div>
            <div class="actions">
                 @if($currentUser->hasAccess('admin.companies.create'))
                     <a href="{{ route('admin.companies.create') }}" class="btn btn-circle blue ">
                        <i class="fa fa-plus"></i> Add new company
                    </a>
                @endif
                <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            @if(Session::has('is_deleted') && !Session::get('is_deleted'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p>Cannot delete company. All users associated to this company must be deleted before it can be deleted.
                </div>
                <?php Session::forget('is_deleted');?>
            @endif
            @if ($companies->count())
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th width="130px">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->type }}</td>
                        <td>{{ $company->notes }}</td>
                        <td>
                            {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.companies.destroy', $company->id))) }}
                            {{ link_to_route('admin.companies.edit', 'Edit', array($company->id), array('class' => 'btn btn-sm btn-primary')) }}
                            @if($currentUser->hasAccess('admin.companies.delete'))
                                {{ Form::submit('Delete', array('class' => 'btn btn-sm red-thunderbird', 'Onclick'=>'return confirm("Are you sure you want to delete this company?")')) }}
                                {{ Form::close() }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
    <!-- END Portlet PORTLET-->

@stop

@section('bottomlinks')
    @parent
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <script class="init" type="text/javascript">
        $(document).ready(function() {

            var table = $('#datatable');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

            var oTable = table.DataTable({
                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found"
                },
                "lengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                "pageLength": 10,
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "order": [[0, 'asc']],
                "stateSave": true,
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "print",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "pdf",
                            "sFileName": "companies.pdf",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "companies.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        }
                    ]
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
        });
    </script>

@stop