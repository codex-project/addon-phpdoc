@extends($document->attr('layout'))

@push('stylesheets')
    <link href="{{ asset('vendor/docit-phpdoc/styles/phpdoc.css') }}" type="text/css" rel="stylesheet">
@endpush

@section('pageTitle', $document->attr('menu_name'))
@section('pageSubtitle', '')

@section('breadcrumb')
    @parent
    <li>
        <a href="{{ route('docit.phpdoc', [ 'projectName' => $project->getName() ]) }}">Phpdoc</a>
    </li>
@stop

@section('sidebar-menu')
    {!! $project->getDocumentsMenu()->render() !!}
@stop

@section('header-actions')
    @parent
    @include('docit::partials/header-actions')
@stop

@section('content')
<div id="docit-phpdoc">
    <div class="col-md-3 pr-n pl-n">
        <div id="docit-phpdoc-menu"></div>
    </div>
    <div class="col-md-9 pr-n pl-n">
        <div id="docit-phpdoc-content"></div>
    </div>
</div>
@stop
@push('config-scripts')
<script>
requirejs.config({
    paths  : {
        'phpdoc': '../../docit-phpdoc/scripts/phpdoc'
    }
});
</script>
@endpush
@push('init-scripts')
@include('docit-phpdoc::partials/phpdoc-template')
<script>

    //DocitLoader.start('docit-phpdoc', 'block');
    require(['phpdoc'], function(Phpdoc){
        var phpdoc = new Phpdoc();
        phpdoc.setTemplate('#phpdoc-content-template')
            .setContentSelector('#docit-phpdoc-content')
            .setTreeSelector('#docit-phpdoc-menu')
            .setData({!! json_encode($content) !!})
            .start(function(){
                //DocitLoader.stop('docit-phpdoc');
            });
        window['phpdoc'] = phpdoc; // for console testing
    });
</script>
@endpush
