@if(is_string(head(array_keys($method))))
@set($method, [$method])
@endif
@foreach($method as $m)
    {{-- Method name --}}
    @spaceless
    <div class="tab-pane-header">
        <span class="phpdoc-visibility-{{ $m['visibility'] }} {{ isset($class) ? $class : '' }}">{{ $m['visibility'] }}</span>
        {{ $m['name'] }}<strong>(</strong>
        @foreach($m['arguments'] as $argument)
            @if(!$loop->first)
                <strong>,&nbsp;</strong>
            @endif
            @include('codex-phpdoc::partials.argument', ['argument' => $argument])
        @endforeach
        <strong>)</strong>
        @if(isset($m['tags']['return']) && strlen($m['tags']['return']['type']) > 0)
            : @include('codex-phpdoc::partials.type', ['type' => $m['tags']['return']['type']])
        @endif
    </div>
    @endspaceless
@endforeach
