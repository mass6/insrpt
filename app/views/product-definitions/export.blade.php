@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Export Request Data'])
@stop

@section('content')

<div id="home-page" style="background-color:#ffffff;padding: 15px;">

    <p class="text text-info">Export request data into Excel or CSV format.</p>
    <br/>
    <br/>

<div class="row" style="min-height: 200px;">
    <div class="col-md-12">
        <h3>Select the filter and file format you wish to download.</h3>
        <br/>

        <div class="btn-group">
            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="true">
            Completed Requests <i class="fa fa-angle-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-danger" role="menu">
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'completed', 'format' => 'xlsx']) }}"><i class="entypo-right"></i>Excel Format (xlsx)</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'completed', 'format' => 'xls']) }}"><i class="entypo-right"></i>Excel Format (xls)</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'completed', 'format' => 'csv']) }}"><i class="entypo-right"></i>CSV Format</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download_csv', ['filter' => 'completed', 'format' => 'csv']) }}"><i class="entypo-right"></i>CSV Format (new)</a>
                </li>
            </ul>
        </div>

        <div class="btn-group">
            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="true">
            All Requests <i class="fa fa-angle-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-danger" role="menu">
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'all', 'format' => 'xlsx']) }}"><i class="entypo-right"></i>Excel Format (xlsx)</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'all', 'format' => 'xls']) }}"><i class="entypo-right"></i>Excel Format (xls)</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download', ['filter' => 'all', 'format' => 'csv']) }}"><i class="entypo-right"></i>CSV Format</a>
                </li>
                <li><a href="{{ url('catalogue/product-definitions/download_csv', ['filter' => 'all', 'format' => 'csv']) }}"><i class="entypo-right"></i>CSV Format (new)</a>
                </li>
            </ul>
        </div>

    </div>


</div>

</div>
@stop