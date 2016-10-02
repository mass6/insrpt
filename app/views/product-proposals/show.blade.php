@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Proposal ID: <strong>' . $product_proposal->proposal_id . '</strong>'])
@stop

@section('content')

    <div class="note note-info note-bordered">
        <h4>Status: <strong>{{ $product_proposal->currentStateLabel() }}</strong></h4>
        <h4>Assigned To: <strong>{{ $product_proposal->assignedTo ? $product_proposal->assignedTo->name() : 'N/A' }}</strong></h4>
    </div>

    @include('product-requests.partials._data', ['product_request' => $product_request, 'collapsed' => 1, 'panel_type' => 'info' ])
    @include('product-proposals.partials._data', ['product_proposal' => $product_proposal, 'panel_type' => 'success'])


    {{-- Actions --}}
    <div class="row">
        <div class="col-md-12">
            {{ Form::open(['route' => ['product-proposals.approvals', $product_proposal->id], 'method' => 'PATCH']) }}
                <?php $ignoredTransitions = ['save_draft','submit_proposal','update','recall_proposal']; ?>
                @foreach($transitions as $transition => $label )

                    @if(in_array($transition, $ignoredTransitions))
                        <?php continue; ?>
                    @endif
                    {{-- only show approve/reject buttons to the currently assigned user --}}
                    @if(in_array($transition, ['approve', 'final_approve', 'reject']) && $product_proposal->assigned_to_id !== $currentUser->id)
                        <?php continue; ?>
                    @endif
                     {{--check that current user has permissions to perform the transition --}}
                    @if($currentUser->hasAccess('product-proposals.' . $transition))
                        {{ Form::submit(ucwords($label), ['class' => 'btn btn-' . transitionButtonColor($transition),'name' => "transition[{$transition}]", 'id' => "transition_{$transition}"]) }}
                    @endif
                @endforeach
                @if($product_proposal->isEditable() && $currentUser->hasAccess('product-proposals.edit'))
                    <a href="{{route('product-proposals.edit', [$product_request->request_id,  $product_proposal->proposal_id]) }}" class="btn btn-primary">Edit Proposal</a>
                @endif
            <a href="{{ URL::previous() }}" class="btn btn-default">Back</a>
            {{ Form::close() }}
        </div>
    </div>
    <div class="clear"></div>
    <br/>

    @include('common._comments', ['model' => $product_proposal])


@stop

@section('bottomlinks')
    @parent
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('js/pages/product-proposals.js') }}"></script>
@stop