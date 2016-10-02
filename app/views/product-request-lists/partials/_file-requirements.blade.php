<!-- Modal 1 (Basic)-->
<div class="modal fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">File Requirements</h4>
            </div>

            <div class="modal-body" style="color:#000000;font-family: arial, sans-serif;">

                <p>The file must be in spreadsheet format(.xlsx, .xls, or .csv), and must contain of the following fields as column headers
                    in the first row. Although all columns headers below must be present in the upload file, only some columns require
                    data to be entered.
                <br/>

                <h4>Required Columns</h4>
                <ul>
                    <li><strong>Product_Description</strong> <span class="text text-danger">(*Required)</span> : - <em>Name and or description of the product requested</em></li>
                    <li><strong>Category</strong> <span class="text text-danger">(*Required)</span> : - <em>Category name of the product requested. (Valid options below)</em>
                        <ul>
                        <?php $categories = $currentUser->company->settings()->get('product-requests.procurement-categories', ['Default']) ?>
                        @foreach($categories as $category)
                            <li>{{ $category }}</li>
                        @endforeach
                        </ul>
                    </li>
                    <li><strong>UOM</strong> <span class="text text-danger">(*Required)</span> : - <em>Unit Of Measure (e.g., Each, Dozen, Carton of 25, etc.)</em></li>
                    <li><strong>First Time Order Quantity</strong> <span class="text text-danger">(*Required)</span> : - <em>Whole integer number</em></li>
                    <li><strong>Purchase_Recurrence</strong> <span class="text text-danger">(*Required)</span> : - <em>(valid options below)</em>
                        <ul>
                            <li>ad-hoc</li>
                            <li>monthly</li>
                            <li>annually</li>
                        </ul>
                    </li>
                    <li><strong>Volume</strong> : - <em>Whole integer number</em></li>
                    <li><strong>Current_SKU</strong>: - <em>Your organisation's SKU or Item Code of the product requested</em></li>
                    <li><strong>Current_Price</strong>: - <em>Current product price. Numbers and decimals only; no symbols</em></li>
                    <li><strong>Current_Price_Currency</strong>: - <em>3-digit ISO 4217 current code. See http://www.xe.com/iso4217.php for reference</em></li>
                    <li><strong>Current_Supplier</strong>: - <em>Current supplier name</em></li>
                    <li><strong>Current_Supplier_Contact</strong>: - <em>Current supplier contact details (address, email, tel)</em></li>

                <?php $settings = $currentUser->company->settings()->all(); ?>
                @if(array_get($settings, 'product-requests.references.enabled'))
                    @for($r = 1; $r <= 4; $r++)
                        @if(array_get($settings, "product-requests.reference{$r}.enabled", false))
                            <li>
                                <strong>
                                    {{
                                        array_get($settings, "product-requests.reference{$r}.label", '') !== ''
                                        ? str_replace(' ', '_', array_get($settings, "product-requests.reference{$r}.label"))
                                        : "Reference{$r}"
                                    }}
                                </strong>
                                @if(array_get($settings, "product-requests.reference{$r}.required", false))
                                     <span class="text text-danger">(*Required)</span>
                                @endif
                                :
                            </li>
                        @endif()
                    @endfor
                @endif
                    <li><strong>Remarks</strong>: <em>Remarks or comments relevant to product requested</em></li>
                </ul>
                <hr/>

                <p>
                    <a href="{{ action('ProductRequestListsController@sample') }}" class="btn btn-primary btn-sm">Download sample file</a>
                </p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>