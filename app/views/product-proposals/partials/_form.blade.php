{{-- Customer Product Data --}}
<div class="row">
	<div class="col-md-12">

		<div class="panel panel-success" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					New Proposal Details
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

                <h4>Product Information</h4>
                <button id="fill-from-request" class="btn btn-info btn-sm">Fill using Request Details</button>
                @if($quotation)
                    <button id="fill-from-quotation" class="btn btn-success btn-sm">Fill using Quotation Details</button>
                @endif

                <br/>
                <br/>
                <div class="well">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Product name Form Input -->
                                {{ Form::label('product_name', 'Product name:') }}
                                {{ Form::text('product_name', null, ['class' => 'form-control', 'id' => 'product_name_input']) }}
                                {{ $errors->first('product_name', '<span class="text text-danger">* :message</span>') }}
                            </div>
                            <div class="form-group">
                                <!-- Product_description Form Input -->
                                {{ Form::label('product_description', 'Product Description:') }}
                                {{ Form::textarea('product_description', null, ['id' => 'product_description_text', 'class' => 'form-control', 'rows' => 6]) }}
                                {{ $errors->first('product_description', '<span class="text text-danger">* :message</span>') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- Uom Form Input -->
                                {{ Form::label('uom', 'UOM:') }}
                                {{ Form::text('uom', null, ['class' => 'form-control', 'id' => 'uom_input_text']) }}
                                {{ $errors->first('uom', '<span class="text text-danger">* :message</span>') }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- Volume Form Input -->
                                {{ Form::label('volume', 'Volume:') }}
                                {{ Form::text('volume', null, ['class' => 'form-control', 'id' => 'volume_input_text']) }}
                                {{ $errors->first('volume', '<span class="text text-danger">* :message</span>') }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- Price Form Input -->
                                {{ Form::label('price', 'Price (per UOM):') }}
                                {{ Form::text('price', null, ['class' => 'form-control', 'id' => 'price_input']) }}
                                {{ $errors->first('price', '<span class="text text-danger">* :message</span>') }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('price_currency', 'Currency:') }}
                                {{ Form::select('price_currency', getCurrencies(), null, ['class' => 'form-control', 'id' => 'price_currency_input']) }}
                                {{ $errors->first('price_currency', '<span class="text text-danger">* :message</span>') }}
                            </div>
                        </div>

                    </div>
                </div>

                <h4>Supplier Information</h4>
                <div class="well">
                    <div class="row">
                        <div class="col-md-6">
                            {{--<div class="form-group">--}}
                                {{--<!-- Supplier Form Input -->--}}
                                {{--{{ Form::label('supplier_id', 'Supplier:') }}--}}
                                {{--{{ Form::select('supplier_id', $suppliers, null, ['class' => 'form-control', 'id' => 'supplier_input']) }}--}}
                                {{--{{ $errors->first('supplier_id', '<span class="text text-danger">* :message</span>') }}--}}
                            {{--</div>--}}

                            {{--<div>--}}
                                {{--{{ Form::label('display_quotation_details', 'Include supplier details in proposal sent to requester?') }}--}}
                                {{--<div class="form-group">--}}
                                    {{--<div id="label-switch" class="make-switch" data-on-label="Yes" data-off-label="No">--}}
                                        {{--{{--}}
                                            {{--Form::checkbox('display_quotation_details', 'true', null)--}}
                                        {{--}}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        </div>

                    </div>
                </div>

                <h4>Image & File Attachments
                    <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="Attach sample product photos or specification documents to the proposal.">
                    </i>
                </h4>

                {{-- Attachments --}}
                <div class="well">

                    <blockquote class="blockquote-blue">
                        <p>Accepted images file types are <strong>.png, .jpg, .jpeg, .gif, and .bmp</strong>.<br/>
                            Accepted document file types are <strong>.pdf, .doc, .docx, .xls, .xlsx, and .csv</strong>.</p>
                    </blockquote>

                    <div class="row">

                        <div id="dZUpload" class="dropzone">
                            <div class="dz-default dz-message"><span style="font-size: 1.5em;">Drop files here, or click to select</span></div>
                        </div>

                    </div>
                    @if (isset($product_proposal))
                        @if(count($product_proposal->images) || count($product_proposal->attachments))
                            <hr/>
                            <br/>
                            <div class="col-md-12">
                                <div class="pull-left">
                                {{--<h4>Current Images</h4>--}}
                                    @foreach($product_proposal->images as $image)
                                        <div id="imageattachment-{{$image->id}}" class="imagewrap attachment-wrap" data-type="image" data-id="{{$image->id}}">
                                            <a href="{{ $image->image->url() }}" target="_blank"><img
                                                        src="{{ $image->image->url('thumb') }}"></a>
                                            <button type="button" class="image-remove-button btn btn-xs btn-link" />
                                                <i class="entypo-trash text-danger" style="font-size:1.3em;font-weight: bold;"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="pull-left" style="margin-left: 30px;">
                                    {{--<h4>Current Files</h4>--}}
                                    <ul class="list-unstyled">
                                        @foreach($product_proposal->attachments as $attachment)
                                            <li class="attachment-wrap" id="fileattachment-{{$attachment->id}}" data-type="attachment" data-id="{{$attachment->id}}"><a href="{{ $attachment->attachment->url() }}"
                                                   target="_blank">{{ $attachment->attachment->originalFilename() }}</a>
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
                {{ Form::hidden('file-uploads', null, ['id'=>'file-uploads']) }}
                </div>

                <h4>Remarks</h4>
                <div class="well">
                    <div id="remarks">
                        <!-- Remarks Form Input -->
                        <?php if(isset($product_proposal)) $product_proposal->remarks = null; ?>
                        {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 4]) }}
                        {{ $errors->first('remarks', '<span class="text text-danger">* :message</span>') }}

                    </div>
                </div>
                {{ Form::hidden('sku', null) }}

			</div>

        </div>

    </div>
</div>
<div class="clear"></div>
<br/>

@if($quotation)
    {{ Form::hidden('quotation_id', $quotation->id, ['id' => 'quotation_id_input']) }}
@endif

{{-- Actions --}}
<div class="row">
    <div class="col-md-12">
        @foreach($transitions as $transition => $label )
            @if($currentUser->hasAccess('product-proposals.' . $transition))
                @if (in_array($transition, ['save_draft', 'submit_proposal']))
                    {{ Form::submit(ucwords($label), ['class' => 'btn btn-' . transitionButtonColor($transition),'name' => "transition[{$transition}]"]) }}
                @endif
                @if(in_array($transition, ['approve', 'final_approve', 'reject']) && $product_proposal->assigned_to_id == $currentUser->id)
                        {{ Form::submit(ucwords($label), ['class' => 'btn btn-' . transitionButtonColor($transition),'name' => "transition[{$transition}]"]) }}
                @endif
                @if ($transition == 'delete')
                    {{ Form::submit(ucwords($label), ['class' => 'btn btn-' . transitionButtonColor($transition),'name' => "transition[{$transition}]", 'Onclick'=>'return confirm("Recall this proposal?")']) }}
                @endif
            @endif
        @endforeach
        <a href="{{ URL::previous() }}" class="btn btn-default">Back</a>
    </div>
</div>

<div class="clear"></div>
<br/>

