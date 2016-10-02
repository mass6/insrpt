{{-- Actions --}}
<?php $removeTransitions = ['submit_proposal', 'recall_proposal', 'delete_active_proposal', 'approve', 'reject_proposal', 'submit_quotation_request'] ; ?>
<div class="row">
    <div class="col-md-12">
        @foreach($transitions as $transition => $label )
            @if(!in_array($transition, $removeTransitions) && $currentUser->hasAccess('product-requests.' . $transition))
                {{ Form::submit(ucwords($label), ['id' => $transition . '_submit','class' => 'btn btn-' . transitionButtonColor($transition),'name' => "transition[{$transition}]"]) }}
            @endif
        @endforeach

        {{ Form::close() }}

    @if(isset($product_request))
        @if($product_request->getState() == 'SRC' && $currentUser->hasAccess('quotations.create'))
            {{ Form::open(['route' => 'quotation-requests.store', 'style' => 'display:inline' ]) }}
            <input type="hidden" id="request_ids[]" name="request_ids" value="{{  $product_request->request_id }}"/>
            <!--  Form Input -->
            {{ Form::submit('Create Quotation Request', ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        @endif
    @endif
        <a href="{{ URL::previous() }}" class="btn btn-default">Back</a>
    </div>
</div>