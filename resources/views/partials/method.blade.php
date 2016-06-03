
    {{-- Method name --}}
    @spaceless
    <div class="tab-pane-header">
        <span class="phpdoc-visibility-{{ $method['visibility'] }} {{ isset($class) ? $class : '' }}">{{ $method['visibility'] }}</span>
        {{ $method['name'] }}<strong>(</strong>
        @foreach($method['arguments'] as $argument)
            @if(!$loop->first)
                <strong>,&nbsp;</strong>
            @endif
            @include('codex-phpdoc::partials.argument', ['argument' => $argument])
        @endforeach
        <strong>)</strong>
        @if(isset($method['tags']['return']) && strlen($method['tags']['return']['type']) > 0)
            : @include('codex-phpdoc::partials.type', ['type' => $method['tags']['return']['type']])
        @endif
    </div>
    @endspaceless
