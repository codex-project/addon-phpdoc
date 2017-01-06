@extends(codex()->view('document'))

@section('appClass', 'page-phpdoc')
@section('content')
    {!! $document->render()  !!}
@stop