@if(starts_with($type, '\\'))
    @set($name, isset($typeFullName) && $typeFullName === true ? str_replace_first('\\', '', $type): last(explode('\\', $type)))
    @if($phpdoc->hasEntity($type))
        <a class="type-link local {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!} href="{{ $phpdoc->url($type) }}" title="{{ str_replace_first('\\', '', $type) }}" data-phpdoc-popover="{{ str_replace_first('\\', '', $type) }}">{{ $name }}</a>
    @else
        <a class="type-link {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!} title="{{ str_replace_first('\\', '', $type) }}">{{ $name }}</a>
    @endif
@else
    <span class="simple-type simple-type-string {{ isset($class) ? $class : '' }}" {!! isset($attributes) ? $attributes : '' !!}>{{ $type }}</span>
@endif
