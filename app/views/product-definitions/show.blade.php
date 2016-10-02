@extends($layout)

@section('links')
    @parent
    <link href="{{ asset('metronic/assets/admin/layout3/css/blockquotes.css') }}" rel="stylesheet" type="text/css">
@stop


@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Cataloguing Request'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">Product:  {{ $product->name }} [{{ $product->code }}]</span>
                </div>
                <div class="actions">
                    @if($product->assigned_user_id === $currentUser->id || $currentUser->hasAccess('cataloguing.products.admin'))
                        <a href="{{ route('catalogue.product-definitions.edit', [$product->id]) }}" class="btn btn-circle green ">
                            <i class="icon-pencil"></i> Edit Request
                        </a>
                    @endif
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                {{-- Prodct Details Block --}}
                <div id="request-details" class="row">
                    <div class="col-md-10">
                        @include('product-definitions.partials._request-details')
                    </div>
                </div>

                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_5_1" data-toggle="tab" aria-expanded="true">
                            General </a>
                        </li>
                        <li class="">
                            <a href="#tab_5_2" data-toggle="tab" aria-expanded="false">
                            Description </a>
                        </li>
                        <li class="">
                            <a href="#tab_5_3" data-toggle="tab" aria-expanded="false">
                            Media </a>
                        </li>
                        @if($attributes)
                        <li>
                            <a href="#tab_5_4" data-toggle="tab">
                            Attributes </a>
                        </li>
                        @endif
                        <li>
                            <a href="#tab_5_5" data-toggle="tab">
                            History & Comments </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_5_1">

                            <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="150"><p><strong>Customer:</strong></p></td>
                                                        <td><p>{{ $product->customer->name }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>Supplier:</strong></p></td>
                                                        <td><p>{{ $product->supplier ? $product->supplier->name : '' }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>Product Code:</strong></p></td>
                                                        <td><p>{{ $product->code }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>Product Name:</strong></p></td>
                                                        <td><p>{{ $product->name }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>Category:</strong></p></td>
                                                        <td><p>{{ $product->category }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>UOM:</strong></p></td>
                                                        <td><p>{{ $product->uom }}</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150"><p><strong>Price:</strong></p></td>
                                                        <td><p>{{ $product->price }}</p></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                        </div>
                        <div class="tab-pane" id="tab_5_2">

                            <h4>Short Description</h4>
                            <p>{{ $product->short_description }}</p>
                            <hr/>
                            <br/>

                            <h4>Full Description</h4>
                            <p>{{ $product->description }}</p>

                        </div>
                        <div class="tab-pane" id="tab_5_3">

                            <!-- Images -->

                                <div class="gallery-env">

                                    <?php $customImageLabels = $product->customer->settings()->get('productDefinitions.customImageLabels'); ?>
                                    <h4>Product Images</h4>

                                    <div class="row">

                                                <div class="col-sm-3 col-xs-4" data-tag="1d">
                                                    <span>{{ isset($customImageLabels['imageLabel1']) ? $customImageLabels['imageLabel1'] : 'Image 1' }}</span>
                                                    <article class="image-thumb">
                                                        <a href="{{ $product->image1->url() }}" class="image" target="_blank">
                                                            <img src="{{ $product->image1->url('thumb') }}"/>
                                                        </a>
                                                    </article>
                                                </div>

                                                <div class="col-sm-3 col-xs-4" data-tag="1d">
                                                <span>{{ isset($customImageLabels['imageLabel2']) ? $customImageLabels['imageLabel2'] : 'Image 2' }}</span>
                                                    <article class="image-thumb">
                                                        <a href="{{ $product->image2->url() }}" class="image" target="_blank">
                                                            <img src="{{ $product->image2->url('thumb') }}"/>
                                                        </a>
                                                    </article>
                                                </div>

                                                <div class="col-sm-3 col-xs-4" data-tag="1d">
                                                <span>{{ isset($customImageLabels['imageLabel3']) ? $customImageLabels['imageLabel3'] : 'Image 3' }}</span>
                                                    <article class="image-thumb">
                                                        <a href="{{ $product->image3->url() }}" class="image" target="_blank">
                                                            <img src="{{ $product->image3->url('thumb') }}"/>
                                                        </a>
                                                    </article>
                                                </div>

                                                <div class="col-sm-3 col-xs-4" data-tag="1d">
                                                <span>{{ isset($customImageLabels['imageLabel4']) ? $customImageLabels['imageLabel4'] : 'Image 4' }}</span>
                                                    <article class="image-thumb">
                                                        <a href="{{ $product->image4->url() }}" class="image" target="_blank">
                                                            <img src="{{ $product->image4->url('thumb') }}"/>
                                                        </a>
                                                    </article>
                                                </div>

                                    </div>

                                </div>
                                <hr />



                            <!-- Attachments -->

                                <?php $customAttachmentLabels = $product->customer->settings()->get('productDefinitions.customAttachmentLabels'); ?>

                                <h4>Attachments</h4>

                                <div class="row">
                                    <ul style="font-size: 14px;">
                                        @if ($product->attachment1->originalFilename())
                                            <li>{{ isset($customAttachmentLabels['attachmentLabel1']) ? '[' . $customAttachmentLabels['attachmentLabel1'] . '] : ' : '' }}
                                                <a href="{{ $product->attachment1->url() }}" target="_blank">
                                                    {{$product->attachment1->originalFilename()}}
                                                </a>
                                            </li>
                                        @endif
                                        @if ($product->attachment2->originalFilename())
                                            <li>{{ isset($customAttachmentLabels['attachmentLabel2']) ? '[' . $customAttachmentLabels['attachmentLabel2'] . '] : ' : '' }}
                                                <a href="{{ $product->attachment2->url() }}" target="_blank">
                                                    {{$product->attachment2->originalFilename()}}
                                                </a>
                                            </li>
                                        @endif
                                        @if ($product->attachment3->originalFilename())
                                            <li>{{ isset($customAttachmentLabels['attachmentLabel3']) ? '[' . $customAttachmentLabels['attachmentLabel3'] . '] : ' : '' }}
                                                <a href="{{ $product->attachment3->url() }}" target="_blank">
                                                    {{$product->attachment3->originalFilename()}}
                                                </a>
                                            </li>
                                        @endif
                                        @if ($product->attachment4->originalFilename())
                                            <li>{{ isset($customAttachmentLabels['attachmentLabel4']) ? '[' . $customAttachmentLabels['attachmentLabel4'] . '] : ' : '' }}
                                                <a href="{{ $product->attachment4->url() }}" target="_blank">
                                                    {{$product->attachment4->originalFilename()}}
                                                </a>
                                            </li>
                                        @endif
                                        @if ($product->attachment5->originalFilename())
                                            <li>{{ isset($customAttachmentLabels['attachmentLabel5']) ? '[' . $customAttachmentLabels['attachmentLabel5'] . '] : ' : '' }}
                                                <a href="{{ $product->attachment5->url() }}" target="_blank">
                                                    {{$product->attachment5->originalFilename()}}
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </div>

                        </div>
                        @if($attributes)
                        <div class="tab-pane" id="tab_5_4">

                            @if($customAttributes)
                                @include('product-definitions.partials._' . strtolower($customAttributes) . '-attributes-data')
                            @else


                                <table>
                                    <tbody>
                                    @foreach($attributes as $key => $val)
                                        <tr>
                                            <td width="150"><p><strong>{{$key}}:</strong></p></td>
                                            <td><p>{{ $val }}</p></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </div>
                        @endif
                        <div class="tab-pane" id="tab_5_5">

                            <!-- Comments -->
                            <h4 class="">History</h4>
                            <br/>
                            @if (Session::has('comment_message'))
                            <div class="row alert {{ Session::get('success') ? 'alert-success' : 'alert-danger' }} clearfix" data-dismiss="alert">
                                {{ Session::get('comment_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif

                            @foreach ($product->comments as $comment)
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="#" class="profile-picture">
                                        <img src="{{ $comment->user->profile ? $comment->user->profile->avatar->url('thumb') : URL::asset('images/user.jpeg') }}" class="img-responsive img-circle" />
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <h5>{{ $comment->user->name() .' on ' . $comment->created_at }}</h5>
                                    <p>{{ formatComment($comment->body) }}</p>
                                </div>
                            </div>
                            <hr/>
                            @endforeach


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Back', 'all', array('class'=>'btn btn-danger btn-sm')) }}
                    </div>
                </div>

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

@stop