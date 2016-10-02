    <div class="col-md-12">


    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet box primary-36s">
        <div class="portlet-title">
            <div class="caption">
                Quotations
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title="">
                </a>
            </div>
        </div>
        <div class="portlet-body">

            <table class="table table-hover" style="font-size: .9em;">
                <thead>
                    <tr>
                        <th>Quotation</th>
                        <th>Quotation Request</th>
                        <th>Supplier</th>
                        <th>UOM</th>
                        <th>Price</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product_request->quotationsReceived as $quotation)
                    <tr>
                        <td>{{$quotation->quotation_id}}</td>
                        <td>{{$quotation->quotationRequest ? $quotation->quotationRequest->quotation_request_id : '' }}</td>
                        <td>{{$quotation->supplier ? $quotation->supplier->name : ''}}</td>
                        <td>{{$quotation->suppliers_uom}}</td>
                        <td>{{$quotation->unit_price}}</td>
                        <td>
                            @if($currentUser->hasAccess('product-proposals.create'))
                                <a href="{{ route('product-proposals.create', [$product_request->request_id, $quotation->quotation_id]) }}" class="btn btn-success btn-xs">+Add Proposal</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <!-- END Portlet PORTLET-->


    </div>