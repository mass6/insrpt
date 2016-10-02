{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

         <!-- BEGIN Portlet PORTLET-->
        <div class="portlet box primary-36s">
            <div class="portlet-title">
                <div class="caption">
                    Product Request Details - <a href="{{ route('product-requests.show', $product_request->request_id) }}" style="color:white;"> [{{$product_request->request_id}}] <small>view</small></a>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="expand" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body {{ isset($collapsed) ? 'display-hide' : '' }}">

                <div class="row">

                    <!-- Product description Form Input -->
                    <div class="col-md-6">
                    {{ Form::label('product_description', 'Product Name/Description:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The name or description of the desired product. For lengthly details
                           and product specifications, use the 'Remarks' field at the bottom of this form.">
                        </i>
                    {{ Form::text('product_description', $product_request->product_description, ['class' => 'form-control', 'id' => 'product_description_input', 'readonly']) }}
					</div>

                    <!-- Customer uom Form Input -->
                    <div class="col-md-2">
					    {{ Form::label('uom', 'UOM:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The desired Unit Of Measure.">
                        </i>
					    {{ Form::text('uom', $product_request->uom, ['class' => 'form-control', 'id' => 'uom_input', 'readonly']) }}
					</div>

                    <!-- Category Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('category', 'Category:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="The procurement category this product request belongs to.">
                        </i>
                        {{ Form::text('category', $product_request->category, ['class' => 'form-control', 'id' => 'category_input', 'readonly']) }}
					</div>
                </div>
                <br/>
                <div class="row">

                    <!-- First Time Order Quantity Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('first_time_order_quantity', 'First Time Order Quantity:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Expected quantity for first order in the given unit of measure (UOM).">
                        </i>
                        {{ Form::text('first_time_order_quantity', $product_request->first_time_order_quantity, ['class' => 'form-control', 'id' => 'first_time_order_quantity_input', 'readonly']) }}
					</div>

                    <!-- Purchase Recurrence Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('purchase_recurrence', 'Purchase Recurrence:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Frequency that the requested product will be purchased.">
                        </i>
                        {{ Form::text('purchase_recurrence', $product_request->purchase_recurrence, ['class' => 'form-control', 'id' => 'purchase_recurrence_input', 'readonly' ]) }}
                    </div>

                    <!-- Volume Requested Form Input -->
                    <div class="col-md-4">
                        {{ Form::label('volume_requested', 'Purchase Recurrence Quantity:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Expected quantity for each recurring purchase in the given unit of measure (UOM).">
                        </i>
                        {{ Form::text('volume_requested', $product_request->volume_requested, ['class' => 'form-control', 'id' => 'volume_requested_input', 'readonly']) }}
					</div>
                </div>
                <br/>
                <div class="row">

                    <div class="col-md-3">
                        <!-- Sku Form Input -->
                        <div>
                            {{ Form::label('sku', 'SKU/Item Code:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="The existing internal SKU or Item code number.">
                            </i>
                            {{ Form::text('sku', $product_request->sku, ['class' => 'form-control', 'id' => 'sku_input', 'readonly']) }}
                        </div>
                        <br/>
                        <!-- Customer price Form Input -->
                        <div>
                            {{ Form::label('current_price', 'Current Price (per UOM):') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Last or current purchase price of the product. Price should correlate to
                               the value provided in the UOM field.">
                            </i>
                            {{ Form::text('current_price', $product_request->current_price, ['class' => 'form-control', 'id' => 'current_price_input', 'readonly' ]) }}
                        </div>
					</div>

                    <div class="col-md-3">
                        <!-- Current Supplier Name Form Input -->
                        <div>
                            {{ Form::label('current_supplier', 'Current Supplier:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Name of the current or last supplier of this product.">
                            </i>
                            {{ Form::text('current_supplier', $product_request->current_supplier, ['class' => 'form-control', 'id' => 'current_supplier_input', 'readonly' ]) }}

                        </div>
                        <br/>
                        <!-- Customer price currency Form Input -->
                        <div>
                            {{ Form::label('current_price_currency', 'Currency:') }}
                            <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="Currency of purchase price.">
                            </i>
                            {{ Form::text('current_price_currency', $product_request->current_price_currency, ['class' => 'form-control', 'id' => 'current_price_currency_input', 'readonly']) }}
                        </div>
                    </div>

                    <!-- Current Supplier Contact Details Form Input -->
                    <div class="col-md-6">
                        {{ Form::label('current_supplier_contact', 'Supplier Contact Details:') }}
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Phone, email, and address of current/last supplier.">
                        </i>
                        {{ Form::textarea('current_supplier_contact', $product_request->current_supplier_contact, [
                            'class' => 'form-control',
                            'id' => 'current_supplier_contact_input', 'readonly',
                            'rows' => 5
                        ]) }}
					</div>

                </div>
                <div class="clear"></div>
                <br/>

                {{-- Only show referenes fields if this feature is enabled for the customer --}}
                @if($product_request->company->settings()->get('product-requests.references.enabled'))
                    @include('product-requests.partials._references_data', ['company_settings' => $product_request->company->settings()->all() ])
                @endif

            </div>
        </div>
        <!-- END Portlet PORTLET-->


    </div>
</div>

{{--@unless(! count($contracts))--}}
    {{--@include('product-requests.partials._contracts')--}}
{{--@endif--}}



@if(count($product_request->images) || count($product_request->attachments))
{{-- Attachments --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Image & File Attachments
                        <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                           data-original-title="Sample product photos or specification documents relevant to the product being requested.">
                        </i>
                    </h4>

				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

                <hr/>
                <br/>
                <div class="col-md-12">
                    <div class="pull-left">
                    {{--<h4>Current Images</h4>--}}
                        @foreach($product_request->images as $image)
                            <div id="imageattachment-{{$image->id}}" class="imagewrap attachment-wrap" data-type="image" data-id="{{$image->id}}">
                                <a href="{{ $image->image->url() }}" target="_blank"><img src="{{ $image->image->url('thumb') }}"></a>
                            </div>
                        @endforeach
                    </div>
                    <div class="pull-left" style="margin-left: 30px;">
                        {{--<h4>Current Files</h4>--}}
                        <ul class="list-unstyled">
                            @foreach($product_request->attachments as $attachment)
                                <li class="attachment-wrap" id="fileattachment-{{$attachment->id}}" data-type="attachment" data-id="{{$attachment->id}}"><a href="{{ $attachment->attachment->url() }}"
                                       target="_blank">{{ $attachment->attachment->originalFilename() }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>

			</div>

        </div>

    </div>
</div>
@endif