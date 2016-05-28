@if(starts_with($type, '\\'))
    @set($name, last(explode('\\', $type)))
    @if($phpdoc->hasElement($type))
        <a class="type-link local" href="#!/{{ $type }}" title="{{ $type }}">{{ $name }}</a>
    @else
        <a class="type-link" title="{{ $type }}">{{ $name }}</a>
    @endif
@else
    <span class="simple-type simple-type-string">{{ $type }}</span>
@endif