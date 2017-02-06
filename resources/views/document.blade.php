@extends(codex()->view('layout'))

@if(isset($document))
    @push('title')
    | {{ $document->getProject()->getDisplayName() }}
    {{ '@' }} {{ $document->getRef()->getName() }}
    | {{ $document->attr('title', $document->getPathName()) }}
    @endpush
@endif

@section('scripts')
    @parent
    <script>
        Vue.use(CodexPlugin)
        Vue.use(CodexPhpdocPlugin)
        var app = new codex.phpdoc.App({
            el: '#app',
            mounted(){
                this.closeSidebar()
            }
        })
    </script>
@stop

@section('body')
    <div id="page-loader">
        <div class="loader loader-page"></div>
    </div>
    <!--[if lt IE 10]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div id="app" class="page-phpdoc @yield('appClass', 'page-document' . (isset($ref) && $ref->hasSidebarMenu() === false ? ' sidebar-hidden':''))" v-cloak>
        <c-theme :class="classes">
            @section('header')
                <c-header ref="header" :show-toggle="true" :logoLink="{ name: 'welcome' }">
                    @stack('nav')
                </c-header>
            @show

            <div class="c-page" ref="page" :style="{ 'min-height': minHeights.page + 'px' }">
                @section('page')
                    <c-sidebar ref="sidebar" class="sidebar-compact" :min-height="minHeights.inner" active="{{ $document->url() }}">
                        @section('sidebar')
                            @if(isset($ref) && $ref->hasSidebarMenu())
                                {!! $ref->getSidebarMenu()->render($project, $ref) !!}
                            @endif
                        @show
                    </c-sidebar>

                    <c-content ref="content" :min-height="minHeights.inner">
                        @section('content')
                                {!! $document->render()  !!}
                        @show
                    </c-content>
                @show
            </div>

            <c-scroll-to-top></c-scroll-to-top>

            @include('codex::partials.footer');
        </c-theme>
    </div>

@stop
