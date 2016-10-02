<?php $divClass = $displayQuotations ? 'col-md-6' : 'col-md-12'; ?>
<div class="col-md-12">



    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet box primary-36s">
        <div class="portlet-title">
            <div class="caption">
                Proposals
            </div>
            <div class="actions">
                @if($product_request->canAttachProposal() && $currentUser->hasAccess('product-proposals.create'))
                    <a href="{{ route('product-proposals.create', $product_request->request_id) }}" class="btn light-36s btn-sm">+ Create New</a>
                @endif

            </div>
        </div>
        <div class="portlet-body">

            <table class="table table-hover" style="font-size: .9em;">
                <thead>
                    <tr>
                        <th>Proposal ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Price Result</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proposals as $proposal)
                    <tr>
                        <td>
                            <a href="{{route('product-proposals.show', [$product_request->request_id,  $proposal->proposal_id]) }}">{{$proposal->proposal_id}}</a>
                        </td>
                        <td>{{ $proposal->product_name }}</td>
                        <td>{{ $proposal->price }}</td>
                        <td>{{ priceResult($proposal, $product_request) }}</td>
                        <td>{{ $proposal->currentStateLabel() }}</td>
                        <td>
                            {{ $proposal->assignedTo ? $proposal->assignedTo->name() : 'N/A' }}
                        </td>
                        <td>
                            {{ Form::open(['route' => ['product-proposals.approvals', $proposal->id], 'method' => 'PATCH' ]) }}
                                <a href="{{route('product-proposals.show', [$product_request->request_id,  $proposal->proposal_id]) }} " class="btn btn-xs btn-info">View</a>
                            @if($proposal->isEditable() && $currentUser->hasAccess('product-proposals.edit'))
                                <a href="{{route('product-proposals.edit', [$product_request->request_id,  $proposal->proposal_id]) }}" class="btn btn-xs primary-36s">Edit</a>
                                @if(in_array($proposal->getState(), ['APP','APR']) &&  $currentUser->hasAccess('product-proposals.recall_proposal'))
                                    {{ Form::submit('Recall', ['name' => "transition[recall_proposal]", 'class' => 'btn btn-danger btn-xs', 'id' => 'recall_proposal_button', 'Onclick'=>'return confirm("Recall this proposal?")']) }}
                                @endif
                            @endif
                            @if($proposal->getState() === 'APP' &&  $currentUser->hasAccess('product-proposals.approve') && $proposal->assigned_to_id === $currentUser->id)
                                {{ Form::submit('Approve', ['name' => "transition[approve]", 'class' => 'btn btn-success btn-xs', 'id' => 'accept_button', 'Onclick'=>'return confirm("Accept this proposal?")']) }}
                                {{ Form::submit('Reject', ['name' => "transition[reject]", 'class' => 'btn btn-danger btn-xs', 'id' => 'transition_reject']) }}
                            @endif
                            {{ Form::close() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($currentUser->hasAccess('product-proposals.create'))

            @endif

        </div>
    </div>
    <!-- END Portlet PORTLET-->

</div>

