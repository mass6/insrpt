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
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Request Lists'])
@stop

@section('content')

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-list"></i>
                <span class="caption-subject bold uppercase"> All Lists.</span>
                <span class="caption-helper"></span>
            </div>
            <div class="actions">
                <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
            </div>
        </div>
        <div class="portlet-body">

            <table id="datatable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>List Name</th>
                        <th>Created</th>
                        <th>Created By</th>
                        <th>Requester</th>
                        <th>Company</th>
                        <th># Requests</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product_request_lists as $product_request_list)
                        <tr>
                            <td>{{ $product_request_list->name }}</td>
                            <td>{{ $product_request_list->created_at }}</td>
                            <td>{{ $product_request_list->createdBy->name() }}</td>
                            <td>{{ $product_request_list->requestedBy->name() }}</td>
                            <td>{{ $product_request_list->company->name }}</td>
                            <td>
                                @if($product_request_list->productRequests)
                                    {{ count($product_request_list->productRequests) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td><a href="{{ route('product-request-lists.show', $product_request_list->id) }}" class="btn btn-primary btn-xs">View Details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ URL::asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "sButtonText": "Copy",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "product_request_lists.csv",
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