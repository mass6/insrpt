@extends('layouts.default.layout')

@section('content')

    <h3>Product Request Details</h3>

    <h5>{{ $product_request->id }} : {{ $product_request->product_description }}</h5>

    <div class="row">
        <div class="col-md-6">
            <table class="table table-hover table-bordered">
                <tbody>
                <thead>
                <th style="width:160px;">Field</th>
                <th>Value</th>
                </thead>
                <tr>
                    <td>Request Id</td>
                    <td>{{ $product_request->request_id }}</td>
                </tr>
                <tr>
                    <td>Created By</td>
                    <td>{{ $product_request->requestedBy->name() }} [{{ $product_request->requestedBy->company->name }}]
                    </td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>{{ $product_request->company->name }}</td>
                </tr>
                <tr>
                    <td>Product Description</td>
                    <td>{{ $product_request->product_description }}</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>{{ $product_request->category }}</td>
                </tr>
                <tr>
                    <td>UOM</td>
                    <td>{{ $product_request->uom }}</td>
                </tr>
                <tr>
                    <td>Purchase Recurrence</td>
                    <td>{{ $product_request->purchaseRecurrenceLabel() }}</td>
                </tr>
                <tr>
                    <td>Volume Requested</td>
                    <td>{{ $product_request->volume_requested }}</td>
                </tr>
                <tr>
                    <td>SKU</td>
                    <td>{{ $product_request->sku }}</td>
                </tr>
                <tr>
                    <td>Current Supplier</td>
                    <td>{{ $product_request->current_supplier }}</td>
                </tr>
                <tr>
                    <td>Current Supplier Contact Details</td>
                    <td>{{ $product_request->current_supplier_contact }}</td>
                </tr>
                <tr>
                    <td>Current Price</td>
                    <td>{{ $product_request->current_price }}</td>
                </tr>
                <tr>
                    <td>Current Price Currency</td>
                    <td>{{ $product_request->current_price_currency }}</td>
                </tr>
                <tr>
                    <td>{{$currentUser->company->settings()->get('product-requests.reference1.label', 'Reference Field 1')}}</td>
                    <td>{{ $product_request->reference1 }}</td>
                </tr>
                <tr>
                    <td>{{$currentUser->company->settings()->get('product-requests.reference2.label', 'Reference Field 2')}}</td>
                    <td>{{ $product_request->reference2 }}</td>
                </tr>
                <tr>
                    <td>{{$currentUser->company->settings()->get('product-requests.reference3.label', 'Reference Field 3')}}</td>
                    <td>{{ $product_request->reference3 }}</td>
                </tr>
                <tr>
                    <td>{{$currentUser->company->settings()->get('product-requests.reference4.label', 'Reference Field 4')}}</td>
                    <td>{{ $product_request->reference4 }}</td>
                </tr>
                <tr>
                    <td>Remarks</td>
                    <td>{{ $product_request->remarks }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>{{ $product_request->currentStateLabel() }}</td>
                </tr>
                <tr>
                    <td>Created</td>
                    <td>{{ $product_request->created_at }}</td>
                </tr>
                <tr>
                    <td>Updated</td>
                    <td>{{ $product_request->updated_at }}</td>
                </tr>
                <tr>
                    <td>Updated By</td>
                    <td>{{ $product_request->updatedBy->name() }} [{{ $product_request->updatedBy->company->name }}]
                    </td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>
                        @if(count($images))
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($images as $image)
                                        <a href="{{ $image->image->url() }}" target="_blank"><img
                                                    src="{{ $image->image->url('thumb') }}"></a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Attachments</td>
                    <td>
                        @if(count($attachments))
                            <ul>
                                @foreach($attachments as $attachment)
                                    <li><a href="{{ $attachment->attachment->url() }}"
                                           target="_blank">{{ $attachment->attachment->originalFilename() }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>

    <a href="{{ route('product-requests.index') }}" class="btn btn-primary">Back</a>

    <!-- History &Comments -->
    <div id="comments" class="row">

        <div class="col-md-10">

            <div class="well well-lg">
                <h3>History & Comments</h3>
                @if(count($product_request->comments))
                <div class="clear"></div>
                <hr/>
                <div class="comment-history">
                    @foreach ($product_request->comments->reverse() as $comment)
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="{{ route('profiles.show', $comment->user->id) }}" class="profile-picture">
                                <img src="{{ $comment->user->profile ? $comment->user->profile->avatar->url('thumb') : URL::asset('images/user.jpeg') }}" class="img-responsive img-circle"  />
                            </a>
                        </div>
                        <div class="col-sm-10 comment-block">
                            <h5>{{ profileLink($comment->user) }} on <span style="text-decoration: underline;">{{ $comment->created_at->format('l, d M Y g:i:s a') }}</span></h5><br/>
                            <div>{{ formatComment($comment->body) }}</div>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

    </div>

@stop