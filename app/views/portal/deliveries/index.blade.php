@extends($layout)

@section('links')
@parent
        <!-- BEGIN PACE PLUGIN FILES -->
<script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/pace/pace.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/pace/themes/pace-theme-big-counter.css') }}"/>
<!-- END PACE PLUGIN FILES -->
<!-- BEGIN PAGE LEVEL STYLES -->
{{--<link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>--}}
{{--<link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>--}}
<link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
<!-- END PAGE LEVEL STYLES -->

@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Deliveries'])
@stop

@section('content')


    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-docs"></i>
                        <span class="caption-subject bold uppercase">{{ isset($heading) ? $heading : 'Deliveries: ' }}</span>

                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="replace-inputs">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        @if ($showMaterialsReceivedTracking)
                            <th></th>
                            <th></th>
                            <th></th>
                        @endif
                        </tr>
                        <tr>
                            <th>Delivery No.</th>
                            <th>Date Dispatched</th>
                            <th>Order No.</th>
                            <th>Order Status</th>
                        @if ($showMaterialsReceivedTracking)
                            <th>Materials Received</th>
                            <th>Received By</th>
                            <th>Date Received</th>
                        @endif
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Delivery No.</th>
                            <th>Date Dispatched</th>
                            <th>Order No.</th>
                            <th>Order Status</th>
                        @if ($showMaterialsReceivedTracking)
                            <th>Materials Received</th>
                            <th>Received By</th>
                            <th>Date Received</th>
                        @endif
                        </tr>
                        </tfoot>

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
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatables/jquery.dataTables.columnFilter.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <script class="init" type="text/javascript">

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $(document).ready(function() {

            var table = $('#datatable');
            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });
            var oTable = table.dataTable({
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
                    [20, 50, -1],
                    [20, 50, "All"] // change per page values here
                ],
                "ajax": {
                    "url" : "/portal/deliveries",
                    "dataSrc": "data"
                },
                "deferRender": true,
                "aoColumns": [
                    { "data": function(row, type, set, meta) {
                        return '<a href="/portal/deliveries/' + row.increment_id + '">' +  row.delivery_number + '</a>';
                    }},
                    { "data": "date_dispatched" },
                    { "data": function(row, type, set, meta) {
                        return '<a href="/portal/orders/details/' + row.order_id + '">' +  row.order_increment_id + '</a>';
                    }},
                    { "data": "order_status" }
                @if ($showMaterialsReceivedTracking),
                    { "data": "materials_received_note" },
                    { "data": "received_by" },
                    { "data": "received_at" }
                @endif
                ],
                "order": [[1, 'desc']],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": "all"
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "deliveries.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": "all"
                        }
                    ]
                }
            });

            oTable.columnFilter({
                "sPlaceHolder" : "head:after",
                aoColumns: [
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"}
                @if ($showMaterialsReceivedTracking),
                    {type: "text"},
                    {type: "text"},
                    {type: "text"}
                @endif
                ]
            });
        });
    </script>


@stop