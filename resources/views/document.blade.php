@extends('codex::layouts.default')

@section('title')
    {{ $document->attr('title') }}
    -
    {{ $project->config('display_name') }}
    ::
    @parent
@stop


@section('content')

    <header>
        <div class="phpdoc-settings-dropdown">
            <a href="#"><i class="fa fa-cog"></i><span class="caret"></span></a>
        </div>
        <small>{{ $document->attr('subtitle', '') }} </small>
        <h1>{{ $document->attr('title') }}</h1>
    </header>
    {!! $content !!}

@stop

