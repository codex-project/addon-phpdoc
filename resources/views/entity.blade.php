<!-- HEADER-->
<header>
    <i class="phpdoc-type-{{ $type }}"></i>
    <h3 class="fs22">
        <span class="phpdoc-type-{{ $type }}">{{ str_replace_first('\\', '', $full_name) }}</span>

        @if(strlen($extends) > 0)
            <small class="pl-xs fs-13">extends</small>
            @include('codex-phpdoc::partials.type', ['type' => $extends, 'class' => 'fs-13'])
            {{--<a class="pl-md color-orange-800 fs-13" >{{ str_replace_first('\\', '', $extends) }}</a>--}}
        @endif
    </h3>
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
                    <th width="150">File Description</th>
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
<div class="tabbable" id="phpdoc-tabs">
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
                    <li role="presentation" class="seperator">
                        <span>Inherited</span>
                    </li>
                    @foreach($inherited_methods as $i => $method)
                        <li role="presentation" class="{{ $i === 0 ? 'active' : '' }}">
                            <a href="#method-{{ $method['name'] }}" aria-controls="method-{{ $method['name'] }}" role="tab" data-toggle="tab">
                                <i class="pr-xs phpdoc-visibility-{{ $method['visibility'] }}"></i>
                                {{ $method['name'] }}
                                <i class="phpdoc-inherited-method-icon" rel="tooltip" title="Inherited from: <br> {{ str_replace_first('\\', '', $method['class_name']) }}"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($methods as $method)
                        <div id="method-{{ $method['name'] }}" role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}">
                            @include('codex-phpdoc::partials.method', compact('method'))

                            <div class="tab-pane-content">
                                {{--Method description--}}
                                <h4>Description</h4>
                                <div class="block">
                                    <p>{{ $method['description'] }}</p>
                                    @if(isset($method['long-description']))
                                        <p>{!! $method['long-description'] !!}</p>
                                    @endif
                                </div>

                                {{--Method tags--}}
                                <table class="table table-hover table-bordered table-phpdoc-tags">
                                    <tbody>
                                    @foreach($method['tags'] as $name => $tag)
                                        @if(in_array($name, ['param', 'return', 'example'], true))
                                            @continue
                                        @endif
                                        <tr>
                                            <th width='150'>{{ $tag['name'] }}</th>
                                            <td>
                                                @if(isset($tag['description']))
                                                    @if($tag['name'] === 'link')
                                                        <a href="{{ $tag['description'] }}" target="_blank">{{ $tag['description'] }}</a>
                                                    @else
                                                        {{ $tag['description'] }}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                {{--Method examples--}}
                                @if(isset($method['tags']['example']['description']))
                                    <h4>Example</h4>
                                    <div class="block">
                                        <pre class="language-php"><code class="language-php">{!! trim($method['tags']['example']['description']) !!}</code></pre>
                                    </div>
                                @endif

                                {{--Method arguments--}}
                                @if(count($method['arguments']) > 0)
                                    <h4>Arguments</h4>
                                    <div class="block">
                                        @foreach($method['arguments'] as $argument)
                                            <div class="argument">
                                                @include('codex-phpdoc::partials.argument', ['argument' => $argument])
                                            </div>
                                            @if(isset($argument['description']) && strlen($argument['description']) > 0)
                                                <div class="block">{!! $argument['description'] !!}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{--Method return--}}
                                @if(isset($method['tags']['return']))
                                    <h4>Returns</h4>
                                    <div class="block">
                                        @if(isset($method['tags']['return']['type']))
                                            @include('codex-phpdoc::partials.type', ['type' => $method['tags']['return']['type'], 'typeFullName' => true])
                                        @endif
                                        @if(isset($method['tags']['return']['description']))
                                            <p>{!! $method['tags']['return']['description'] !!}</p>
                                        @endif
                                    </div>
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

        {{--SOURCE--}}
        <div id="phpdoc-source" role="tabpanel" class="tab-pane">
            <pre class="language-php line-numbers"><code class="language-php">{{ $source }}</code></pre>
        </div>
    </div>
</div>
