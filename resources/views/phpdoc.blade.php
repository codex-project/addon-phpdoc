@extends($document->attr('layout'))

@push('styles')
<link href="{{ asset('vendor/codex-phpdoc/styles/phpdoc.css') }}" type="text/css" rel="stylesheet">
@endpush

@section('page-title', $document->attr('menu_name'))
@section('page-subtitle', '')

@section('breadcrumb')
    @parent
    <li>
        <a href="{{ route('codex.phpdoc', [ 'projectName' => $project->getName() ]) }}">Phpdoc</a>
    </li>
@stop


@section('content')
    <div id="codex-phpdoc">
        <div class="col-md-3 pr-n pl-n">
            <div id="codex-phpdoc-menu"></div>
        </div>
        <div class="col-md-9 pr-n pl-n">
            <div id="codex-phpdoc-content"></div>
        </div>
    </div>
@stop

@push('init-scripts')
<script src="{{ asset('vendor/codex/bower_components/jstree/dist/jstree.js') }}"></script>
<script src="{{ asset('vendor/codex-phpdoc/scripts/phpdoc.js') }}"></script>
@include('codex-phpdoc::partials/phpdoc-template')
<script>
    var phpdoc = new Phpdoc();
    phpdoc.setTemplate('#phpdoc-content-template')
        .setContentSelector('#codex-phpdoc-content')
        .setTreeSelector('#codex-phpdoc-menu')
        .setData({!! json_encode($content) !!})
        .start(function () {
            //CodexLoader.stop('codex-phpdoc');
        });
    window['phpdoc'] = phpdoc; // for console testing
</script>
@endpush
