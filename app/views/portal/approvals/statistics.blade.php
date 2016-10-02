@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PACE PLUGIN FILES -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/pace/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/pace/themes/pace-theme-big-counter.css') }}"/>
    <!-- END PACE PLUGIN FILES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->

    <style>
        table.users, table.addresses {border:1px solid #dddddd;padding:5px;}
        .users td, .addresses td {border:none;}
        td.details-control1  {
            background: url('{{ URL::asset("js/datatables/resources/details_open.png") }}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown1 td.details-control1 {
            background: url('{{ URL::asset("js/datatables/resources/details_close.png") }}') no-repeat center center;
        }
        tr.row-header {background-color: #DDDDDD !important;}
        td.column-header {width: 100% !important;color:#464646}
        .users tr {border-bottom: 1px solid #ddd;}
        td.col-label {color:#464646;}
        td.col-value {border-right:1px solid #DDDDDD;}
        td.col-label, td.col-value {padding: 5px;}
        table.addresses tr td.col-label {text-decoration: underline;}
    </style>

@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Portal Reports', 'subheading' => 'Approval Statistics'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-graph"></i>
                    <span class="caption-subject bold uppercase">{{ isset($heading) ? $heading : 'Approval Statistics: ' }}</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table id="datatable" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        @foreach ($approvalStatistics['header'] as $header)
            <th>{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach ($approvalStatistics['report'] as $row)
            <tr>
                @foreach ($row as $key => $column)
                    @if($key == 0)
                        <td><a href="/portal/orders/details/{{ $column['entity_id'] }}">{{ $column['increment_id'] }}</a></td>
                    @else
                        <td>{{ $column }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

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
                "deferRender": true,
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ URL::asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "approval-history.csv",
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
