<div class="col-md-12">

    <div class="panel panel-default panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->

        <!-- panel head -->
        <div class="panel-heading">
            <div class="panel-title"><h4 class="text text-danger">Error Details</h4></div>

            <div class="panel-options">
                <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
            </div>
        </div>

        <!-- panel body -->
        <div class="panel-body">
           {{ Session::get('errorDetails', '') }}
        </div>

    </div>

</div>
<hr/>