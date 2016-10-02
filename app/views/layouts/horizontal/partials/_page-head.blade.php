<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>{{ $heading, '' }} <small>{{ $subheading or '' }}</small></h1>
        </div>
        <!-- END PAGE TITLE -->

        @if (isset($layoutSelector))
            @include('layouts.horizontal.partials._layout-selector')
        @endif

    </div>
</div>