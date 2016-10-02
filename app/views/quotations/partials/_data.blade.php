
<!--  Form Input -->
<div class="row">
   <div class="col-md-12">

        <div class="panel panel-{{ isset($panel_type) ? $panel_type : 'success' }}" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">
                    Quotation: {{$quotation->quotation_id}}
                </div>

                <div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                </div>
            </div>

            <div class="panel-body">

                <table class="table table-hover" style="font-size: .9em;">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Product Name</th>
                            <th>Product Description</th>
                            <th>UOM</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Delivery Terms</th>
                            <th>Payment Terms</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$quotation->supplier ? $quotation->supplier->name : ''}}</td>
                            <td id="suppliers_product_name">{{$quotation->suppliers_product_name }}</td>
                            <td id="suppliers_product_description">{{$quotation->suppliers_product_description }}</td>
                            <td id="suppliers_uom">{{$quotation->suppliers_uom}}</td>
                            <td id="suppliers_quantity">{{$quotation->suppliers_quantity}}</td>
                            <td>{{$quotation->unit_price}} {{ $quotation->price_currency }}</td>
                            <td>{{$quotation->total_price}} {{ $quotation->price_currency }}</td>
                            <td>{{$quotation->delivery_terms}}</td>
                            <td>{{$quotation->payment_terms}}</td>
                        </tr>

                    </tbody>
                </table>

            </div>

        </div>

    </div>
</div>