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
    </style>
@stop
@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Delivery # ' . $delivery['delivery_number']])
@stop
@section('content')

    <div class="well">
        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-dark" data-collapsed="0">

                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title">Delivery Details</div>

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
                                        <td>Date Dispatched:</td>
                                        <td>{{ $delivery['date_dispatched'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Contract:</td>
                                        <td>{{ $delivery['order']['contract'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered No:</td>
                                        <td>{{ $delivery['order']['increment_id'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered By:</td>
                                        <td>{{ $delivery['order']['ordered_by'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ordered On:</td>
                                        <td>{{ $delivery['order']['created_at'] }}</td>
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
                                        <td>Delivery Address:</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $delivery['order']['ship_to'] }}</td>
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
                        <div class="panel-title">Delivered Items</div>

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
                                <th class="right">Qty Delivered</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($delivery['shipment_items']['data'] as $item)
                                <tr>
                                    <td class="left">{{ $item['name'] }}</td>
                                    <td class="center">{{ $item['sku'] }}</td>
                                    <td class="center">{{ $item['bp_product_code'] }}</td>
                                    <td class="right">
                                        <span class="order-qty">{{ $delivery['orderQuantities'][$item['sku']]['qty'] }}</span>
                                    </td>
                                    <td class="right">
                                        <span class="order-qty">{{ $item['qty'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

    @if ($showMaterialsReceivedTracking && $currentUser->hasAccess('portal.deliveries.materials-received-note'))
        <div class="row">

            <div class="col-md-6">

                <div class="panel panel-dark" data-collapsed="0">

                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title">Materials Received</div>

                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <table class="table table-bordered">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td><strong>Materials Received Note</strong></td>
                                <td><strong>{{ array_get($delivery, 'materials_received') ?: 'No' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Received By</td>
                                <td>{{ $delivery['received_by'] }}</td>
                            </tr>
                            <tr>
                                <td>Date Received</td>
                                <td>{{ $delivery['received_at'] }}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>
    @endif

        <div class="row">

            <div class="col-md-12">
                <div class="pull-right">
                    <a href="{{ URL::route('portal.deliveries.print', $delivery['increment_id']) }}" target="_blank" class="btn btn-success">Print</a>
                    <a href="{{ URL::previous() ?: route('home') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>

        </div>

    </div>

@stop