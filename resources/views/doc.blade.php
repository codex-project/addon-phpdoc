<!-- HEADER-->
<header>
    <i class="phpdoc-type-{{ $type }}"></i>
    <h3 class="fs22"><span class="phpdoc-type-{{ $type }}">{{ $full_name }}</span></h3>
</header>

<!-- DESC-->
<div class="phpdoc-content-description">
    @if(strlen($description)>0)
        <p class="fs-13">{{ $description }}</p>
    @endif
    @if(count($tags) > 0 || count($file_tags) > 0 || strlen($file_description) > 0)
        <table class="table table-hover table-bordered table-phpdoc-tags">
            <tbody>
            @if(strlen($file_description) > 0)
                <tr>
                    <th width="150">file description</th>
                    <td>Part of the Codex Project packages.</td>
                </tr>
            @endif
            @foreach(array_merge($tags, $file_tags) as $name => $tag)
                <tr>
                    <th width="150">{{ $tag['name'] }}</th>
                    <td>{{ $tag['description'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div><!-- TABS: METHODS, PROPERTIES, SOURCE-->
<div class="tabbable">
    <ul role="tablist" class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#phpdoc-methods" aria-controls="phpdoc-methods" role="tab" data-toggle="tab">Methods</a></li>
        <li role="presentation"><a href="#phpdoc-properties" aria-controls="phpdoc-properties" role="tab" data-toggle="tab">Properties</a></li>
        <li role="presentation"><a href="#phpdoc-source" aria-controls="phpdoc-source" role="tab" data-toggle="tab">Source</a></li>
    </ul>
    <div class="tab-content">

        {{--METHODS--}}
        <div id="phpdoc-methods" role="tabpanel" class="tab-pane active">
            <div class="tabbable tabs-left">
                <ul role="tablist" class="nav nav-tabs">
                    @foreach($methods as $i => $method)
                        <li role="presentation" class="{{ $i === 0 ? 'active' : '' }}">
                            <a href="#method-{{ $method['name'] }}" aria-controls="method-{{ $method['name'] }}" role="tab" data-toggle="tab">
                                <i class="pr-xs phpdoc-visibility-{{ $method['visibility'] }}"></i>
                                {{ $method['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($methods as $method)
                        <div id="method-{{ $method['name'] }}" role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}">
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

                            <div class="tab-pane-content">
                                {{--Method description--}}
                                <p>{{ $method['description'] }}</p>
                                @if(isset($method['long-description']))
                                    <p>{!! $method['long-description'] !!}</p>
                                @endif

                                {{--Method tags--}}
                                <table class="table table-hover table-bordered table-phpdoc-tags">
                                    <tbody>
                                    @foreach($method['tags'] as $name => $tag)
                                        @if(in_array($name, ['param', 'return', 'example'], true))
                                            @continue
                                        @endif
                                        <tr>
                                            <th width='150'>{{ $tag['name'] }}</th>
                                            <td>{{ $tag['description'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                {{--Method examples--}}
                                @if(isset($method['tags']['example']))
                                    <h4>Example</h4>
                                    <pre class="language-php"><code class="language-php">{!! trim($method['tags']['example']['description']) !!}</code></pre>
                                @endif

                                {{--Method arguments--}}
                                @if(count($method['arguments']) > 0)
                                    <h4>Arguments</h4>
                                    @foreach($method['arguments'] as $argument)
                                        <div>
                                            <span>
                                                @include('codex-phpdoc::partials.type', ['type' => $argument['type']])
                                            </span>
                                            <span class="color-cyan-900">{{ $argument['name'] }}</span>
                                        </div>
                                        @if(isset($argument['description']))
                                            {!! $argument['description'] !!}
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{--PROPERTIES--}}
        <div id="phpdoc-properties" role="tabpanel" class="tab-pane">
            <table class="table table-hover table-striped table-bordered table-phpdoc-properties">
                <thead>
                <tr>
                    <th width="200px"><strong>Property</strong></th>
                    <th width="130px" class="text-center"><strong>Type</strong></th>
                    <th><strong>Description</strong></th>
                </tr>
                </thead>
                <tbody>
                @foreach($properties as $property)
                    <tr>
                        <td class="text-right color-teal-500 pr-xs pl-xs">
                            @if($property['static'] === true)
                                <span class="label label-xs label-info pull-right m-xs">static</span>
                            @endif
                            <i class="pr-xs phpdoc-visibility-protected"></i>
                            {{ $property['name'] }}
                        </td>
                        <td>
                            <p class="m-n">
                                @include('codex-phpdoc::partials.type', ['type' => $property['type']])
                            </p>
                        </td>
                        <td>
                            <small>{{ $property['description'] }}</small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="phpdoc-source" role="tabpanel" class="tab-pane">
            <pre class="language-php"><code class="language-php">{!! $source !!}</code></pre>
        </div>
    </div>
</div>
