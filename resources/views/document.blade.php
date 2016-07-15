@extends('codex::layouts.default')

@section('title')
    {{ $document->attr('title') }}
    -
    {{ $project->config('display_name') }}
    ::
    @parent
@stop


@section('content')
    {!! $content !!}
@stop
