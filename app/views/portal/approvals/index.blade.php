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
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Order Reports'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-hourglass"></i>
                    <span class="caption-subject bold uppercase">Orders Pending Approval</span>
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
                            <th>Id</th>
                            <th>Weborder</th>
                            <th>Total</th>
                            <th>Customer</th>
                            <th>Ordered By</th>
                            <th>Contract</th>
                            <th>Ordered On</th>
                            <th>Current Lead Time (hrs)</th>
                            <th>Total Lead Time (hrs)</th>
                            <th>Current Approver</th>
                            <th>Last Approver</th>
                            <th>Custom Field 1</th>
                            <th>Custom Field 2</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <td></td>
                            <th style="text-align:right">Total:</th>
                            <th colspan="2"></th>
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
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <script class="init" type="text/javascript">

        var reportname = "<?php echo $reportName; ?>";

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
                    [20, 50, -1],
                    [20, 50, "All"] // change per page values here
                ],
                "ajax": {
                    "url" : "/portal/orders/approvals",
                    "dataSrc": ""
                },
                "deferRender": true,
                "columns": [
                    { "data": "entity_id", "visible":false },
                    { "data": function(row, type, set, meta) {
                        return '<a href="/portal/orders/details/' + row.entity_id + '">' +  row.weborder + '</a>';
                    }},
                    { "data": "total" },
                    { "data": "customer" },
                    { "data": "ordered_by" },
                    { "data": "contract" },
                    { "data": "created_at" },
                    { "data": "lead_time_hours" },
                    { "data": "total_lead_time_hours" },
                    { "data": "current_approver" },
                    { "data": "last_approver"},
                    { "data": "custom_ref1" },
                    { "data": "custom_ref2" }
                ],
                "order": [[7, 'desc']],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ URL::asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "sRowSelect": "multi",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 1,2,3,4,5,6,7,8,9,10,11,12 ]
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "orders_pending_approval.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 1,2,3,4,5,6,7,8,9,10,11,12 ]
                        }
                    ]
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                        i : 0;
                    };

                    // Total over all pages
                    data = api.column( 2 ).data();
                    total = data.length ?
                            data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } ) :
                            0;

                    // Total over this page
                    data = api.column( 2, { page: 'current'} ).data();
                    pageTotal = data.length ?
                            data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } ) :
                            0;

                    console.log(pageTotal);
                    // Update footer
                    $( api.column( 12 ).footer() ).html(
                            'Showing AED '+ numberWithCommas(Math.round(pageTotal*100)/100) +
                            '<br/> of  AED '+ numberWithCommas(Math.round(total*100)/100) +' Total'
                    );
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown



        });
    </script>
@stop