{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-{{ isset($panel_type) ? $panel_type : 'success' }}" data-collapsed="{{ isset($collapsed) ? $collapsed : 0 }}">

			<div class="panel-heading">
				<div class="panel-title">
					Proposal Details
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

                @if($product_proposal->quotation && $currentUser->hasAccess('quotations.*'))
                    <div class="col-md-12">
                        @include('quotations.partials._data', ['quotation' => $product_proposal->quotation, 'panel_type' => 'dark'])
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- Product name Form Input -->
                            {{ Form::label('product_name', 'Product name:') }}
                            {{ Form::text('product_name', $product_proposal->product_name, ['class' => 'form-control', 'id' => 'product_name_input', 'readonly']) }}
                        </div>
                        <div class="form-group">
                            <!-- Product_description Form Input -->
                            {{ Form::label('product_description', 'Product Description:') }}
                            {{ Form::textarea('product_description', $product_proposal->product_description, ['class' => 'form-control', 'rows' => 6, 'readonly']) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <!-- Uom Form Input -->
                            {{ Form::label('uom', 'UOM:') }}
                            {{ Form::text('uom', $product_proposal->uom, ['class' => 'form-control', 'id' => 'uom_input', 'readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <!-- Volume Form Input -->
                            {{ Form::label('volume', 'Volume:') }}
                            {{ Form::text('volume', $product_proposal->volume, ['class' => 'form-control', 'id' => 'volume_input', 'readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <!-- Price Form Input -->
                            {{ Form::label('price', 'Price (per UOM):') }}
                            {{ Form::text('price', $product_proposal->price, ['class' => 'form-control', 'id' => 'price_input', 'readonly']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('price_currency', 'Currency:') }}
                            {{ Form::text('price_currency', $product_proposal->price_currency, ['class' => 'form-control', 'id' => 'price_currency_input', 'readonly']) }}
                        </div>
                    </div>

                </div>

                @if($currentUser->hasAccess('edit') || $product_proposal->display_supplier_details)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- Supplier Form Input -->
                                {{ Form::label('supplier', 'Supplier Name:') }}
                                {{ Form::text('supplier', $product_proposal->supplier, ['class' => 'form-control', 'id' => 'supplier_input', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Supplier_contact Form Input -->
                            {{ Form::label('supplier_contact', 'Supplier Contact:') }}
                            {{ Form::textarea('supplier_contact', $product_proposal->supplier_contact, ['class' => 'form-control', 'rows' => 5, 'readonly']) }}
                        </div>
                    </div>
                    <div class="clear"></div>
                    <br/>
                @endif

                @if(count($product_proposal->images) || count($product_proposal->attachments))

                <h4>Attachments</h4>
                    <div class="well">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                {{--<h4>Current Images</h4>--}}
                                    @foreach($product_proposal->images as $image)
                                        <div id="imageattachment-{{$image->id}}" class="imagewrap attachment-wrap" data-type="image" data-id="{{$image->id}}">
                                            <a href="{{ $image->image->url() }}" target="_blank"><img src="{{ $image->image->url('thumb') }}"></a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="pull-left" style="margin-left: 30px;">
                                    {{--<h4>Current Files</h4>--}}
                                    <ul class="list-unstyled">
                                        @foreach($product_proposal->attachments as $attachment)
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

                @endif

			</div>

        </div>

    </div>
</div>