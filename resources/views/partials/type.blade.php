@if(starts_with($type, '\\'))
    @set($name, last(explode('\\', $type)))
    @if($phpdoc->hasElement($type))
        <a class="type-link local {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!} href="#!/{{ $type }}" title="{{ str_replace_first('\\', '', $type) }}">{{ $name }}</a>
    @else
        <a class="type-link {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!} title="{{ str_replace_first('\\', '', $type) }}">{{ $name }}</a>
    @endif
@else
    <span class="simple-type simple-type-string {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!}>{{ $type }}</span>
@endif