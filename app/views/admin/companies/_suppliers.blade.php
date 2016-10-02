@if (siteOwnerId() !== $company->id)
    <br/>
    <button id="copy-from-master-list" class="btn blue btn-sm">Copy all Suppliers from Master List</button>
    <br/>
@endif
@include('company-settings.partials._suppliers')
