
    {{-- Method name --}}
    @spaceless
    <div class="tab-pane-header">
        <span class="phpdoc-visibility-{{ $method['visibility'] }}">{{ $method['visibility'] }}</span>
        {{ $method['name'] }}<strong>(</strong>
        @foreach($method['arguments'] as $argument)
            @if(!$loop->first)
                <strong>,&nbsp;</strong>
            @endif
            @foreach($argument['types'] as $type)
                {{ $loop->first ? '' : '|' }}
                @include('codex-phpdoc::partials.type', ['type' => $type['name']])
            @endforeach
            <span class="color-cyan-900">&nbsp;{{ $argument['name'] }}</span>
            @if(strlen($argument['default']) > 0)
                @spaceless
                <span>={{ $argument['default'] }}</span>
                @endspaceless
            @endif
        @endforeach
        <strong>)</strong>
        @if(isset($method['tags']['return']) && strlen($method['tags']['return']['type']) > 0)
            : @include('codex-phpdoc::partials.type', ['type' => $method['tags']['return']['type']])
        @endif
    </div>
    @endspaceless
