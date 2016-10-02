@if($sourcing_request->status)
    {{-- Tracking Data --}}
    <div class="row">

        <div class="col-md-12">

            <h4 class="text text-info">Tracking</h4>
            @if ($sourcing_request->status !== 'CLS')
                <blockquote id="tracking-open" class="blockquote-info">
            @else
                <blockquote id="tracking-closed" class="blockquote-danger">
            @endif
                <table class="request-details" style="margin-bottom: 0;font-size: 11px;padding: 3px;">
                    <tbody class="request-details">
                    <tr>
                        <td width="80">Customer:</td>
                        <td width="180"><strong>{{ $sourcing_request->customer->name }}</strong></td>
                        <td width="100">Batch Reference:</td>
                        <td width="180"><strong>{{ $sourcing_request->batch }}</strong></td>
                    </tr>
                    <tr>
                        <td width="80">Created By:</td>
                        <td width="180"><strong>{{ $sourcing_request->createdBy->name() }}</strong></td>
                        <td width="80">Updated by:</td>
                        <td width="180"><strong>{{ $sourcing_request->updatedBy->name() }}</strong></td>
                        <td width="80">Assigned to:</td>
                        <td width="180"><strong>{{ $sourcing_request->assigned_to_id ? $sourcing_request->assignedTo->name() : '' }}</strong></td>
                    </tr>
                    <tr>
                        <td width="70">Created on:</td>
                        <td width="180">{{ $sourcing_request->created_at->format('d-m-Y') }}</td>
                        <td width="70">Updated on:</td>
                        <td width="180">{{ $sourcing_request->updated_at->format('d-m-Y g:i:s A') }}</td>
                        <td width="70">Status:</td>
                        <td width="180"><strong><span class="text-info">{{ $sourcing_request->statusName() }}</span></strong></td>

                    </tr>
                    </tbody>
                </table>
            </blockquote>
        </div>
    </div>

    <div class="clear"></div>
    <br />

@endif

{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Request Information</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

                    @if(Request::is('sourcing-requests/create'))
                        <div class="col-md-6">
                            <!-- Customer Form Input -->
                            {{ Form::label('customer_id', 'Customer') }}
                            {{ Form::select('customer_id', $customers, null, ['class' => 'form-control', 'id' => 'customer_input', 'required']) }}
                            {{ $errors->first('customer_id', '<span class="text text-danger">* :message</span>') }}

                        </div>
                    @else
                        <div class="col-md-6">
                            <!-- Customer Form Input -->
                            {{ Form::label('customer_id', 'Customer') }}
                            <input class="form-control" value="{{ $sourcing_request->customer->name }}" disabled />
                        </div>
					@endif

					<div class="col-md-3">
                        <!-- Reference Form Input -->
                        {{ Form::label('batch', 'Batch Reference:') }}
                        {{ Form::text('batch', null, ['class' => 'form-control', 'id' => 'batch_input']) }}
					</div>

					<div class="col-md-3">
                        {{ Form::label('received_on', 'Received on:') }}
                        <div class="input-group date">
                            {{ Form::text('received_on', $sourcing_request->received_on ? $sourcing_request->received_on->format('d-m-Y') : null,
                            ['id'=>'received_on_input','class'=>'form-control datepicker', 'data-format' => "dd-mm-yyyy", 'required' ]) }}
                            <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>
                        {{ $errors->first('received_on', '<span class="text text-danger">* :message</span>') }}

                    </div>

				</div>

			</div>

		</div>

	</div>
</div>

{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Customer Product Data</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

					<div class="col-md-3">
                        <!-- Customer sku Form Input -->
                        {{ Form::label('customer_sku', 'Customer SKU:') }}
                        {{ Form::text('customer_sku', null, ['class' => 'form-control', 'id' => 'customer_sku_input']) }}
                        {{ $errors->first('customer_sku', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="col-md-9">
                    <!-- Product description Form Input -->
                    {{ Form::label('customer_product_description', 'Product description:') }}
                    {{ Form::text('customer_product_description', null, ['class' => 'form-control', 'id' => 'product_description_input', 'required']) }}
                    {{ $errors->first('customer_product_description', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="clear"></div>
					<br />

					<div class="col-md-3">
                        <!-- Customer price Form Input -->
                        {{ Form::label('customer_price', 'Price:') }}
                        {{ Form::text('customer_price', null, ['class' => 'form-control', 'id' => 'customer_price_input' ]) }}
                        {{ $errors->first('customer_price', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="col-md-4">
                        <!-- Customer price currency Form Input -->
                        {{ Form::label('customer_price_currency', 'Currency:') }}
                        {{ Form::select('customer_price_currency', getCurrencies(), null, ['class' => 'form-control', 'id' => 'customer_price_currency_input']) }}
                        {{ $errors->first('customer_price_currency', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="col-md-5">
					    <!-- Customer uom Form Input -->
					    {{ Form::label('customer_uom', 'UOM:') }}
					    {{ Form::text('customer_uom', null, ['class' => 'form-control', 'id' => 'customer_uom_input']) }}
					    {{ $errors->first('customer_uom', '<span class="text text-danger">* :message</span>') }}
					</div>

					{{--<div class="clear"></div>--}}
					{{--<br />--}}

					{{--<div class="col-md-3">--}}
                        {{--<!-- Reference Form Input -->--}}
                        {{--{{ Form::label('batch', 'Batch Reference:') }}--}}
                        {{--{{ Form::text('batch', null, ['class' => 'form-control', 'id' => 'batch_input']) }}--}}
					{{--</div>--}}

					{{--<div class="col-md-3">--}}
                        {{--{{ Form::label('received_on', 'Received on:') }}--}}
                        {{--<div class="input-group date">--}}
                            {{--<!-- Received at Form Input -->--}}
                            {{--{{ Form::input('text','received_on', isset($sourcing_request->received_on) ? $sourcing_request->received_on->format('d-m-Y') : null,--}}
                            {{--['id'=>'valid_until','class'=>'form-control datepicker', 'data-format'=>'dd-mm-yyyy', 'data-start-view'=>'1']) }}--}}
                            {{--{{ Form::text('received_on', $sourcing_request->received_on ? $sourcing_request->received_on->format('d-m-Y') : null,--}}
                            {{--['id'=>'received_on_input','class'=>'form-control datepicker', 'data-format' => "dd-mm-yyyy" ]) }}--}}
                            {{--<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>--}}
                        {{--</div>--}}
                    {{--</div>--}}

				</div>

			</div>

		</div>

	</div>
</div>

{{-- 36S Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>36S Product Data</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

					<div class="col-md-3">
						<!-- 36s sku Form Input -->
						{{ Form::label('36_sku', '36S SKU:') }}
						{{ Form::text('tss_sku', null, ['class' => 'form-control', 'id' => 'tss_sku_input']) }}
                        {{ $errors->first('tss_sku', '<span class="text text-danger">* :message</span>') }}

					</div>

					<div class="col-md-9">
						<!-- 36s product name Form Input -->
						{{ Form::label('tss_product_name', 'Product Name:') }}
						{{ Form::text('tss_product_name', null, ['class' => 'form-control', 'id' => 'tss_product_name_input']) }}
                        {{ $errors->first('tss_product_name', '<span class="text text-danger">* :message</span>') }}

					</div>

					<div class="clear"></div>
					<br />

					<div class="col-md-3">
					    <!-- 36s buy price Form Input -->
					    {{ Form::label('tss_buy_price', 'Buy Price:') }}
					    {{ Form::text('tss_buy_price', null, ['class' => 'form-control', 'id' => 'tss_buy_price_input']) }}
                        {{ $errors->first('tss_buy_price', '<span class="text text-danger">* :message</span>') }}

					</div>

					<div class="col-md-3">
					    <!-- 36s buy price currency Form Input -->
					    {{ Form::label('tss_buy_price_currency', 'Currency:') }}
					    {{ Form::select('tss_buy_price_currency', getCurrencies(), null, ['class' => 'form-control', 'id' => 'tss_buy_price_currency_input']) }}
					    {{ $errors->first('tss_buy_price_currency', '<span class="text text-danger">* :message</span>') }}
					</div>

                    <div class="col-md-3">
                        <!-- Tss uom Form Input -->
                        {{ Form::label('tss_uom', '36S UOM:') }}
                        {{ Form::text('tss_uom', null, ['class' => 'form-control', 'id' => 'tss_uom_input']) }}
                        {{ $errors->first('tss_uom', '<span class="text text-danger">* :message</span>') }}
                    </div>

                    <div class="col-md-3">
                        <!-- Supplier name Form Input -->
                        {{ Form::label('supplier_name', 'Supplier Name:') }}
                        {{ Form::text('supplier_name', null, ['class' => 'form-control', 'id' => 'supplier_name_input']) }}
                        {{ $errors->first('supplier_name', '<span class="text text-danger">* :message</span>') }}
                    </div>

					<div class="clear"></div>
					<br />

					<div class="col-md-3">
					    <!-- 36s sell price Form Input -->
					    {{ Form::label('tss_sell_price', 'Sell Price:') }}
					    {{ Form::text('tss_sell_price', null, ['class' => 'form-control', 'id' => 'tss_sell_price_input']) }}
					    {{ $errors->first('tss_sell_price', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="col-md-3">
					    <!-- 36s sell price currency Form Input -->
					    {{ Form::label('tss_sell_price_currency', 'Currency:') }}
					    {{ Form::select('tss_sell_price_currency', getCurrencies(), null, ['class' => 'form-control', 'id' => 'tss_sell_price_currency_input']) }}
					    {{ $errors->first('tss_sell_price_currency', '<span class="text text-danger">* :message</span>') }}
					</div>


				</div>

			</div>

		</div>

	</div>
</div>

{{-- Margins --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Margins</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

					<div class="col-md-4">
					    <!-- 36s buy price margin Form Input -->
					    {{ Form::label('tss_buy_price_margin', '36S Margin on Buy Price:') }}
					    {{ Form::hidden('tss_buy_price_margin', null, ['class' => 'form-control', 'id' => 'tss_buy_price_margin_input']) }}
					     <!-- TODO: completely remove buy price margin field -->
					    <input type="text" class="price-margin form-control" value="{{ $sourcing_request->tssBuyPriceMargin() ? $sourcing_request->tssBuyPriceMargin() . ' %' : '' }}" id="tss_buy_price_margin" readonly>
					</div>

					<div class="col-md-4">
					    <!-- 36s sell price margi Form Input -->
					    {{ Form::label('tss_sell_price_margin', '36S Margin on Sell Price:') }}
					    {{ Form::hidden('tss_sell_price_margin', null, ['class' => 'form-control', 'id' => 'tss_sell_price_margin_input']) }}
					    <!-- TODO: completely remove sell price margin field -->
					    <input type="text" class="price-margin form-control" value="{{ $sourcing_request->tssSellPriceMargin() ? $sourcing_request->tssSellPriceMargin() . ' %' : '' }}" id="tss_sell_price_margin" readonly>
					</div>

					<div class="col-md-4">
					    <!-- Customer sell price margin Form Input -->
					    {{ Form::label('customer_sell_price_margin', 'Customer Margin on Sell Price:') }}
					    {{ Form::hidden('customer_sell_price_margin', null, ['class' => 'form-control', 'id' => 'customer_sell_price_margin_input']) }}
					    <!-- TODO: completely remove customer sell price margin field -->
					    <input type="text" class="price-margin form-control" value="{{ $sourcing_request->customerSellPriceMargin() ? $sourcing_request->customerSellPriceMargin() . ' %' : '' }}" id="customer_sell_price_margin" readonly>
					</div>
				</div>

			</div>

		</div>

	</div>
</div>

{{-- Status & History --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-warning" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					Request Workflow
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

					<div class="col-md-4">
						<!-- Assigend to id Form Input -->
						{{ Form::label('assigned_to_id', 'Assigned to:') }}
						{{ Form::select('assigned_to_id', $assignableUsers, $sourcing_request->assignedTo ? $sourcing_request->assignedTo->id : null, ['class' => 'form-control', 'id' => 'assigend_to_id_input']) }}
                        {{ $errors->first('assigned_to_id', '<span class="text text-danger">* :message</span>') }}
					</div>

					<div class="col-md-3">
						<!-- Status Form Input -->
						{{ Form::label('status', 'Status:') }}
						{{ Form::select('status', $statuses, null, ['class' => 'form-control', 'id' => 'status_input']) }}
                        {{ $errors->first('status', '<span class="text text-danger">* :message</span>') }}
					</div>


					<div class="col-md-3" id="reason_closed_div" style="display: {{ $sourcing_request->reason_closed or $sourcing_request->status === 'CLS' ? 'block' : 'none' }};">
						<!-- Reason closed Form Input -->
						{{ Form::label('reason_closed', 'Reason Closed:') }}
						{{ Form::select('reason_closed', [null => '', 'COM' => 'Completed', 'DUP' => 'Duplicate'], null, ['class' => 'form-control', 'id' => 'reason_closed_input']) }}
					</div>

					<div class="clear"></div>
					<br />

                    <div class="col-md-7">
					    <!-- Remarks -->
					    <h4>Remarks</h4>
					    <p>Add any remarks or comments you have here.</p>
					    <?php $sourcing_request->remarks = null; ?>
					    {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 4, 'id' => 'remarks_input']) }}
					    {{ $errors->first('remarks', '<span class="text text-danger">* :message</span>') }}
					</div>

				</div>

			</div>

		</div>

	</div>
</div>

<br/>
<!-- Submit button -->
<div class="col-md-2">
    {{ Form::submit($submit, ['class' => 'form-control btn btn-primary', 'id' => 'submit_button']) }}
</div>
<div class="col-md-2">
    <a href="{{ URL::previous() }}" class="form-control btn btn-default">Back</a>
</div>
<div class="clear"></div>

<br/>
<br/>

<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {

    var status = $("#status_input");
    var div = $("#reason_closed_div");
    var reason_closed = $("#reason_closed_input");

    if (status.val() !== "CLS") {
        div.css("display", "none");
    } else {
        div.css("display", "block");
    }

    status.change(function(e){
        e.preventDefault();
        if ($(this).val() == 'CLS') {
        div.css("display", "block");
        } else {
        div.css("display", "none");
        reason_closed.val('');
        }
        //var confirmdelete = confirm("Are you sure to want to permanently delete this image?");
        //if (confirmdelete ==true) {
          //var $id = $(this).attr('imageid');
          //document.getElementById('existing-image'+$id).style.display = 'none';
          //document.getElementById('existing-image'+$id).remove();
          //document.getElementById('replace-image'+$id).style.display = 'block';
          //$.getJSON("/images/" + "<?php //echo $product->id; ?>" + "/" + "image" + $id + "/delete",function(result){});
        //}
    });
});

</script>