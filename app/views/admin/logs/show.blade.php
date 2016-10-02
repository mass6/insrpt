@extends($layout)

<?php $vars = getViewVariables(); ?>
<?php extract($vars); ?>

@section('links')
    @parent
    <link href="{{ asset('metronic/assets/admin/layout3/css/logviewer.css') }}" rel="stylesheet" type="text/css">

@stop

@section('content')


    {{-- Nav Bar --}}
    <div class="">

        <div class="navbar navbar-inverse" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">
                Toggle navigation </span>
                <span class="icon-bar">
                </span>
                <span class="icon-bar">
                </span>
                <span class="icon-bar">
                </span>
                </button>
                <a class="navbar-brand" href="javascript:;">
                Application Log </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    {{ HTML::nav_item($url . '/' . $path . '/' . $sapi_plain . '/' . $date . '/all', ucfirst(Lang::get('logviewer::logviewer.levels.all'))) }}
                    @foreach ($levels as $level)
                        {{ HTML::nav_item($url . '/' . $path . '/' . $sapi_plain . '/' . $date . '/' . $level, ucfirst(Lang::get('logviewer::logviewer.levels.' . $level))) }}
                    @endforeach
                </ul>

                <div class="nav navbar-nav navbar-right" id="delete-current-log">
                    {{ HTML::link($url . '/' . $path . '/' . $sapi_plain . '/' . $date . '/delete', Lang::get('logviewer::logviewer.delete.btn'),
                                    array('class' => 'btn red-thunderbird', 'Onclick'=>'return confirm("Delete and clear the log?")')) }}
                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>

    </div>

    <div class="row">

        {{-- Log Date Menu --}}
        <div class="col-md-2">
            <div id="nav" class="well">
                <ul class="nav nav-list">
                    @if ($logs)
                    @foreach ($logs as $type => $files)
                    @if ( ! empty($files['logs']))
                    <?php $count = count($files['logs']) ?>
                    @foreach ($files['logs'] as $app => $file)
                    @if ( ! empty($file))
                    <li class="nav-header">{{ ($count > 1 ? $app . ' - ' . $files['sapi'] : $files['sapi']) }}</li>
                    <ul class="nav nav-list">
                        @foreach ($file as $f)
                        {{ HTML::decode(HTML::nav_item($url . '/' . $app . '/' . $type . '/' . $f, $f)) }}
                        @endforeach
                    </ul>
                    @endif
                    @endforeach
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>

        {{-- Log Entries --}}
        <div class="col-md-10">
            <div class="row-fluid{{ ! $has_messages ? ' hidden' : '' }}">
                <div class="span12" id="messages">
                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('error') }}
                    </div>
                    @endif
                    @if (Session::has('info'))
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('info') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    {{ $paginator->links() }}
                    <div id="log" class="well">
                        @if ( ! $empty && ! empty($log))
                        @foreach ($log as $l)
                        @if (strlen($l['stack']) > 1)
                        <div class="alert alert-block alert-{{ $l['level'] }}">
                            <span title="Click to toggle stack trace" class="toggle-stack"><i class="fa fa-plus-square-o"></i></span>
                            <span class="stack-header">{{ $l['header'] }}</span>
                            <pre class="stack-trace">{{ $l['stack'] }}</pre>
                        </div>
                        @else
                        <div class="alert alert-block alert-{{ $l['level'] }}">
                            <span class="toggle-stack">&nbsp;&nbsp;</span>
                            <span class="stack-header">{{ $l['header'] }}</span>
                        </div>
                        @endif
                        @endforeach
                        @elseif ( ! $empty && empty($log))
                        <div class="alert alert-block">
                            {{ Lang::get('logviewer::logviewer.empty_file', array('sapi' => $sapi, 'date' => $date)) }}
                        </div>
                        @else
                        <div class="alert alert-block">
                            {{ Lang::get('logviewer::logviewer.no_log', array('sapi' => $sapi, 'date' => $date)) }}
                        </div>
                        @endif
                    </div>
                    {{ $paginator->links() }}
                </div>
            </div>
        </div>

    </div>





<div id="delete_modal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>{{ Lang::get('logviewer::logviewer.delete.modal.header') }}</h3>
    </div>
    <div class="modal-body">
        <p>{{ Lang::get('logviewer::logviewer.delete.modal.body') }}</p>
    </div>
    <div class="modal-footer">
        {{ HTML::link($url . '/' . $path . '/' . $sapi_plain . '/' . $date . '/delete', Lang::get('logviewer::logviewer.delete.modal.btn.yes'), array('class' => 'btn btn-success')) }}
        <button class="btn btn-danger" data-dismiss="modal">{{ Lang::get('logviewer::logviewer.delete.modal.btn.no') }}</button>
    </div>
</div>

@stop
@section('bottomlinks')
    @parent
    {{ HTML::script('js/pages/logviewer.js') }}

@stop


