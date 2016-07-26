var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        codex.defaultConfig.phpdoc = {
            jstree: {
                'plugins': ['types', 'search', 'wholerow'],
                'core': {
                    'themes': {
                        'responsive': false,
                        'name': 'codex'
                    }
                },
                'types': {
                    'default': { 'icon': 'fa fa-file' },
                    'folder': { 'icon': 'fa fa-folder color-blue-grey-500' },
                    'class': { icon: 'fa fa-file-code-o color-green-500' },
                    'interface': { icon: 'fa fa-code color-purple-800' },
                    'trait': { icon: 'fa fa-terminal color-blue-500' }
                }
            }
        };
        codex.ready(function () {
            console.log('ready');
            phpdoc.api = new phpdoc.PhpdocApi;
        });
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var defined = codex.util.defined;
        var create = codex.util.create;
        var PhpdocApi = (function (_super) {
            __extends(PhpdocApi, _super);
            function PhpdocApi() {
                _super.apply(this, arguments);
            }
            PhpdocApi.prototype.getEntities = function (project, ref, extras, fields) {
                return this.request('get', '/phpdoc/entities', {
                    project: project,
                    ref: ref,
                    full: defined(extras) ? extras : false,
                    fields: defined(fields) ? fields : []
                });
            };
            PhpdocApi.prototype.getTree = function (project, ref) {
                return this.request('get', '/phpdoc/entities', {
                    project: project,
                    ref: ref,
                    tree: true
                });
            };
            PhpdocApi.prototype.getEntity = function (project, ref, full_name, fields) {
                return this.request('get', '/phpdoc/entity', {
                    project: project,
                    ref: ref,
                    entity: full_name,
                    fields: defined(fields) ? fields : []
                });
            };
            return PhpdocApi;
        }(codex.Api));
        phpdoc.PhpdocApi = PhpdocApi;
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            function removeStartSlash(value) {
                if (!codex.util.defined(value)) {
                    return;
                }
                var matches = value.match(/^\\(.*)/);
                if (matches !== null && matches.length === 2) {
                    return matches[1];
                }
                return value;
            }
            components.removeStartSlash = removeStartSlash;
            ;
            Vue.filter('removeStartSlash', removeStartSlash);
            codex.defaultConfig.phpdoc = {
                jstree: {
                    'plugins': ['types', 'search', 'wholerow'],
                    'core': {
                        'themes': {
                            'responsive': false,
                            'name': 'codex'
                        }
                    },
                    'types': {
                        'default': { 'icon': 'fa fa-file' },
                        'folder': { 'icon': 'fa fa-folder color-blue-grey-500' },
                        'class': { icon: 'fa fa-file-code-o color-green-500' },
                        'interface': { icon: 'fa fa-code color-purple-800' },
                        'trait': { icon: 'fa fa-terminal color-blue-500' }
                    }
                }
            };
            var tpl = "\n        <header>\n            <!--<div class=\"phpdoc-settings-toggle\">\n                <a href=\"#\" v-on:click=\"toggleSettings($event)\"><i class=\"fa fa-cog\"></i><span class=\"caret\"></span></a>\n            </div>-->\n           <small>{{ subtitle }} </small>\n            <h1>{{ title }}</h1>\n        </header>\n\n        <div class=\"phpdoc\">\n            <p-tree :project.sync=\"project\" :ref.sync=\"ref\" :full-name.sync=\"fullName\" class=\"phpdoc-tree\"></p-tree>\n            <p-entity :project.sync=\"project\" :ref.sync=\"ref\" :full-name.sync=\"fullName\" :settings.sync=\"settings\" class=\"phpdoc-content\"></p-entity>\n        </div>\n\n    ";
            var App = (function (_super) {
                __extends(App, _super);
                function App() {
                    _super.apply(this, arguments);
                    this.title = 'API Documenation';
                    this.tree = {};
                    this.entities = [];
                    this.file = {};
                    this.settings = {};
                }
                App.prototype.beforeCompile = function () {
                    console.log('App (phpdoc) beforeCompile', this, 'project', this.project, 'ref', this.ref);
                    if (typeof localStorage.getItem('phpdoc') === 'string') {
                        this.settings = JSON.parse(localStorage.getItem('phpdoc'));
                    }
                    else {
                        this.settings = _.clone(App.defaultSettings);
                    }
                };
                App.prototype.activate = function (done) {
                    var _this = this;
                    console.log('App (phpdoc) activate');
                    phpdoc.api.getEntities(this.project, this.ref).then(function (res) {
                        _this.entities = res.data;
                        _this.$root.$set('entities', _this.entities);
                        console.log('App activate fetched data', res.data);
                        done();
                    }, function (err) { return console.log(err); }).fail(function (err) { return console.log(err); });
                };
                App.prototype.ready = function () {
                    var _this = this;
                    console.log('App (phpdoc) ready', this, 'with root', this.$root, 'project', this.project, 'ref', this.ref);
                    this.$watch('settings', function () {
                        console.log('App (phpdoc) waytvh settings', _this.$get('settings'));
                        localStorage.setItem('phpdoc', JSON.stringify(_this.$get('settings')));
                    }, { deep: true });
                    this.$on('entity.click', function (fullName) {
                        console.log('App entity.click fullName', fullName);
                        _this.fullName = fullName;
                    });
                    window.history.replaceState(null, this.fullName, window.location.pathname + "#!/" + this.fullName);
                    window.addEventListener("popstate", function (event) {
                        console.log('popstate', window.location);
                        if (location.hash.indexOf('#!/') !== -1) {
                            _this.fullName = location.hash.replace(/\#\!\//, '');
                        }
                    }, false);
                    this.$watch('fullName', function (fullName) {
                        codex['theme'].layout.hideTooltips();
                        window.history.pushState(null, fullName, window.location.pathname + "#!/" + fullName);
                    });
                };
                App.template = tpl;
                App.defaultSettings = {
                    filters: {
                        methods: {
                            show: {
                                inherited: false,
                                public: true,
                                protected: true,
                                private: true,
                            },
                            sort: {
                                by: 'name',
                                dir: 'asc'
                            },
                            view: 'detailed'
                        },
                        properties: {
                            show: {
                                inherited: false,
                                public: true,
                                protected: true,
                                private: true,
                            },
                            sort: {
                                by: 'name',
                                dir: 'asc'
                            }
                        }
                    }
                };
                __decorate([
                    codex.prop({ type: String, default: 'Api Documentation' })
                ], App.prototype, "title", void 0);
                __decorate([
                    codex.prop
                ], App.prototype, "subtitle", void 0);
                __decorate([
                    codex.prop
                ], App.prototype, "project", void 0);
                __decorate([
                    codex.prop
                ], App.prototype, "ref", void 0);
                __decorate([
                    codex.prop
                ], App.prototype, "fullName", void 0);
                __decorate([
                    codex.lifecycleHook('beforeCompile')
                ], App.prototype, "beforeCompile", null);
                __decorate([
                    codex.lifecycleHook('activate')
                ], App.prototype, "activate", null);
                __decorate([
                    codex.lifecycleHook('ready')
                ], App.prototype, "ready", null);
                App = __decorate([
                    codex.component('phpdoc')
                ], App);
                return App;
            }(codex.Component));
            components.App = App;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            function makeTpl() {
                return "\n<div class=\"phpdoc-content\" >\n    <header>\n        <i class=\"phpdoc-type-{{ file.type }}\"></i>\n        <h3 class=\"fs22\">\n            <span class=\"phpdoc-type-{{ file.type }}\">{{ entity.full_name | removeStartSlash}}</span>\n            <small v-if=\"hasExtend\" class=\"pl-xs fs-13\">extends</small>\n            <p-type v-if=\"hasExtend\" :type=\"entity.extends\" class=\"fs-13\"></p-type>\n        </h3>\n    </header>\n\n    <!-- DESC-->\n    <div class=\"phpdoc-content-description\">\n        <p v-if=\"hasDescription\" class=\"fs-13\">{{ entity.description }}</p>\n        <p-tags :object=\"entity\" :exclude=\"['example', 'inherited_from']\"></p-tags>\n    </div>\n\n    <!-- TABS: METHODS, PROPERTIES, SOURCE-->\n    <div class=\"tabbable phpdoc-content-tabs\" id=\"phpdoc-tabs\">\n        <ul role=\"tablist\" class=\"nav nav-tabs\">\n            <li role=\"presentation\" :class=\"{ 'active' : isActive('methods') }\"><a href=\"#\" @click.prevent=\"setActive('methods')\" aria-controls=\"phpdoc-methods\" role=\"tab\" >Methods</a></li>\n            <li role=\"presentation\" :class=\"['tab-filters-toggler', { 'active' : isActive('methods') }]\"><a href=\"#\" @click.prevent=\"toggleFiltersPane('methods')\" role=\"tab\" ><i class=\"fa fa-cog\"></i></a></li>\n\n            <li role=\"presentation\" :class=\"{ 'active' : isActive('properties') }\"><a href=\"#\" @click.prevent=\"setActive('properties')\" aria-controls=\"phpdoc-properties\" role=\"tab\" >Properties</a></li>\n            <li role=\"presentation\" :class=\"['tab-filters-toggler', { 'active' : isActive('properties') }]\"><a href=\"#\" @click.prevent=\"toggleFiltersPane('properties')\" role=\"tab\" ><i class=\"fa fa-cog\"></i></a></li>\n\n\n            <li role=\"presentation\" :class=\"{ 'active' : isActive('source') }\"><a href=\"#\" @click.prevent=\"setActive('source')\" aria-controls=\"phpdoc-source\" role=\"tab\" >Source</a></li>\n        </ul>\n        <div class=\"tab-content\">\n\n            <!--SETTINGS (COG)-->\n            <div class=\"tab-filters\" v-if=\"activeFiltersPane !== false\">\n                <p-filters v-if=\"isActiveFiltersPane('methods')\" class=\"tab-filters-pane\" type=\"methods\" :settings=\"settings.filters.methods\"></p-filters>\n                <p-filters v-if=\"isActiveFiltersPane('properties')\" class=\"tab-filters-pane\" type=\"properties\" :settings=\"settings.filters.properties\"></p-filters>\n            </div>\n\n            <!--METHODS-->\n            <div role=\"tabpanel\" :class=\"['tab-pane', { 'active' : isActive('methods') }]\">\n                <div class=\"tabbable tabs-left phpdoc-content-method-tabs\">\n                    <ul role=\"tablist\" class=\"nav nav-tabs\">\n\n                        <li v-for=\"method in getFiltered('methods')\" role=\"presentation\">\n                            <a @click.prevent=\"setMethod(method)\" role=\"tab\" href=\"#\">\n                                <i class=\"pr-xs phpdoc-visibility-{{ method.visibility }}\"></i>\n                                {{ method.name }}\n                                <i v-if=\"method.inherited\" class=\"phpdoc-inherited-method-icon\" rel=\"tooltip\" title=\"Inherited from: <br> {{ method.class_name }}\"></i>\n                            </a>\n                        </li>\n\n                    </ul>\n                    <div class=\"tab-content\">\n                        <div role=\"tabpanel\" class=\"tab-pane active\">\n\n                        <!--<div v-for=\"method in entity.methods\" id=\"method-{{ method.name }}\" role=\"tabpanel\" :class=\"['tab-pane', $index === 0 ? 'active' : '']\">-->\n                            <!--\n                            <div class=\"tab-pane-header\">\n                                <p-method-signature :entities=\"entities\" :entity=\"file.entity\" :name=\"method.name\"></p-method-signature>\n                            </div>\n                            <div class=\"tab-pane-content\">\n\n                                <h4 v-if=\"method.description.length > 0\">Description</h4>\n                                <div v-if=\"method.description.length > 0\" class=\"block\">\n                                    <p>{{ method.description }}</p>\n                                    <p v-if=\"method['long-description'].length > 0\">{{ method['long-description'] }}</p>\n                                </div>\n\n                                <h4 v-if=\"method.arguments.length > 0\">Arguments</h4>\n                                <div class=\"block\" v-if=\"method.arguments.length > 0\">\n                                    <div v-for=\"argument in method.arguments\" >\n                                        <div class=\"argument\">\n                                        <span v-for=\"type in argument.types\">\n                                            <span v-if=\"$index > 0\">|</span>\n                                            <p-type :type=\"type\" :fqn=\"false\"></p-type>\n                                        </span>\n                                            <span class=\"color-cyan-900\">&nbsp;{{ argument.name }}</span>\n                                            <span v-if=\"argument.default.length > 0\"> = {{ argument.default }}</span>\n                                        </div>\n                                        <div v-if=\"argument.description.length > 0\" class=\"block\">\n                                            {{ argument.description }}\n                                            <div v-if=\"argument['long-description'].length > 0\">\n                                                {{ argument['long-description'] }}\n                                            </div>\n                                        </div>\n                                    </div>\n                                </div>\n\n\n                                <h4>Returns</h4>\n                                <div class=\"block\">\n                                    <p-type :type=\"method.returns\" :fqn=\"true\"></p-type>\n                                </div>\n                            </div>\n\n                        </div>\n                        -->\n\n                        <p-method :method=\"method\"></p-method>\n\n                    </div>\n                </div>\n            </div>\n            </div>\n\n            <!--PROPERTIES-->\n            <div role=\"tabpanel\" :class=\"['tab-pane', { 'active' : isActive('properties') }]\">\n\n                <table class=\"table table-hover table-striped table-bordered table-phpdoc-properties\">\n                    <thead>\n                    <tr>\n                        <th width=\"200px\"><strong>Property</strong></th>\n                        <th width=\"130px\" class=\"text-center\"><strong>Type</strong></th>\n                        <th><strong>Description</strong></th>\n                    </tr>\n                    </thead>\n                    <tbody>\n                        <tr v-for=\"property in getFiltered('properties')\">\n                            <td :class=\"['text-right', 'color-teal-500', 'pr-xs', 'pl-xs', 'phpdoc-visibility-' + property.visibility]\">\n                                <span v-if=\"property.static\" class=\"label label-xs label-info pull-right m-xs\">static</span>\n                                <i class=\"pr-xs phpdoc-visibility-{{ method.visibility }}\"></i>\n                                {{ property.name }}\n                            </td>\n                            <td>\n                                <p class=\"m-n\">\n                                    <p-type :type=\"property.type\" :fqn=\"false\"></p-type>\n                                </p>\n                            </td>\n                            <td>\n                                <small>{{ property.description }}</small>\n                            </td>\n                        </tr>\n                    </tbody>\n                </table>\n\n            </div>\n\n            <!--SOURCE-->\n            <div role=\"tabpanel\" :class=\"['tab-pane', { 'active' : isActive('source') }]\">\n                <pre class=\"language-php line-numbers\"><code class=\"language-php\">{{ file.source }}</code></pre>\n            </div>\n        </div>\n    </div>\n\n</div>\n    ";
            }
            var Entity = (function (_super) {
                __extends(Entity, _super);
                function Entity() {
                    _super.apply(this, arguments);
                    this.file = {};
                    this.active = 'methods';
                    this.activeFiltersPane = false;
                    this.count = { methods: 0, properties: 0 };
                }
                Entity.prototype.activate = function (done) {
                    console.log('Entity activate', 'project', this.project, 'ref', this.ref);
                    this.refreshEntity().then(function () { return done(); });
                };
                Entity.prototype.ready = function () {
                    var _this = this;
                    console.log('Entity ready', this);
                    this.$on('filters.close', function () {
                        _this.closeFiltersPane();
                    });
                    this.$watch('methodCount', function (newVal) {
                        console.log('methodCount changed', newVal, 'current method: ', _this.method.name);
                    });
                    this.$watch('file', function () {
                        this.setMethod(this.entity.methods[0]);
                        if (codex.util.defined(window['Prism'])) {
                            window['Prism'].highlightAll();
                            $('.line-numbers-rows span').wrap($('<a>').attr({
                                href: '#'
                            }));
                        }
                    });
                    this.refreshEntity();
                    this.$watch('fullName', function (newVal) { return _this.refreshEntity(); });
                };
                Entity.prototype.refreshEntity = function () {
                    var _this = this;
                    return phpdoc.api.getEntity(this.project, this.ref, this.fullName).then(function (data) {
                        _this.file = data.data;
                    });
                };
                Entity.prototype.getFiltered = function (type) {
                    var items = _.chain(this.entity[type]);
                    if (this.settings.filters[type].show.inherited === false) {
                        items = items.filter(function (item) { return item.inherited !== true; });
                    }
                    if (this.settings.filters[type].show.public === false) {
                        items = items.filter(function (item) { return item.visibility !== 'public'; });
                    }
                    if (this.settings.filters[type].show.protected === false) {
                        items = items.filter(function (item) { return item.visibility !== 'protected'; });
                    }
                    if (this.settings.filters[type].show.private === false) {
                        items = items.filter(function (item) { return item.visibility !== 'private'; });
                    }
                    console.log('getFiltered type', type, 'items', items);
                    items = items.sortBy(this.settings.filters[type].sort.by);
                    if (this.settings.filters[type].sort.dir === 'desc') {
                        items = items.reverse();
                    }
                    this.count[type] = items.value().length;
                    return items.value();
                };
                Entity.prototype.setActive = function (name) {
                    this.active = name;
                    this.activeFiltersPane = false;
                };
                Entity.prototype.isActive = function (name) {
                    return this.active === name;
                };
                Entity.prototype.setMethod = function (method) {
                    if (typeof method === 'string') {
                        method = _.find(this.entity.methods, { name: name });
                    }
                    this.$set('method', method);
                };
                Entity.prototype.toggleFiltersPane = function (name) {
                    if (this.activeFiltersPane === name) {
                        this.activeFiltersPane = false;
                    }
                    else {
                        this.activeFiltersPane = name;
                        this.active = name;
                    }
                };
                Entity.prototype.isActiveFiltersPane = function (name) {
                    return this.activeFiltersPane === name;
                };
                Entity.prototype.closeFiltersPane = function () {
                    this.activeFiltersPane = false;
                };
                Object.defineProperty(Entity.prototype, "entity", {
                    get: function () {
                        return this.file.entity;
                    },
                    enumerable: true,
                    configurable: true
                });
                Object.defineProperty(Entity.prototype, "hasExtend", {
                    get: function () {
                        return this.entity.extends.length > 0;
                    },
                    enumerable: true,
                    configurable: true
                });
                Object.defineProperty(Entity.prototype, "hasDescription", {
                    get: function () {
                        return this.entity.description.length > 0;
                    },
                    enumerable: true,
                    configurable: true
                });
                Entity.template = makeTpl();
                __decorate([
                    codex.prop
                ], Entity.prototype, "project", void 0);
                __decorate([
                    codex.prop
                ], Entity.prototype, "ref", void 0);
                __decorate([
                    codex.prop
                ], Entity.prototype, "fullName", void 0);
                __decorate([
                    codex.prop
                ], Entity.prototype, "settings", void 0);
                __decorate([
                    codex.lifecycleHook('activate')
                ], Entity.prototype, "activate", null);
                __decorate([
                    codex.lifecycleHook('ready')
                ], Entity.prototype, "ready", null);
                Entity = __decorate([
                    codex.component('p-entity')
                ], Entity);
                return Entity;
            }(codex.Component));
            components.Entity = Entity;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var tpl = "\n<div>\n    <form class=\"form-horizontal\">\n        <div class=\"row\">\n            <div class=\"col-md-3 hide\">\n                <content></content>\n            </div>\n            <div class=\"col-md-3\">\n            <h4>Filters</h4>\n                <div class=\"checkbox\"> <label> <input type=\"checkbox\" v-model=\"settings.show.inherited\"> Show inherited {{ name }}</label> </div>\n                <div class=\"checkbox\"> <label> <input type=\"checkbox\" v-model=\"settings.show.public\"> Show public {{ name }}</label> </div>\n                <div class=\"checkbox\"> <label> <input type=\"checkbox\" v-model=\"settings.show.protected\"> Show protected {{ name }}</label> </div>\n                <div class=\"checkbox\"> <label> <input type=\"checkbox\" v-model=\"settings.show.private\"> Show private {{ name }}</label> </div>\n            </div>\n            <div class=\"col-md-3\">\n                <h4>Sorting</h4>\n                <div class=\"form-group\">\n                    <label for=\"inputEmail3\" class=\"col-sm-2 hide control-label\">Email</label>\n                    <div class=\"col-sm-12\">\n                        <select class=\"form-control\" v-model=\"settings.sort.by\">\n                          <option v-for=\"option in sortMethodsOptions\">{{ option }}</option>\n                        </select>\n                    </div>\n                </div>\n                <div class=\"form-group\">\n                    <div class=\"radio col-sm-6\">\n                        <label> <input type=\"radio\" value=\"asc\" v-model=\"settings.sort.dir\"> Ascending </label>\n                    </div>\n                    <div class=\"radio col-sm-6\">\n                        <label> <input type=\"radio\" value=\"desc\" v-model=\"settings.sort.dir\"> Descending </label>\n                    </div>\n                </div>\n            </div>\n            <div class=\"col-md-2 col-md-offset-1\">\n            <h4>&nbsp;</h4>\n            <a href=\"#\" class=\"pull-right btn btn-block\" @click.prevent=\"closeFilters()\">Close</a>\n            <a href=\"#\" class=\"pull-right btn btn-block\" @click.prevent=\"resetFilters()\">Reset to default</a>\n            </div>\n        </div>\n    </form>\n</div>\n    ";
            var Filters = (function (_super) {
                __extends(Filters, _super);
                function Filters() {
                    _super.apply(this, arguments);
                    this.file = {};
                    this.sortMethodsOptions = ['name', 'visibility'];
                }
                Filters.prototype.resetFilters = function () {
                    this.settings = components.App.defaultSettings.filters[this.type];
                };
                Filters.prototype.closeFilters = function () {
                    this.$broadcast('filters.close');
                };
                Object.defineProperty(Filters.prototype, "entity", {
                    get: function () {
                        return this.file.entity;
                    },
                    enumerable: true,
                    configurable: true
                });
                Filters.prototype.ready = function () {
                    console.log('filters ready for ', this.type, 'with settings', this.settings);
                };
                Filters.template = tpl;
                __decorate([
                    codex.prop
                ], Filters.prototype, "file", void 0);
                __decorate([
                    codex.prop
                ], Filters.prototype, "settings", void 0);
                __decorate([
                    codex.prop
                ], Filters.prototype, "type", void 0);
                __decorate([
                    codex.lifecycleHook('ready')
                ], Filters.prototype, "ready", null);
                Filters = __decorate([
                    codex.component('p-filters')
                ], Filters);
                return Filters;
            }(codex.Component));
            components.Filters = Filters;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var tpl = "\n    <div class=\"phpdoc-method-signature\">\n    <span :class=\"['phpdoc-visibility-' + method.visibility, class ? class : '' ]\">{{ method.visibility }}</span>&nbsp;{{ method.name }}&nbsp;<strong>(</strong>\n    <span v-for=\"argument in method.arguments\">\n        <strong v-if=\"$index !== 0\">,</strong>\n        <span v-for=\"type in argument.types\">\n            <span v-if=\"$index > 0\">|</span>\n            <p-type :type=\"type\" :fqn=\"false\"></p-type>\n        </span>\n        <span class=\"color-cyan-900\">{{ argument.name }}</span>\n    </span>\n    <strong>)</strong>\n    <p-type v-if=\"hasReturn\" :entities=\"entities\" :type=\"returns.type\"></p-type>\n</div>\n\n    ";
            var MethodSignature = (function (_super) {
                __extends(MethodSignature, _super);
                function MethodSignature() {
                    _super.apply(this, arguments);
                }
                MethodSignature.prototype.ready = function () {
                };
                Object.defineProperty(MethodSignature.prototype, "hasReturn", {
                    get: function () {
                        return this.$get('method.returns').length > 0;
                    },
                    enumerable: true,
                    configurable: true
                });
                MethodSignature.template = tpl;
                __decorate([
                    codex.prop({ type: Object })
                ], MethodSignature.prototype, "method", void 0);
                __decorate([
                    codex.lifecycleHook('ready')
                ], MethodSignature.prototype, "ready", null);
                MethodSignature = __decorate([
                    codex.component('p-method-signature')
                ], MethodSignature);
                return MethodSignature;
            }(codex.Component));
            components.MethodSignature = MethodSignature;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var tpl = "\n<div class=\"phpdoc-method\">\n    <div class=\"tab-pane-header\">\n        <p-method-signature :method=\"method\"></p-method-signature>\n    </div>\n    <div class=\"tab-pane-content\">\n\n        <h4 v-if=\"method.description.length > 0\">Description</h4>\n        <div v-if=\"method.description.length > 0\" class=\"block\">\n            <p>{{ method.description }}</p>\n            <p v-if=\"method['long-description'].length > 0\">{{ method['long-description'] }}</p>\n        </div>\n\n        <h4 v-if=\"method.arguments.length > 0\">Arguments</h4>\n        <div class=\"block\" v-if=\"method.arguments.length > 0\">\n            <div v-for=\"argument in method.arguments\">\n                <div class=\"argument\">\n                                        <span v-for=\"type in argument.types\">\n                                            <span v-if=\"$index > 0\">|</span>\n                                            <p-type :type=\"type\" :fqn=\"false\"></p-type>\n                                        </span>\n                    <span class=\"color-cyan-900\">&nbsp;{{ argument.name }}</span>\n                    <span v-if=\"argument.default.length > 0\"> = {{ argument.default }}</span>\n                </div>\n                <div v-if=\"argument.description.length > 0\" class=\"block\" >\n                    {{{ argument.description }}}\n                    <div v-if=\"argument['long-description'].length > 0\">\n                        {{{ argument['long-description'] }}}\n                    </div>\n                </div>\n            </div>\n        </div>\n\n\n        <h4>Tags</h4>\n        <div class=\"block\">\n            <p-tags :object=\"method\" :exclude=\"['param', 'example', 'return']\"></p-tags>\n        </div>\n\n\n        <h4>Returns</h4>\n        <div class=\"block\">\n            <p-type :type=\"method.returns\" :fqn=\"true\"></p-type>\n        </div>\n\n\n\n    </div>\n</div>\n    ";
            var Method = (function (_super) {
                __extends(Method, _super);
                function Method() {
                    _super.apply(this, arguments);
                }
                Method.template = tpl;
                __decorate([
                    codex.prop({ type: Object })
                ], Method.prototype, "method", void 0);
                Method = __decorate([
                    codex.component('p-method')
                ], Method);
                return Method;
            }(codex.Component));
            components.Method = Method;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var Parameter = (function (_super) {
            __extends(Parameter, _super);
            function Parameter() {
                _super.apply(this, arguments);
            }
            Parameter.template = "";
            __decorate([
                codex.prop({ type: Object })
            ], Parameter.prototype, "entity", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Parameter.prototype, "method", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Parameter.prototype, "name", void 0);
            return Parameter;
        }(codex.Component));
        phpdoc.Parameter = Parameter;
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var Property = (function (_super) {
                __extends(Property, _super);
                function Property() {
                    _super.apply(this, arguments);
                }
                Property.template = "";
                __decorate([
                    codex.prop({ type: Object })
                ], Property.prototype, "entity", void 0);
                __decorate([
                    codex.prop({ type: String })
                ], Property.prototype, "name", void 0);
                return Property;
            }(codex.Component));
            components.Property = Property;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var tpl = "\n        <table v-if=\"object.tags.length > 0\" class=\"table table-hover table-bordered table-tags\">\n            <tbody>\n            <tr v-for=\"tag in tags\">\n                <th width=\"150\" valign=\"middle\">{{ tag.name }}</th>\n                <td>{{{ tag.description }}}</td>\n            </tr>\n            </tbody>\n        </table>\n    ";
            var Tags = (function (_super) {
                __extends(Tags, _super);
                function Tags() {
                    _super.apply(this, arguments);
                    this.exclude = [];
                }
                Object.defineProperty(Tags.prototype, "tags", {
                    get: function () {
                        var _this = this;
                        var tags = _.filter(this.object.tags, function (tag) {
                            return _this.exclude.indexOf(tag.name);
                        });
                        tags = _.filter(tags, function (tag) {
                            return _this.hasTagHandler(tag) === false;
                        });
                        return tags;
                    },
                    enumerable: true,
                    configurable: true
                });
                Tags.prototype.hasTagHandler = function (tag) {
                    var tagHandler = _.find(phpdoc.tagHandlers, { 'name': tag.name });
                    console.log('found taghandler for tag', tag.name, codex.util.defined(tagHandler), tagHandler);
                    return codex.util.defined(tagHandler);
                };
                Tags.template = tpl;
                __decorate([
                    codex.prop
                ], Tags.prototype, "object", void 0);
                __decorate([
                    codex.prop({ type: Array, default: [] })
                ], Tags.prototype, "exclude", void 0);
                Tags = __decorate([
                    codex.component('p-tags')
                ], Tags);
                return Tags;
            }(codex.Component));
            components.Tags = Tags;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var Tree = (function (_super) {
                __extends(Tree, _super);
                function Tree() {
                    _super.apply(this, arguments);
                    this.ignoreTreeSelect = false;
                }
                Tree.prototype.activate = function (done) {
                    var _this = this;
                    console.log('Tree activate', this, done);
                    console.log('Tree activate project', this.project, 'ref', this.ref);
                    phpdoc.api.getTree(this.project, this.ref).then(function (data) {
                        _this.items = data.data;
                        done();
                    });
                };
                Tree.prototype.beforeCompile = function () {
                    var _this = this;
                    this.$watch('fullName', function (fullName) { return _this.openTreeTo(fullName); });
                };
                Tree.prototype.ready = function () {
                    var _this = this;
                    this.$tree = $(this.$els.tree);
                    this.makeTree(this.$get('items'));
                    phpdoc.api.getTree(this.project, this.ref).then(function (data) {
                        _this.items = data.data;
                        _this.makeTree(_this.items);
                    });
                };
                Tree.prototype.makeTree = function (items) {
                    var _this = this;
                    codex.util.defined(this.tree) && this.tree.destroy();
                    codex.util.defined(this.$treeRoot) && this.$treeRoot.remove();
                    this.$treeRoot = $('<ul>').appendTo(this.$tree);
                    this.traverseTree(items, this.$treeRoot);
                    this.$tree.jstree(codex.config('phpdoc.jstree'));
                    this.tree = this.$tree.jstree();
                    this.$tree.on('select_node.jstree', this, function (event, data) {
                        _this.$dispatch('tree.select', event, data);
                        _this.fullName = data.node.data.fullName;
                    });
                };
                Tree.prototype.traverseTree = function (items, $tree, level) {
                    if (level === void 0) { level = 0; }
                    for (var k in items) {
                        var item = items[k];
                        if (isNaN(parseInt(k))) {
                            var $nel = $('<ul>');
                            var $nli = $('<li>').text(k).append($nel);
                            $nli.addClass('fs-12');
                            if (level == 0) {
                                $nli.attr('data-jstree', '{ "opened" : true, "type" : "folder" }');
                            }
                            else {
                                $nli.attr('data-jstree', '{ "type" : "folder" }');
                            }
                            var namePath = "\\" + k;
                            if (typeof $tree.closest('li').attr('data-full-name') !== "undefined") {
                                namePath = $tree.closest('li').attr('data-full-name') + namePath;
                            }
                            $nli.attr('data-full-name', namePath);
                            $tree.prepend($nli);
                            this.traverseTree(item, $nel, level++);
                        }
                        else {
                            $tree.append($('<li>')
                                .text(item['name'])
                                .attr('data-jstree', '{ "type": "' + item['type'] + '" }')
                                .attr('data-full-name', item['full_name']));
                        }
                    }
                };
                Tree.prototype.searchTree = function (fullName) {
                    var items = this.tree.get_json(null, { flat: true });
                    codex.debug.log('search for', fullName, 'in', items);
                    var found = false;
                    items.forEach(function (item) {
                        if (typeof item.data.fullName !== "undefined" && _.endsWith(item.data.fullName, fullName)) {
                            codex.debug.log('search for', fullName, 'found', item);
                            found = item;
                            return false;
                        }
                    });
                    return found;
                };
                Tree.prototype.openTreeTo = function (fullName) {
                    var node = this.searchTree(fullName);
                    if (node !== false) {
                        this.ignoreTreeSelect = true;
                        this.tree.close_all();
                        this.tree._open_to(node);
                        this.tree.deselect_all();
                        this.tree.select_node(node);
                        this.ignoreTreeSelect = false;
                    }
                };
                Tree.template = "\n        <div v-el:tree class=\"phpdoc-tree\"></div>\n        ";
                __decorate([
                    codex.prop
                ], Tree.prototype, "project", void 0);
                __decorate([
                    codex.prop
                ], Tree.prototype, "ref", void 0);
                __decorate([
                    codex.prop
                ], Tree.prototype, "fullName", void 0);
                __decorate([
                    codex.lifecycleHook('activate')
                ], Tree.prototype, "activate", null);
                __decorate([
                    codex.lifecycleHook('beforeCompile')
                ], Tree.prototype, "beforeCompile", null);
                __decorate([
                    codex.lifecycleHook('ready')
                ], Tree.prototype, "ready", null);
                Tree = __decorate([
                    codex.component('p-tree')
                ], Tree);
                return Tree;
            }(codex.Component));
            components.Tree = Tree;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var components;
        (function (components) {
            var tpl = "\n    <span>\n<span v-if=\"! isEntity\" class=\"simple-type simple-type-string\">{{ formattedType }}</span>\n<a v-if=\"isEntity\" class=\"type-link local\" href=\"#\" rel=\"tooltip\" :title=\"type | removeStartSlash\" :data-phpdoc-popover=\"type\" v-on:click.prevent=\"onEntityClick\">{{ formattedType }}</a>\n</span>\n\n";
            var Type = (function (_super) {
                __extends(Type, _super);
                function Type() {
                    _super.apply(this, arguments);
                    this.type = '';
                    this.fqn = true;
                    this.isEntity = false;
                    this.hasEntity = false;
                }
                Object.defineProperty(Type.prototype, "formattedType", {
                    get: function () {
                        var type = components.removeStartSlash(this.$get('type'));
                        if (this.fqn === false) {
                            type = _.last(type.split('\\'));
                        }
                        return type;
                    },
                    enumerable: true,
                    configurable: true
                });
                Type.prototype.onEntityClick = function () {
                    console.log('onEntityClick', this);
                    this.isEntity === true && this.$dispatch('entity.click', this.type);
                };
                Type.prototype.ready = function () {
                    if (!codex.util.defined(this.type)) {
                        return;
                    }
                    this.isEntity = this.type[0] === '\\';
                    if (this.isEntity) {
                        var found = _.find(this.$root.$get('entities'), { 'full_name': this.type });
                        this.hasEntity = codex.util.defined(found['name']);
                    }
                };
                Type.template = tpl;
                __decorate([
                    codex.prop({ type: String, required: true })
                ], Type.prototype, "type", void 0);
                __decorate([
                    codex.prop({ type: Boolean, default: true })
                ], Type.prototype, "fqn", void 0);
                __decorate([
                    codex.lifecycleHook('ready')
                ], Type.prototype, "ready", null);
                Type = __decorate([
                    codex.component('p-type')
                ], Type);
                return Type;
            }(codex.Component));
            components.Type = Type;
        })(components = phpdoc.components || (phpdoc.components = {}));
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var PhpdocHelper = (function () {
            function PhpdocHelper() {
                this._list = [];
                this._tree = {};
            }
            PhpdocHelper.prototype.init = function (project, ref) {
                if (project === void 0) { project = ''; }
                if (ref === void 0) { ref = 'master'; }
                this.defer = codex.util.create();
                return this;
            };
            PhpdocHelper.prototype.ready = function (cb) {
                return this.defer.promise.then(cb);
            };
            Object.defineProperty(PhpdocHelper.prototype, "list", {
                get: function () {
                    return this._list;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(PhpdocHelper.prototype, "tree", {
                get: function () {
                    return this._tree;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(PhpdocHelper.prototype, "api", {
                get: function () {
                    return this._api;
                },
                enumerable: true,
                configurable: true
            });
            PhpdocHelper.prototype.initLinks = function () {
                var attr = {
                    trigger: 'hover',
                    html: true,
                    viewport: 'body',
                    container: 'body',
                    placement: 'top'
                };
                var $link = $('.phpdoc-link');
                $link.tooltip(_.merge(attr, {
                    template: "<div class=\"tooltip tooltip-phpdoc\" role=\"tooltip\"><div class=\"tooltip-arrow\"></div><div class=\"tooltip-inner\"></div></div>"
                }));
                var $popoverLink = $('.phpdoc-popover-link');
                $popoverLink.popover(_.merge(attr, {
                    template: "<div class=\"popover popover-phpdoc\" role=\"tooltip\"><div class=\"arrow\"></div><h3 class=\"popover-title\"></h3><div class=\"popover-content\"></div></div>"
                }));
            };
            PhpdocHelper.prototype.classLink = function (fullName) {
                return window.location.pathname + "#!/" + fullName;
            };
            PhpdocHelper.prototype.makeTypeLink = function (types) {
                var _this = this;
                var els = [];
                types.toString().split('|').forEach(function (type) {
                    var isAdvancedtype = type.indexOf('\\') !== -1;
                    if (!isAdvancedtype) {
                        els.push($('<span>')
                            .text(type)
                            .addClass('simple-type simple-type-' + type.toLowerCase())
                            .get(0)
                            .outerHTML);
                    }
                    else {
                        var found = _.find(_this._list, { full_name: type });
                        var $a = $('<a>')
                            .text(type.split('\\').reverse()[0])
                            .addClass('type-link')
                            .attr('title', type);
                        if (codex.util.defined(found)) {
                            $a.addClass('local');
                            $a.attr('href', _this.classLink(type));
                        }
                        els.push($a.get(0).outerHTML);
                    }
                });
                return els.join(' | ');
            };
            PhpdocHelper.prototype.methodCallsign = function (method) {
                var txt = method.visibility;
                if (method.abstract) {
                    txt = 'abstract ' + txt;
                }
                if (method.final) {
                    txt += ' final';
                }
                if (method.static) {
                    txt += ' static';
                }
                return txt;
            };
            PhpdocHelper.prototype.removeValues = function (arr) {
                var what, a = arguments, L = a.length, ax;
                while (L > 1 && arr.length) {
                    what = a[--L];
                    while ((ax = arr.indexOf(what)) !== -1) {
                        arr.splice(ax, 1);
                    }
                }
                return arr;
            };
            return PhpdocHelper;
        }());
        phpdoc.PhpdocHelper = PhpdocHelper;
        jQuery.extend({
            phpdoc: new PhpdocHelper
        });
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        var PhpdocWidget = (function (_super) {
            __extends(PhpdocWidget, _super);
            function PhpdocWidget() {
                _super.call(this);
                this.widgetEventPrefix = 'phpdoc';
                this.options = {
                    project: '',
                    ref: 'master',
                    defaultClass: null,
                    styleClasses: {
                        container: 'phpdoc',
                        tree: 'phpdoc-tree',
                        content: 'phpdoc-content',
                    },
                    jstree: {
                        'plugins': ['types', 'search', 'wholerow'],
                        'core': {
                            'themes': {
                                'responsive': false,
                                'name': 'codex'
                            }
                        },
                        'types': {
                            'default': { 'icon': 'fa fa-file' },
                            'folder': { 'icon': 'fa fa-folder color-blue-grey-500' },
                            'class': { icon: 'fa fa-file-code-o color-green-500' },
                            'interface': { icon: 'fa fa-code color-purple-800' },
                            'trait': { icon: 'fa fa-terminal color-blue-500' }
                        }
                    }
                };
                this.history = [];
                this.ignoreTreeSelect = false;
            }
            PhpdocWidget.prototype.$ = function (sel) {
                return this.element.find(sel);
            };
            PhpdocWidget.prototype._create = function () {
                var _this = this;
                if (codex.config('debug')) {
                    window['widget'] = this;
                }
                this.$el = this.element;
                this.data = { list: [], tree: {}, entities: [] };
                this.$el.html('');
                this.$el.ensureClass(this.options.styleClasses.container);
                this.$tree = $('<div>').addClass(this.options.styleClasses.tree).appendTo(this.$el);
                this.$treeRoot = $('<ul>').appendTo(this.$tree);
                this.$content = $('<div>').addClass(this.options.styleClasses.content).appendTo(this.$el);
                codex.startLoader(this.$content);
                $.phpdoc.init(this.options.project, this.options.ref);
                this.api = $.phpdoc.api;
                $.phpdoc.ready(function () {
                    _this.data.list = $.phpdoc.list;
                    _this.data.tree = $.phpdoc.tree;
                    codex.stopLoader(_this.$content);
                    _this._createTree();
                    var fullName;
                    if (location.hash.indexOf('#!/') !== -1) {
                        fullName = location.hash.replace(/\#\!\//, '');
                    }
                    else if (_this.options.defaultClass !== null) {
                        fullName = _this.options.defaultClass;
                    }
                    else {
                        fullName = _this.data.list[0].full_name;
                    }
                    window.history.replaceState(null, fullName, window.location.pathname + "#!/" + fullName);
                    window.addEventListener("popstate", function (event) {
                        console.log('popstate', window.location);
                        if (location.hash.indexOf('#!/') !== -1) {
                            _this.open(location.hash.replace(/\#\!\//, ''));
                        }
                    }, false);
                    _this.open(fullName);
                    _this._bindTreeListener();
                });
            };
            PhpdocWidget.prototype._destroy = function () {
                codex.debug.log('destroy');
            };
            PhpdocWidget.prototype._createTree = function () {
                this._traverseTree(this.data.tree, this.$treeRoot, 0);
                this.$tree.jstree(this.options.jstree);
                this.tree = this.$tree.jstree();
            };
            PhpdocWidget.prototype._bindTreeListener = function () {
                var _this = this;
                this.$tree.on('select_node.jstree', this, function (event, data) {
                    if (_this.ignoreTreeSelect)
                        return;
                    codex.debug.log('select_node.jstree', data);
                    codex.debug.log('Selected type', data.node.type);
                    if (data.node.type === 'folder') {
                        _this.tree.open_node(data.node);
                    }
                    else {
                        var fullName = data.node.data.fullName;
                        _this.open(fullName);
                        window.history.pushState(null, fullName, window.location.pathname + "#!/" + fullName);
                    }
                });
            };
            PhpdocWidget.prototype.scrollToBegin = function () {
                $('html, body').animate({ scrollTop: this.$content.offset().top }, 800);
            };
            PhpdocWidget.prototype.gotoSourceLine = function (nr) {
                this.openTab('source');
                var lines = $('.line-numbers-rows span');
                var line = $(lines[nr]);
                $('html, body').animate({ scrollTop: line.offset().top - line.height() }, 800);
            };
            PhpdocWidget.prototype.openTab = function (name) {
                $('#phpdoc-tabs a[href="#phpdoc-' + name + '"]').tab('show');
            };
            PhpdocWidget.prototype.open = function (name) {
                var _this = this;
                console.log('phpdoc open', name);
                codex.startLoader(this.$content);
                this.$('.type-link').tooltip('hide');
                codex.debug.profile('doc-request');
                return this.api.doc(name).then(function (doc) {
                    codex.debug.profileEnd();
                    codex.stopLoader(_this.$content);
                    codex.debug.profile('doc-html');
                    _this.$content.html(doc);
                    codex.debug.profileEnd();
                    async.parallel([
                        function (cb) {
                            codex.debug.profile('tooltips');
                            _this.$('.type-link, .visibility-icon').tooltip({ viewport: 'body', container: 'body' });
                            cb();
                            codex.debug.profileEnd();
                        },
                        function (cb) {
                            codex.debug.profile('highlight');
                            Prism.highlightAll();
                            cb();
                            codex.debug.profileEnd();
                        },
                        function (cb) {
                            codex.debug.profile('tree');
                            _this.openTreeTo(name);
                            cb();
                            codex.debug.profileEnd();
                        },
                        function (cb) {
                            codex.debug.profile('scroll');
                            _this.scrollToBegin();
                            cb();
                            codex.debug.profileEnd();
                        },
                        function (cb) {
                            codex.debug.profile('inherit-method');
                            _this.$('.phpdoc-inherited-method-icon').on('click', function (e) {
                                e.stopPropagation();
                                e.preventDefault();
                                console.log('go to entity');
                            });
                            cb();
                            codex.debug.profileEnd();
                        }
                    ], function () {
                        console.log('cb done', arguments);
                    });
                }).otherwise(function (e) {
                    console.error(e);
                });
            };
            PhpdocWidget.prototype._traverseTree = function (items, $tree, level) {
                for (var k in items) {
                    var item = items[k];
                    if (isNaN(parseInt(k))) {
                        var $nel = $('<ul>');
                        var $nli = $('<li>').text(k).append($nel);
                        $nli.addClass('fs-12');
                        if (level == 0) {
                            $nli.attr('data-jstree', '{ "opened" : true, "type" : "folder" }');
                        }
                        else {
                            $nli.attr('data-jstree', '{ "type" : "folder" }');
                        }
                        var namePath = "\\" + k;
                        if (typeof $tree.closest('li').attr('data-full-name') !== "undefined") {
                            namePath = $tree.closest('li').attr('data-full-name') + namePath;
                        }
                        $nli.attr('data-full-name', namePath);
                        $tree.prepend($nli);
                        this._traverseTree(item, $nel, level++);
                    }
                    else {
                        $tree.append($('<li>')
                            .text(item['name'])
                            .attr('data-jstree', '{ "type": "' + item['type'] + '" }')
                            .attr('data-full-name', item['full_name']));
                    }
                }
            };
            PhpdocWidget.prototype.searchTree = function (fullName) {
                var items = this.tree.get_json(null, { flat: true });
                codex.debug.log('search for', fullName, 'in', items);
                var found = false;
                items.forEach(function (item) {
                    if (typeof item.data.fullName !== "undefined" && _.endsWith(item.data.fullName, fullName)) {
                        codex.debug.log('search for', fullName, 'found', item);
                        found = item;
                        return false;
                    }
                });
                return found;
            };
            PhpdocWidget.prototype.openTreeTo = function (fullName) {
                var node = this.searchTree(fullName);
                if (node !== false) {
                    this.ignoreTreeSelect = true;
                    this.tree.close_all();
                    this.tree._open_to(node);
                    this.tree.deselect_all();
                    this.tree.select_node(node);
                    this.ignoreTreeSelect = false;
                }
            };
            return PhpdocWidget;
        }(codex.util.Widget));
        phpdoc.PhpdocWidget = PhpdocWidget;
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.helper = new phpdoc.PhpdocHelper;
        phpdoc.tagHandlers = [];
        phpdoc.tagHandlers.push({
            name: 'example',
            handler: function () {
                console.log('example handler');
            }
        });
        function init(options) {
            var _this = this;
            if (options === void 0) { options = {}; }
            options = _.merge({
                project: codex.config('phpdoc.project'),
                ref: codex.config('phpdoc.ref'),
                fullName: codex.config('phpdoc.default_class')
            }, options);
            $('article.content').html('<phpdoc :project="project" :ref="ref" :full-name="fullName"></phpdoc>');
            console.log('init options', options);
            var VM = Vue.extend({
                project: options.project,
                ref: options.ref,
                fullName: options.fullName,
                data: function () {
                    var data = _.merge({
                        title: 'Api Documentation'
                    }, options);
                    console.log('root vm data', data, _this);
                    return data;
                }
            });
            var vm = new VM;
            console.log('phpdoc mounting window.vm', vm);
            window['vm'] = vm;
            vm.$mount('article.content');
            console.log('phpdoc mounted window.vm', vm);
        }
        phpdoc.init = init;
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
//# sourceMappingURL=phpdoc.js.map