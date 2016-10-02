{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Requested Product Information</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div id="general-information" class="panel-body">

				<div class="row">

                    <!-- Product description Form Input -->
                    <div class="col-md-6">
                    {{ Form::label('product_description', 'Product Name/Description:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The name or description of the desired product. For lengthly details
                           and product specifications, use the 'Remarks' field at the bottom of this form.">
                        </i>
                    {{ Form::text('product_description', null, ['v-model' => 'product_description', 'class' => 'form-control', 'id' => 'product_description_input', 'required']) }}
                    {{ $errors->first('product_description', '<span class="text text-danger">* :message</span>') }}
					</div>

                    <!-- Customer uom Form Input -->
                    <div class="col-md-2">
					    {{ Form::label('uom', 'UOM:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The desired Unit Of Measure.">
                        </i>
					    {{ Form::text('uom', null, ['class' => 'form-control', 'id' => 'uom_input']) }}
					    {{ $errors->first('uom', '<span class="text text-danger">* :message</span>') }}
					</div>

                    <!-- Category Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('category', 'Category:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The procurement category this product request belongs to.">
                        </i>
                        {{ Form::select('category', $categoriesList, null, ['class' => 'form-control', 'id' => 'category_input']) }}
                        {{ $errors->first('category', '<span class="text text-danger">* :message</span>') }}
					</div>
                </div>
                <br/>

                @if($product_request->getState() != 'NEW'
                        && settings('live_search.enabled')
                        && $currentUser->hasAccess('product-requests.product-match')
                    )
                    @include('product-requests.partials._product-match', ['open' => $product_request->getState() == 'REV'])
                <br/>
                @endif
                <div class="row">

                    <!-- First Time Order Quantity Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('first_time_order_quantity', 'First Time Order Quantity:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Expected quantity for first order in the given unit of measure (UOM).">
                        </i>
                        {{ Form::text('first_time_order_quantity', null, ['class' => 'form-control', 'id' => 'first_time_order_quantity_input']) }}
                        {{ $errors->first('first_time_order_quantity', '<span class="text text-danger">* :message</span>') }}
					</div>

                    <!-- Purchase Recurrence Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('purchase_recurrence', 'Purchase Recurrence:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Frequency that the requested product will be purchased.">
                        </i>
                        {{ Form::select('purchase_recurrence', Insight\ProductRequests\ProductRequest::purchaseRecurrenceOptions(), null, ['class' => 'form-control', 'id' => 'purchase_recurrence_input' ]) }}
                        {{ $errors->first('purchase_recurrence', '<span class="text text-danger">* :message</span>') }}
                    </div>

                    <!-- Purchase Recurrence Quantity Form Input -->
                    <div class="col-md-4" id="volume_requested_div" style="display:none;">
                        {{ Form::label('volume_requested', 'Purchase Recurrence Quantity:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Expected quantity for each recurring purchase in the given unit of measure (UOM).">
                        </i>
                        {{ Form::text('volume_requested', null, ['class' => 'form-control', 'id' => 'volume_requested_input']) }}
                        {{ $errors->first('volume_requested', '<span class="text text-danger">* :message</span>') }}
					</div>



                </div>
                <br/>
                <blockquote>
                    <p>If you have previously purchased this product from another supplier
                        or would like the new request to replace a current item, please provide
                        the current product details below.
                    </p>
                </blockquote>
                <div class="row">

                    <div class="col-md-3">
                        <!-- Sku Form Input -->
                        <div>
                            {{ Form::label('sku', 'SKU/Item Code:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="The existing internal SKU or Item code number.">
                            </i>
                            {{ Form::text('sku', null, ['class' => 'form-control', 'id' => 'sku_input']) }}
                            {{ $errors->first('sku', '<span class="text text-danger">* :message</span>') }}
                        </div>
                        <br/>
                        <!-- Customer price Form Input -->
                        <div>
                            {{ Form::label('current_price', 'Current Price (per UOM):') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Last or current purchase price of the product. Price should correlate to
                               the value provided in the UOM field.">
                            </i>
                            {{ Form::text('current_price', null, ['class' => 'form-control', 'id' => 'current_price_input' ]) }}
                            {{ $errors->first('current_price', '<span class="text text-danger">* :message</span>') }}
                        </div>
					</div>

                    <div class="col-md-3">
                        <!-- Current Supplier Name Form Input -->
                        <div>
                            {{ Form::label('current_supplier', 'Current Supplier:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Name of the current or last supplier of this product.">
                            </i>
                            {{ Form::text('current_supplier', null, ['class' => 'form-control', 'id' => 'current_supplier_input' ]) }}
                            {{ $errors->first('current_supplier', '<span class="text text-danger">* :message</span>') }}

                        </div>
                        <br/>
                        <!-- Customer price currency Form Input -->
                        <div>
                            {{ Form::label('current_price_currency', 'Currency:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Currency of purchase price.">
                            </i>
                            {{ Form::select('current_price_currency', getCurrencies(), null, ['class' => 'form-control', 'id' => 'current_price_currency_input']) }}
                            {{ $errors->first('current_price_currency', '<span class="text text-danger">* :message</span>') }}
                        </div>
                    </div>

                    <!-- Current Supplier Contact Details Form Input -->
                    <div class="col-md-6">
                        {{ Form::label('current_supplier_contact', 'Supplier Contact Details:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Phone, email, and address of current/last supplier.">
                        </i>
                        {{ Form::textarea('current_supplier_contact', null, [
                            'class' => 'form-control',
                            'id' => 'current_supplier_contact_input',
                            'rows' => 5
                        ]) }}
                        {{ $errors->first('current_supplier_contact', '<span class="text text-danger">* :message</span>') }}
					</div>

                </div>
			</div>

        </div>

    </div>
</div>

@if ($currentUser->hasAccess('product-requests.contracts') && count($contracts))
    @include('product-requests.partials._contracts')
@endif

{{-- Only show referenes fields if this feature is enabled for the customer --}}
@if($product_request->company->settings()->get('product-requests.references.enabled'))
    @include('product-requests.partials._product-references')
@endif

{{-- Attachments --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Image & File Attachments
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Attach sample product photos or specification documents of the product you are requesting.">
                        </i>
                    </h4>

				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

                <blockquote>
                    <p>Accepted images file types are <strong>.png, .jpg, .jpeg, .gif, and .bmp</strong>.<br/>
                        Accepted document file types are <strong>.pdf, .doc, .docx, .xls, .xlsx, and .csv</strong>.</p>
                </blockquote>

				<div class="row">

                    <div id="dZUpload" class="dropzone">
                        <div class="dz-default dz-message"><span style="font-size: 1.5em;">Drop files here, or click to select</span></div>
                    </div>

				</div>
                @if ($product_request->getState() !== 'NEW')
                    @if(count($product_request->images) || count($product_request->attachments))
                        <hr/>
                        <br/>
                        <div class="col-md-12">
                            <div class="pull-left">
                            {{--<h4>Current Images</h4>--}}
                                @foreach($product_request->images as $image)
                                    <div id="imageattachment-{{$image->id}}" class="imagewrap attachment-wrap" data-type="image" data-id="{{$image->id}}">
                                        <a href="{{ $image->image->url() }}" target="_blank"><img
                                                    src="{{ $image->image->url('thumb') }}">
                                            <i class="attachments fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="Attached by {{ $image->uploadedBy ? $image->uploadedBy->company->name : 'unknown' }} on {{ $image->created_at->format('d/M/Y') }}">
                                            </i>
                                        </a>
                                        <button type="button" class="image-remove-button btn btn-xs btn-link" />
                                            <i class="entypo-trash text-danger" style="font-size:1.3em;font-weight: bold;"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="pull-left" style="margin-left: 30px;">
                                {{--<h4>Current Files</h4>--}}
                                <ul class="list-unstyled">
                                    @foreach($product_request->attachments as $attachment)
                                        <li class="attachment-wrap" id="fileattachment-{{$attachment->id}}" data-type="attachment" data-id="{{$attachment->id}}"><a href="{{ $attachment->attachment->url() }}"
                                               target="_blank">
                                                {{ $attachment->attachment->originalFilename() }}
                                                <i class="attachments fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title="Attached by {{ $attachment->uploadedBy ? $attachment->uploadedBy->company->name : 'unknown' }} on {{ $attachment->created_at->format('d/M/Y') }}">
                                                </i>
                                            </a>
                                            <button type="button" class="attachment-remove-button btn btn-xs btn-link" />
                                            <i class="entypo-trash text-danger" style="font-style: normal;"></i>
                                        </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    @endif
                @endif

			</div>
            {{ Form::hidden('file-uploads', null, ['id'=>'file-uploads']) }}

        </div>

    </div>
</div>

{{-- Cataloguing --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Cataloguing
                    <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="The assigned product information as will it appear in your catalog of products.">
                    </i>
                    </h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

                    <!--Catalouging: Item code Form Input -->
                    <div class="col-md-2" id="cataloguing_item_code_div">
                        {{ Form::label('cataloguing_item_code', 'Item Code:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The item code or sku assigned to this product.">
                        </i>
                        {{ Form::text('cataloguing_item_code', null, ['class' => 'form-control', 'id' => 'cataloguing_item_code_input', !$currentUser->hasAccess('product-requests.cataloguing') ? 'readonly' : '' ]) }}
                        {{ $errors->first('cataloguing_item_code', '<span class="text text-danger">* :message</span>') }}
                    </div>

                    <!--Cataloguing: Product Name Form Input -->
                    <div class="col-md-10" id="cataloguing_product_name_div">
                        {{ Form::label('cataloguing_product_name', 'Product Name:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The product name assigned to this product.">
                        </i>
                        {{ Form::text('cataloguing_product_name', null, ['class' => 'form-control', 'id' => 'cataloguing_product_name_input' , !$currentUser->hasAccess('product-requests.cataloguing') ? 'readonly' : '']) }}
                        {{ $errors->first('cataloguing_product_name', '<span class="text text-danger">* :message</span>') }}
                    </div>

				</div>

			</div>

        </div>

    </div>
</div>

{{-- Remarks & Workflow --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Remarks
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Notes or remarks to be added to the request's audit trail.">
                        </i>
                    </h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

                    <!-- Remarks Form Input -->
                    <?php if(isset($product_request)) $product_request->remarks = null; ?>
                    <div class="col-md-12">
                        {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 4]) }}
                        {{ $errors->first('remarks', '<span class="text text-danger">* :message</span>') }}
                    </div>

				</div>

			</div>

        </div>

    </div>
</div>



{{-- Reason Closed --}}
<br/>
<div class="col-md-4" id="reason_closed_div" style="display: none;border: 1px solid #A6E8F3;padding: 15px;">
    <!-- Reason closed Form Input -->
    {{ Form::label('reason_closed', 'Reason for Closing:', ['style' => 'color:#000;font-size:1.2em;font-weight:bold;']) }}
    {{ Form::select('reason_closed', [null => 'select...', 'DUP' => 'Duplicate', 'WNS' => 'Will Not Source', 'AVL' => 'Product Already Available'], null, ['class' => 'form-control', 'id' => 'reason_closed_input', 'disabled']) }}<br/>
    {{ $errors->first('reason_closed', '<span class="text text-danger">* :message</span><br/><br/>') }}
    {{ Form::submit('Close Request', ['id' => 'reason_closed_submit','class' => 'btn btn-black','name' => "transition[action]"]) }}
    <button id="cancel_close" class="btn btn-warning">Cancel</button>
</div>
<div class="clear"></div>
<br/>
{{-- end Reason Closed--}}

{{-- Actions --}}
    @include('product-requests.partials._actions')
{{-- end Actions--}}
<div class="clear"></div>
<br/>

<script>
    var hasErrors = '<?php echo $errors->first('reason_closed'); ?>'
</script>


