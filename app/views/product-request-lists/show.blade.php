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
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Request List: <strong>' . $product_request_list->name . '</strong>'])
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
                        <th>Req No.</th>
                        <th>Product Description</th>
                        <th>UOM</th>
                        <th>Customer</th>
                        <th>Date Requested</th>
                        <th>List</th>
                        <th>Volume</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product_request_list->productRequests as $product_request)
                        <tr>
                            <td><a href="{{route('product-requests.show', $product_request->request_id)}}">{{ $product_request->request_id }}</a></td>
                            <td>{{ $product_request->product_description }}</td>
                            <td>{{ $product_request->uom }}</td>
                            <td>{{ $product_request->requestedBy->company->name }}</td>
                            <td>{{ $product_request->created_at}}</td>
                            <td>
                                @if($product_request->productRequestList)
                                    <a href="{{ route('product-requests.index', ['filter' => 'list_id', 'value' => $product_request->list_id ]) }}">{{$product_request->productRequestList->name}}</a>
                                @endif
                            <td>{{ $product_request->volume_requested }}</td>
                            <td>{{ $product_request->currentStateLabel() }}</td>
                            <td><a href="{{route('product-requests.edit', $product_request->request_id)}}" class="btn btn-info btn-xs">Edit</a></td>
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
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                "stateSave" : true,
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "js/datatables/copy_csv_xls_pdf.swf",
                    "aButtons": [
                        "print",
                        {
                            "sExtends": "pdf",
                            "sFileName": "sourcing-requests.pdf"
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "sourcing-requests.csv"
                        },
                        {
                            "sExtends": "xls",
                            "sFileName": "sourcing-requests.xls"
                        }
                    ]
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

        });
    </script>
@stop