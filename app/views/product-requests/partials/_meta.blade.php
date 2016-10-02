    {{-- Tracking Data --}}
    <div class="row">

        <div class="col-md-12">

            @if ($product_request->getState() === 'CLS')
                <blockquote id="tracking-closed" class="blockquote-danger">
            @else
                <blockquote id="tracking-open" class="blockquote-info">
            @endif
                <table class="request-details" style="margin-bottom: 0;font-size: 11px;padding: 3px;">
                    <tbody class="request-details">
                    <tr>
                        <td width="80">Request Id:</td>
                        <td width="180"><strong>{{ $product_request->request_id }}</strong></td>
                        <td width="80">Requested By:</td>
                        <td width="180"><strong>{{ $product_request->requestedBy->name() }}</strong></td>
                        <td width="70">Created:</td>
                        <td width="180">{{ $product_request->created_at->format('d-m-Y g:i:s A') }}</td>
                    </tr>
                    <tr>
                        <td width="70">Status:</td>
                        <td width="180"><strong><span class="label label-{{statusColor($product_request->getCurrentState())}}">{{ $product_request->currentStateLabel() }}</span></strong></td>
                        <td width="80">Updated by:</td>
                        <td width="180"><strong>{{ $product_request->updatedBy->name() }}</strong></td>
                        <td width="70">Updated:</td>
                        <td width="180">{{ $product_request->updated_at->format('d-m-Y g:i:s A') }}</td>
                    </tr>
                    <tr>
                        <td width="100">List:</td>
                        <td width="180"><strong>{{ $product_request->productRequestList ? $product_request->productRequestList->name : 'N/A' }}</strong></td>
                        <td width="100">Company:</td>
                        <td width="180"><strong>{{ $product_request->company->name }}</strong></td>
                        <td width="80">Created By:</td>
                        <td width="180"><strong>{{ $product_request->createdBy->name() }}</strong></td>
                    </tr>
                    </tbody>
                </table>
            </blockquote>
        </div>
    </div>
