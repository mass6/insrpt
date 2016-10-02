@extends($layout)

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
        #items-ordered th, #items-ordered td {text-align: right;}
        #items-ordered td.left, #items-ordered th.left {text-align: left;}
        .right {text-align: right;}
        #items-ordered td.center, #items-ordered th.center {text-align: center;}

    </style>

@stop
@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Order Details - #' . $order['increment_id'] . ' | ' . $order['created_at'] ])
@stop

<?php
$fields = explode('<br/>',$order['ship_to']);
if(count($fields) > 4 &&
    ($fields[count($fields)-1] == '01' ||
        $fields[count($fields)-1] == '02' ||
        $fields[count($fields)-1] == '10' ||
        $fields[count($fields)-1] == '11')){
    array_pop($fields);
    $order['ship_to'] = implode('<br/>',$fields);
};?>


@section('content')

<div class="tabbable tabs-left">
    <span id="about-this-order" class="pull-left">About This Order:</span>
    <ul class="nav nav-tabs order-details">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                Order Information </a>
        </li>
        @if($currentUser->hasAccess('portal.deliveries') && isset($order['deliveries']) && count($order['deliveries']))
        <li>
            <a href="#tab_2" data-toggle="tab">
                Deliveries </a>
        </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">

            <div class="well">
                <div class="row">

                    <div class="col-md-12">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Order #{{ $order['increment_id'] }}</div>

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
                                                <td>Order Date:</td>
                                                <td>{{ $order['created_at'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status:</td>
                                                <td>{{ $order['status'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Actual Delivery Date:</td>
                                                <td>{{ $order['actual_delivery_date'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Contract:</td>
                                                <td>{{ $order['code'] . ' - ' . $order['contract'] }}</td>
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
                                                <td>Ordered by:</td>
                                                <td>{{ $order['ordered_by'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td>{{ $order['customer_email'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Approved by:</td>
                                                <td>{{ $order['approved_by'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Approved at:</td>
                                                <td>{{ $order['approved_at'] }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Billing Information</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                                </div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">

                                <table class="">
                                    <tbody>
                                    <tr>
                                        <td>{{ $order['bill_to'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Delivery Information</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                                </div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">

                                <table class="">
                                    <tbody>
                                    <tr>
                                        <td>{{ $order['ship_to'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Items Ordered</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                                </div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">
                                <table  id="items-ordered" class="table table-bordered table-hover">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th class="left">Product</th>
                                        <th class="left">Supplier</th>
                                        <th>Price</th>
                                        <th>UOM</th>
                                        <th>Qty</th>
                                        <th>Row Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($order['items'] as $item)
                                        <tr>
                                            <td class="left">SKU: {{ $item['sku'] }}<br/>{{ $item['name'] }}</td>
                                            <td class="left">{{ $item['supplier'] }}</td>
                                            <td class="right">{{ $item['price'] }}</td>
                                            <td>{{ $item['uom'] }}</td>
                                            <td>
                                                <span class="order-qty">Ordered: <strong>{{ $item['qty'] }}</strong></span>
                                                @if ($currentUser->hasAccess('portal.deliveries') && isset($order['deliveryTotals'][$item['sku']]))
                                                <span class="order-qty">Delivered: <strong>{{ $order['deliveryTotals'][$item['sku']] }}</strong></span>
                                                @endif
                                            </td>
                                            <td class="right">{{ $order['order_currency_code'] }} {{ $item['row_total'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>

                                <h4 class="text-right">Grand Total: {{ $order['order_currency_code'] }} {{ $order['grand_total'] }}</h4>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Comments</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                                </div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">
                                <table  id="items-ordered" class="table table-bordered table-hover">
                                    <tbody>
                                    @foreach ($order['comments'] as $comment)
                                        <tr>
                                            <td class="left">{{ $comment['created_at'] }} | {{ $comment['comment'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="right">
                            <a href="{{ URL::route('portal.orders.print', $order['entity_id']) }}" target="_blank" class="btn btn-success">Print</a>
                            <a href="{{ URL::previous() }}" class="btn btn-primary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @if($currentUser->hasAccess('portal.deliveries') && isset($order['deliveries']) && count($order['deliveries']))
        <div class="tab-pane fade" id="tab_2">

            <div class="well">


                <div class="row">

                    <div class="col-md-12">

                        <div class="panel panel-dark" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">Deliveries</div>

                                <div class="panel-options">
                                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                                    <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                                </div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">

                                <table class="table">
                                    <thead>
                                    <th>Delivery No</th>
                                    <th>Date</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($order['deliveries'] as $delivery)
                                        <tr>
                                            <td><a href="{{ route('portal.deliveries.show', $delivery['increment_id']) }}">{{ $delivery['delivery_number'] }}</a></td>
                                            <td>{{ $delivery['date_dispatched'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>


                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        @endif
    </div>
</div>





@stop