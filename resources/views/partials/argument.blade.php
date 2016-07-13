@foreach($argument['types'] as $type)
    {{ $loop->first ? '' : '|' }}
    @include('codex-phpdoc::partials.type', ['type' => $type])
@endforeach
<span class="color-cyan-900">&nbsp;{{ $argument['name'] }}</span>
@if(strlen($argument['default']) > 0)
    @spaceless
    <span>={{ $argument['default'] }}</span>
    @endspaceless
@endif
