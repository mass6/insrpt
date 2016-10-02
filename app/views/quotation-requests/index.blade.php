@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <style>
        input.search_init.text_filter {
            width: 80%;
        }
    </style>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Quotation Requests'])
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-docs"></i>
                        <span class="caption-subject bold uppercase">Quotation Requests List</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="quotation-requests-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="replace-inputs">
                                <th></th>
                                <th></th>
                                @if(isSiteOwner($currentUser))
                                    <th></th>
                                @endif
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="width: 270px;"></th>
                            </tr>
                            <tr>
                                <th style="width: 116px;">ID</th>
                                <th>Created</th>
                                @if(isSiteOwner($currentUser))
                                    <th>Company</th>
                                @endif
                                <th>Created By</th>
                                <th style="width: 210px;">Supplier</th>
                                <th># Products</th>
                                <th>Status</th>
                                <th>Emailed</th>
                                <th>Email Status</th>
                                <th style="width: 220px;">Options</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Created</th>
                                @if(isSiteOwner($currentUser))
                                    <th>Company</th>
                                @endif
                                <th>Created By</th>
                                <th>Supplier</th>
                                <th># Products</th>
                                <th>Status</th>
                                <th>Emailed</th>
                                <th>Email Status</th>
                                <th>Options</th>
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
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.columnFilter.js') }}"></script>

    <script class="init" type="text/javascript">

        $(document).ready(function() {

            var selectedRecords = [];

            $('.select-record').change( function () {
                if($(this).is(":checked")) {
                    selectedRecords.push($(this).val());
                }
                if(!$(this).is(":checked")) {
                    var searchValue = $(this).val();
                    selectedRecords = $(selectedRecords).not([searchValue]).get();
                }
                if(selectedRecords.length) {
                    $('#submit-action').fadeIn();
                } else {
                    $('#submit-action').fadeOut();
                }
            } );

            $('#action-form').submit( function(e) {
                $("#action-form").attr('action', '/' + $('#actions').val());
                $("#request_ids").val(selectedRecords);
            });

            var el = $('#quotation-requests-table');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

            var table = el.dataTable({
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
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"] // change per page values here
                ],
                "ajax": {
                    "url" : "/quotation-requests",
                    "dataSrc": ""
                },
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "deferRender": true,
                "columns": [
                    { "data": function(row, type, set, meta) {
                        return '<a href="/quotation-requests/' + row.quotation_request_id + '/edit">' +  row.quotation_request_id + '</a>';
                    }},
                    { "data": "created_at" },
                    @if(isSiteOwner($currentUser))
                        { "data": "company" },
                    @endif
                    { "data": "created_by" },
                    { "data": "supplier" },
                    { "data": "quotations" },
                    { "data": "status" },
                    { "data": "emailed" },
                    { "data": "email_delivery_status" },
                    { "data": function(row, type, set, meta) {
                        var txt = '<a href="/quotation-requests/' + row.quotation_request_id + '/edit" class="btn primary-36s btn-sm tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icon-note"></i></a>';
                        txt += '<a href="/quotation-requests/duplicate/' + row.quotation_request_id + '" class="btn btn-primary btn-sm tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Duplicate" onclick="' + 'return confirm("Create a duplicate of this quotation request?")' + '"><i class="icon-plus"></i></a>';
                        txt += '<a href="/product-requests/quotation-request/' + row.quotation_request_id + '" style="margin-left: 3px;">';
                        txt += '<button type="button" class="btn light-36s btn-sm btn-icon icon-right tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="View associated product requests">Product Requests<i class="entypo-right"></i></button></a>';

                        return txt;
                    } }
                ],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "stateSave": true,
                "order": [[1, 'desc']],
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "js/datatables/copy_csv_xls_pdf.swf",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 0,2,3,4,5,6 ]
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "quotation-requests.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 0,2,3,4,5,6 ]
                        }
                    ]
                },
                "aoColumnDefs": [
                    { "bSortable": false, "aTargets": [ 0, 9 ] }
                ]
            });

            var tableWrapper = $('#quotation-requests-table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            table.columnFilter({
                "sPlaceHolder" : "head:after",
                aoColumns: [
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    null,
                ]
            });

        });


    </script>

@stop