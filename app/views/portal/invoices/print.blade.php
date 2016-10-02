@extends('layouts.default.print')

@section('links')
    @parent
    <style>
        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding:0;}
        .table > tbody > tr > td { border-top: none; border-bottom: 1px solid #ebebeb;}
        .table {margin-bottom: 0;}
        .table > tbody > tr > td, .table > thead > tr > th {padding-left: 5px;padding-right: 5px;}
        blockquote {
            padding: 0px 17px;
            margin: 0 0 17px;
            border-left: 5px solid #eeeeee;
        }
    </style>
@stop
@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Invoice # ' . $invoice['invoice_number']])
@stop
@section('content')

    <div class="well">
        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-dark" data-collapsed="0">

                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title">Invoice Details</div>

                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="col-md-6">
                            <blockquote class="">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>Invoice Date:</td>
                                        <td>{{ $invoice['created_at'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Contract:</td>
                                        <td>{{ $invoice['order']['contract'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered No:</td>
                                        <td>{{ $invoice['order']['increment_id'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered By:</td>
                                        <td>{{ $invoice['order']['ordered_by'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered On:</td>
                                        <td>{{ $invoice['order']['created_at'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </blockquote>

                        </div>

                        <div class="col-md-6">
                            <blockquote class="">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>Bill To Address:</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $invoice['order']['bill_to'] }}</td>
                                    <tr>
                                    </tbody>
                                </table>
                            </blockquote>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-dark" data-collapsed="0">

                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title">Invoiced Items</div>

                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <table  id="items-delivered" class="table table-bordered table-hover">
                            <colgroup>
                                <col>
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th class="left">Product</th>
                                <th class="center">SKU</th>
                                <th class="center">Partner Code</th>
                                <th class="right">Qty Ordered</th>
                                <th class="right">Qty Invoiced</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoice['items'] as $item)
                                <tr>
                                    <td class="left">{{ $item['name'] }}</td>
                                    <td class="center">{{ $item['sku'] }}</td>
                                    <td class="center">{{ $item['bp_product_code'] }}</td>
                                    <td class="right">
                                        <span class="order-qty">{{ $invoice['orderQuantities'][$item['sku']]['qty'] }}</span>
                                    </td>
                                    <td class="right">
                                        <span class="order-qty">{{ (int)$item['qty'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
//        window.close();
        });

    </script>

@stop