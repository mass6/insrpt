@extends($layout)

@section('links')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/datatables/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css">
    {{--<style>--}}
        {{--table.users, table.addresses {border:1px solid #dddddd;}--}}
        {{--.users td, .addresses td {border:none;}--}}
        {{--td.details-control1  {--}}
            {{--background: url('{{ URL::asset("js/datatables/resources/details_open.png") }}') no-repeat center center;--}}
            {{--cursor: pointer;--}}
        {{--}--}}
        {{--tr.shown1 td.details-control1 {--}}
            {{--background: url('{{ URL::asset("js/datatables/resources/details_close.png") }}') no-repeat center center;--}}
        {{--}--}}
        {{--tr.row-header {background-color: #DDDDDD !important;}--}}
        {{--td.column-header {width: 100% !important;color:#464646}--}}
        {{--.users tr {border-bottom: 1px solid #ddd;}--}}
        {{--td.col-label {color:#464646;}--}}
        {{--td.col-value {border-right:1px solid #DDDDDD;}--}}
        {{--table.addresses tr td.col-label {text-decoration: underline;}--}}
    {{--</style>--}}

<script class="init" type="text/javascript">

//    /* Formatting function for row details - modify as you need */
//    function format ( d ) {
//        // `d` is the original data object for the row
//        return '<table class="addresses"  cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
//            '<tr class="row-header">'+
//            '<td class="col-label">Customer UOM</td>'+
//            '<td class="col-label">Customer Price</td>'+
//            '<td class="col-label">Customer Price Currency</td>'+
//            '<td class="col-label">36S Product Code</td>'+
//            '<td class="col-label">36S Product Name</td>'+
//            '<td class="col-label">36S UOM</td>'+
//            '<td class="col-label">36S Buy Price</td>'+
//            '<td class="col-label">36S Buy Price Currency</td>'+
//            '<td class="col-label">36S Supplier</td>'+
//            '<td class="col-label">36S Sell Price</td>'+
//            '<td class="col-label">36S Sell Price Currency</td>'+
//            '<td class="col-label">36S Margin on Buy Price</td>'+
//            '<td class="col-label">36S Margin on Sell Price</td>'+
//            '<td class="col-label">Customer Margin on Sell Price</td>'+
//            '<td class="col-label">Date Received</td>'+
//            '<td class="col-label">Created By</td>'+
//            '<td class="col-label">Closed At</td>'+
//            '<td class="col-label">Reason For Closing</td>'+
//            '</tr>'+
//            '<tr>'+
//            '<td class="col-value">'+'Each'+'</td>'+
//            '<td class="col-value">'+'55.00'+'</td>'+
//            '<td class="col-value">'+'AED'+'</td>'+
//            '<td class="col-value">'+'SKU-1903'+'</td>'+
//            '<td class="col-value">'+'Coffee Cups'+'</td>'+
//            '<td class="col-value">'+'Pack (12)'+'</td>'+
//            '<td class="col-value">'+'22.00'+'</td>'+
//            '<td class="col-value">'+'AED'+'</td>'+
//            '<td class="col-value">'+'Acme Trading LLC.'+'</td>'+
//            '<td class="col-value">'+'46.00'+'</td>'+
//            '<td class="col-value">'+'AED'+'</td>'+
//            '<td class="col-value">'+'23.00'+'</td>'+
//            '<td class="col-value">'+'23.00'+'</td>'+
//            '<td class="col-value">'+'9.00'+'</td>'+
//            '<td class="col-value">'+'2015-June-15'+'</td>'+
//            '<td class="col-value">'+'Jess Richards'+'</td>'+
//            '<td class="col-value">'+''+'</td>'+
//            '<td class="col-value">'+''+'</td>'+
//            '</tr>'+
//            '</table>';
//    }

    $(document).ready(function() {
        var table = $('#datatable').dataTable({
            "sPaginationType": "bootstrap",
            "stateSave": true,
            "sDom": "<'row'<'col-xs-6 col-left'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
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

        table.columnFilter({
			"sPlaceHolder" : "head:after",
			aoColumns: [
			                null,
                            {
                                 type: "text"
                            },
                            {
                                 type: "select"
                            },
                            {type: "text"},
                            {type: "text"},
                            {
                                type: "select",
                                values: ['Assessing','Sourcing','Pricing','Closed']
                            },
                            {type: "select"},
                            null,
                            null
                         ]
		});
    });
</script>


@stop

@section('content')

{{--<a href="{{URL::route('catalogue.product-definitions.create')}}" class="pull-right">--}}
    {{--<button type="button" class="btn btn-info btn-icon icon-left">--}}
        {{--New Request--}}
        {{--<i class="entypo-plus"></i>--}}
    {{--</button>--}}
{{--</a>--}}
<h1>{{ $filterName or "All Sourcing Requests" }}</h1>
<p>
    {{ link_to_route('sourcing-requests.create', 'Create New Request', null, ['class' => 'btn btn-primary']) }}
    {{ link_to_route('sourcing-requests.import.create', 'Import Wizard', null, ['class' => 'btn btn-black']) }}
</p>
    <br/>

    <table class="table table-bordered" id="datatable">
	<thead>
		<tr class="replace-inputs">
		    <th>#</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Status</th>
            <th></th>
            <th></th>
            <th></th>
		</tr>
		<tr>
			<th>#</th>
            <th>Batch Reference</th>
            <th>Customer</th>
            <th>Customer SKU</th>
            <th>Product Description</th>
            <th>Status</th>
            <th>Assigned</th>
            <th>Received</th>
            <th>Options</th>
		</tr>
	</thead>
	<tbody>
		@foreach($sourcing_requests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->batch }}</td>
                <td>{{ $request->customer->name }}</td>
                <td>{{ $request->customer_sku }}</td>
                <td>{{ $request->customer_product_description }}</td>
                <td><span class="{{ statusLabel($request->status) }}">{{ $request->statusName() }}</span></td>
                <td>{{{ $request->assigned_to_id ? $request->assignedTo->name() : null }}}</td>
                <td>{{ $request->received_on->format('d-M-y') }}</td>
                <td align="center">
                    <a href="{{ url("/sourcing-requests/{$request->id}/edit") }}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>#</th>
            <th>Batch Reference</th>
            <th>Customer</th>
            <th>Customer SKU</th>
            <th>Product Description</th>
            <th>Status</th>
            <th>Assigned</th>
            <th>Received</th>
            <th>Options</th>
		</tr>
	</tfoot>
</table>
    <div>
        {{--{{ $requests->links() }}--}}
    </div>


{{--<script type="text/javascript">--}}
    {{--function btnClicked(elname, id)--}}
    {{--{--}}
        {{--if ( document.getElementById(elname).value == "view" )--}}
        {{--{--}}
            {{--window.location="{{ url('/catalogue/product-definitions/') }}" + "/" + id--}}
        {{--}--}}
        {{--else if ( document.getElementById(elname).value == "edit" )--}}
        {{--{--}}
            {{--window.location="{{ url('/catalogue/product-definitions/') }}" + "/" + id + "/edit"--}}
        {{--}--}}
        {{--else if ( document.getElementById(elname).value == "assign-quotation" )--}}
        {{--{--}}
            {{--alert('This functionality is to be added...')--}}
{{--//            window.location="{{ url('/quotations/create') }}" + "/" + id--}}
        {{--}--}}
    {{--}--}}
{{--</script>--}}

@include('portal.partials._datatables')

@stop