<table id="datatable" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="{{  $headerRowSpan }}">Name</th>
            <th rowspan="{{  $headerRowSpan }}">SKU</th>
            <th rowspan="{{  $headerRowSpan }}">UOM</th>
            <th rowspan="{{  $headerRowSpan }}">Price</th>
            <th colspan="{{ count($periods) ? count($periods) : 1 }}" class="center">Purchases</th>
        </tr>
        @if (count($periods))
            <tr>
                @foreach($periods as $period)
                    <td>{{ $period }}</td>
                @endforeach
            </tr>
        @endif
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['sku'] }}</td>
                <td>{{ $product['uom'] }}</td>
                <td class="right">{{ $product['price'] }}</td>
                @foreach($periods as $period)
                    <td class="right">{{ isset($product['qty_ordered'][$period]) ? $product['qty_ordered'][$period] : 0 }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $.extend(true, $.fn.DataTable.TableTools.classes, {
        "container": "btn-group tabletools-btn-group pull-right",
        "buttons": {
            "normal": "btn btn-sm default",
            "disabled": "btn btn-sm default disabled"
        }
    });
    $('#datatable').DataTable({
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
        "columnDefs": [
            {"width": "20%", "targets": 0}, // product name column width: 20%
            {"width": "10%", "targets": 1}, // product sku column width: 10%
            {"width": "10%", "targets": 2}, // uom column width: 10%
            {"width": "10%", "targets": 3}, // price column width: 10%
        ],
        "oTableTools": {
            "sSwfPath": "{{ URL::asset('js/datatables/copy_csv_xls_pdf.swf') }}",
            "aButtons": [
                {
                    "sExtends": "copy",
                    "oSelectorOpts": { filter: "applied", order: "current" }
                },
                {
                    "sExtends": "pdf",
                    "sFileName": "products-ordered.pdf",
                    "oSelectorOpts": { filter: "applied", order: "current" },
                },
                {
                    "sExtends": "csv",
                    "sFileName": "products-ordered.csv",
                    "oSelectorOpts": { filter: "applied", order: "current" },
                }
            ]
        },
        "sPaginationType": "bootstrap",
        "pagingType": "full_numbers",
        "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
    });
    $('#datatable_wrapper').find('.dataTables_length select').select2(); // initialize select2 dropdown
    $('#filter-form button').removeAttr('disabled').text('Search');
</script>