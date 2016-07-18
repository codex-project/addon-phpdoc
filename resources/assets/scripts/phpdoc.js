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
        var PersistableConfig = (function (_super) {
            __extends(PersistableConfig, _super);
            function PersistableConfig(key, obj) {
                _super.call(this, obj);
                this.key = key;
                this.load();
            }
            PersistableConfig.prototype.set = function (prop, value) {
                _super.prototype.set.call(this, prop, value);
                this.save();
                return this;
            };
            PersistableConfig.prototype.unset = function (prop) {
                _super.prototype.unset.call(this, prop);
                this.save();
                return this;
            };
            PersistableConfig.prototype.save = function () {
                window.localStorage.setItem(this.key, JSON.stringify(this.data));
            };
            PersistableConfig.prototype.load = function () {
                if (typeof window.localStorage.getItem(this.key) === 'string') {
                    this.merge(JSON.parse(window.localStorage.getItem(this.key)));
                }
            };
            return PersistableConfig;
        }(codex.util.Config));
        phpdoc.PersistableConfig = PersistableConfig;
        var PhpdocApp = (function () {
            function PhpdocApp() {
                var _this = this;
                this.template = "\n        <phpdoc :project=\"project\" :ref=\"ref\" :full-name=\"fullName\" ></phpdoc>\n        ";
                this.project = 'codex';
                this.ref = 'master';
                this.fullName = 'Codex\\Codex';
                var VM = Vue.extend({
                    components: [],
                    data: function () {
                        return {
                            title: 'Api Documentation',
                            project: _this.project,
                            ref: _this.ref,
                            fullName: _this.fullName,
                        };
                    },
                    ready: function () {
                    }
                });
                this.vm = new VM;
                $('article.content').append(this.template);
                this.vm.$mount('article.content');
            }
            return PhpdocApp;
        }());
        phpdoc.PhpdocApp = PhpdocApp;
        var Phpdoc = (function (_super) {
            __extends(Phpdoc, _super);
            function Phpdoc() {
                _super.apply(this, arguments);
                this.settings = {};
                this.entities = [];
                this.file = {};
                this.tree = [];
            }
            Phpdoc.prototype.ready = function () {
                var _this = this;
                this.$on('tree.select', function (event, obj) {
                    var fullName = obj.node.data.fullName;
                    var type = obj.node.type;
                    console.log('tree.select', fullName, type);
                });
                this.$on('settings.toggle', function () {
                    _this.$set('settings.show', _this.$get('settings.show') === false);
                });
                this.$watch('settings', function () {
                    console.log('watch settings', _this.$get('settings'));
                    localStorage.setItem('phpdoc', JSON.stringify(_this.$get('settings')));
                }, { deep: true });
            };
            Phpdoc.prototype.beforeCompile = function () {
                if (typeof localStorage.getItem('phpdoc') === 'string') {
                    this.settings = JSON.parse(localStorage.getItem('phpdoc'));
                }
                else {
                    this.settings = _.clone(Phpdoc.defaultSettings);
                }
                this.fetch();
            };
            Phpdoc.prototype.fetch = function () {
                var _this = this;
                var defer = codex.util.create();
                async.parallel([
                    function (cb) { return phpdoc.api.getTree(_this.project, _this.ref).then(function (res) {
                        _this.tree = res.data;
                        cb();
                    }); },
                    function (cb) { return phpdoc.api.getEntities(_this.project, _this.ref).then(function (res) {
                        _this.entities = res.data;
                        cb();
                    }); },
                    function (cb) { return phpdoc.api.getEntity(_this.project, _this.ref, _this.fullName, ['methods', 'properties', 'source']).then(function (res) {
                        _this.file = res.data;
                        cb();
                    }); },
                ], function () {
                    defer.resolve();
                });
                return defer.promise;
            };
            Phpdoc.template = "\n        <p-header title=\"Api Documentation\"></p-header>\n        <p-settings  :settings=\"settings\"></p-settings>\n        <div class=\"phpdoc\">\n            <p-tree :items=\"tree\" class=\"phpdoc-tree\"></p-tree>\n            <p-content :file=\"file\" :entities=\"entities\" :settings=\"settings\" class=\"phpdoc-content\"></p-content>\n        </div>\n        ";
            Phpdoc.defaultSettings = {
                show: false,
                showInheritedMethods: true
            };
            __decorate([
                codex.prop({ type: String })
            ], Phpdoc.prototype, "project", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Phpdoc.prototype, "ref", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Phpdoc.prototype, "fullName", void 0);
            __decorate([
                codex.lifecycleHook('ready')
            ], Phpdoc.prototype, "ready", null);
            __decorate([
                codex.lifecycleHook('beforeCompile')
            ], Phpdoc.prototype, "beforeCompile", null);
            Phpdoc = __decorate([
                codex.component('phpdoc')
            ], Phpdoc);
            return Phpdoc;
        }(codex.Component));
        phpdoc.Phpdoc = Phpdoc;
        var Settings = (function (_super) {
            __extends(Settings, _super);
            function Settings() {
                _super.apply(this, arguments);
            }
            Settings.prototype.ready = function () {
            };
            Settings.template = "\n        <div class=\"phpdoc-settings\" v-if=\"settings.show\">\n            <form class=\"form-horizontal\">\n                <div class=\"row\">\n                    <div class=\"col-md-4\">\n                        <h5>Methods</h5>\n                        <div class=\"checkbox\">\n                            <label> <input type=\"checkbox\" v-model=\"settings.showInheritedMethods\"> Show inherited </label>\n                        </div>\n                        <!--<div class=\"form-group\">-->\n                            <!--<label for=\"inputEmail3\" class=\"col-sm-2 control-label\">Email</label>-->\n                            <!--<div class=\"col-sm-10\">-->\n                                <!--<input type=\"email\" class=\"form-control\" id=\"inputEmail3\" placeholder=\"Email\">-->\n                            <!--</div>-->\n                        <!--</div>-->\n                    </div>\n                    <div class=\"col-md-4\">\n                        <!--<div class=\"checkbox\">\n                            <label>\n                            <input type=\"checkbox\" value=\"\">\n                            Option one is this and that&mdash;be sure to include why it's great\n                            </label>\n                        </div>\n                        <div class=\"checkbox disabled\">\n                            <label>\n                            <input type=\"checkbox\" value=\"\" disabled>\n                            Option two is disabled\n                            </label>\n                        </div>\n\n                        <div class=\"radio\">\n                            <label>\n                            <input type=\"radio\" name=\"optionsRadios\" id=\"optionsRadios1\" value=\"option1\" checked>\n                            Option one is this and that&mdash;be sure to include why it's great\n                            </label>\n                        </div>\n                        <div class=\"radio\">\n                            <label>\n                            <input type=\"radio\" name=\"optionsRadios\" id=\"optionsRadios2\" value=\"option2\">\n                            Option two can be something else and selecting it will deselect option one\n                            </label>\n                        </div>\n                        <div class=\"radio disabled\">\n                            <label>\n                            <input type=\"radio\" name=\"optionsRadios\" id=\"optionsRadios3\" value=\"option3\" disabled>\n                            Option three is disabled\n                            </label>\n                        </div>-->\n                    </div>\n                    <div class=\"col-md-4\">\n                        <!--<div class=\"form-group\">\n                            <label for=\"inputEmail3\" class=\"col-sm-2 control-label\">Email</label>\n                            <div class=\"col-sm-10\">\n                                <select class=\"form-control\">\n                                  <option>1</option>\n                                  <option>2</option>\n                                  <option>3</option>\n                                  <option>4</option>\n                                  <option>5</option>\n                                </select>\n                            </div>\n                        </div>-->\n                    </div>\n                </div>\n            </form>\n        </div>\n        ";
            __decorate([
                codex.prop({ type: Object })
            ], Settings.prototype, "settings", void 0);
            __decorate([
                codex.lifecycleHook('ready')
            ], Settings.prototype, "ready", null);
            Settings = __decorate([
                codex.component('p-settings')
            ], Settings);
            return Settings;
        }(codex.Component));
        phpdoc.Settings = Settings;
        var Content = (function (_super) {
            __extends(Content, _super);
            function Content() {
                _super.apply(this, arguments);
                this.file = {};
            }
            Object.defineProperty(Content.prototype, "entity", {
                get: function () {
                    return this.file.entity;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(Content.prototype, "hasExtend", {
                get: function () {
                    return this.entity.extends.length > 0;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(Content.prototype, "hasDescription", {
                get: function () {
                    return this.entity.description.length > 0;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(Content.prototype, "methods", {
                get: function () {
                    this.$get('file.entity.methods');
                    var methods = _.find(this.$get('file.entity.methods'), 'interited', false);
                    console.log('methods', methods, 'entity', this.entity, this.$get('file.entity.methods'));
                    return methods;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(Content.prototype, "inheritedMethods", {
                get: function () {
                    return _.find(this.entity.methods, 'interited', true);
                },
                enumerable: true,
                configurable: true
            });
            Content.prototype.beforeCompile = function () {
                console.log('phpdoc content beforeCompile', this);
            };
            Content.prototype.ready = function () {
                this.$watch('file', function () {
                    if (codex.util.defined(window['Prism'])) {
                        window['Prism'].highlightAll();
                        $('.line-numbers-rows span').wrap($('a').attr({
                            href: '#'
                        }));
                    }
                });
            };
            Content.template = "\n<div class=\"phpdoc-content\">\n    <header>\n        <i class=\"phpdoc-type-{{ file.type }}\"></i>\n        <h3 class=\"fs22\">\n            <span class=\"phpdoc-type-{{ file.type }}\">{{ entity.full_name | removeStartSlash}}</span>\n            <small v-if=\"hasExtend\" class=\"pl-xs fs-13\">extends</small>\n            <p-type v-if=\"hasExtend\" :type=\"entity.extends\" class=\"fs-13\"></p-type>\n            <!--<a class=\"pl-md color-orange-800 fs-13\" >{{ file.entity.extends }}</a>-->\n        </h3>\n    </header>\n\n    <!-- DESC-->\n    <div class=\"phpdoc-content-description\">\n        <p v-if=\"hasDescription\" class=\"fs-13\">{{ entity.description }}</p>\n        <table v-if=\"entity.tags.length > 0\" class=\"table table-hover table-bordered table-phpdoc-tags\">\n            <tbody>\n                <tr v-for=\"tag in entity.tags\">\n                    <th width=\"150\">{{ tag.name }}</th>\n                    <td>{{ tag.description }}</td>\n                </tr>\n            </tbody>\n        </table>\n\n    </div>\n\n    <!-- TABS: METHODS, PROPERTIES, SOURCE-->\n    <div class=\"tabbable\" id=\"phpdoc-tabs\">\n        <ul role=\"tablist\" class=\"nav nav-tabs\">\n            <li role=\"presentation\" class=\"active\"><a href=\"#phpdoc-methods\" aria-controls=\"phpdoc-methods\" role=\"tab\" data-toggle=\"tab\">Methods</a></li>\n            <li role=\"presentation\"><a href=\"#phpdoc-properties\" aria-controls=\"phpdoc-properties\" role=\"tab\" data-toggle=\"tab\">Properties</a></li>\n            <li role=\"presentation\"><a href=\"#phpdoc-source\" aria-controls=\"phpdoc-source\" role=\"tab\" data-toggle=\"tab\">Source</a></li>\n        </ul>\n        <div class=\"tab-content\">\n            <!--METHODS-->\n            <div id=\"phpdoc-methods\" role=\"tabpanel\" class=\"tab-pane active\">\n            <div class=\"tabbable tabs-left\">\n                <ul role=\"tablist\" class=\"nav nav-tabs\">\n\n                    <li v-for=\"method in entity.methods | filterMethods 'inherited' false\" role=\"presentation\">\n                         <a :href=\"'http://asdf.com' | testHref\" aria-controls=\"method-{{ method.name }}\" role=\"tab\" data-toggle=\"tab\">\n                            <i class=\"pr-xs phpdoc-visibility-{{ method.visibility }}\"></i>\n                            {{ method.name }}\n                        </a>\n                    </li>\n\n                    <li v-if=\"settings.showInheritedMethods\" role=\"presentation\" class=\"seperator\">\n                        <span>Inherited</span>\n                    </li>\n\n                    <li v-if=\"settings.showInheritedMethods\" v-for=\"method in entity.methods | filterMethods 'inherited' true\" role=\"presentation\">\n                      <a href=\"#method-{{ method.name }}\" aria-controls=\"method-{{ method.name['name'] }}\" role=\"tab\" data-toggle=\"tab\">\n                            <i class=\"pr-xs phpdoc-visibility-{{ method.visibility }}\"></i>\n                            {{ method.name }}\n                            <i class=\"phpdoc-inherited-method-icon\" rel=\"tooltip\" title=\"Inherited from: <br> {{ method.class_name }}\"></i>\n                        </a>\n                    </li>\n                </ul>\n                <div class=\"tab-content\">\n                    <div v-for=\"method in entity.methods\" id=\"method-{{ method.name }}\" role=\"tabpanel\" v-bind:class=\"['tab-pane', $index === 0 ? 'active' : '']\">\n                        <div class=\"tab-pane-header\">\n                            <p-method-signature :entities=\"entities\" :entity=\"file.entity\" :name=\"method.name\"></p-method-signature>\n                        </div>\n\n                        <h4>Description</h4>\n                        <a href=\"/api/va\">avaa</a>\n                        <div class=\"block\">\n                            <p>{{ method.description }}</p>\n                            <p v-if=\"method['long-description'].length > 0\">{{ method['long-description'] }}</p>\n                        </div>\n\n                        <h4>Arguments</h4>\n                        <div class=\"block\">\n                            <div v-for=\"argument in method.arguments\" class=\"argument\">\n                                <span v-for=\"type in argument.types\">\n                                    <span v-if=\"$index > 0\">|</span>\n                                    <p-type :type=\"type\"></p-type>\n                                </span>\n                                <span class=\"color-cyan-900\">&nbsp;{{ argument.name }}</span>\n                                <span v-if=\"argument.default.length > 0\">={{ argument.default }}</span>\n                                <div v-if=\"argument.description.length > 0\" class=\"block\">{{ argument.description }}</div>\n                            </div>\n                                <!--@if(isset($argument['description']) && strlen($argument['description']) > 0)-->\n                                    <!--<div class=\"block\">{!! $argument['description'] !!}</div>-->\n\n                        </div>\n\n                    </div>\n\n                </div>\n            </div>\n            </div>\n\n            <!--PROPERTIES-->\n            <div id=\"phpdoc-properties\" role=\"tabpanel\" class=\"tab-pane\">\n            asdf\n            </div>\n\n            <!--SOURCE-->\n            <div id=\"phpdoc-source\" role=\"tabpanel\" class=\"tab-pane\">\n                <pre class=\"language-php line-numbers\"><code class=\"language-php\">{{ file.source }}</code></pre>\n            </div>\n        </div>\n    </div>\n\n</div>\n        ";
            __decorate([
                codex.prop({ type: Object })
            ], Content.prototype, "entities", void 0);
            __decorate([
                codex.prop({ type: Object })
            ], Content.prototype, "file", void 0);
            __decorate([
                codex.prop({ type: Object })
            ], Content.prototype, "settings", void 0);
            __decorate([
                codex.lifecycleHook('beforeCompile')
            ], Content.prototype, "beforeCompile", null);
            __decorate([
                codex.lifecycleHook('ready')
            ], Content.prototype, "ready", null);
            Content = __decorate([
                codex.component('p-content')
            ], Content);
            return Content;
        }(codex.Component));
        phpdoc.Content = Content;
        var Type = (function (_super) {
            __extends(Type, _super);
            function Type() {
                _super.apply(this, arguments);
                this.type = '';
                this.isEntity = false;
                this.hasEntity = false;
            }
            Type.prototype.ready = function () {
                if (!codex.util.defined(this.type)) {
                    return;
                }
                this.isEntity = this.type[0] === '\\';
                if (this.isEntity) {
                    var found = _.chain(this.entities).find(['full_name', this.type]);
                    this.hasEntity = found > 0;
                }
            };
            Type.template = "\n        <span>\n<span v-if=\"! isEntity\" class=\"simple-type simple-type-string\">{{ type | removeStartSlash }}</span>\n<a v-if=\"isEntity\" class=\"type-link local\" href=\"#\" :title=\"type\" :data-phpdoc-popover=\"type\">{{ type | removeStartSlash }}</a>\n</span>\n        ";
            __decorate([
                codex.prop({ type: String, required: true })
            ], Type.prototype, "type", void 0);
            __decorate([
                codex.prop({ type: Object })
            ], Type.prototype, "entities", void 0);
            __decorate([
                codex.lifecycleHook('ready')
            ], Type.prototype, "ready", null);
            Type = __decorate([
                codex.component('p-type')
            ], Type);
            return Type;
        }(codex.Component));
        phpdoc.Type = Type;
        Vue.filter('testHref', function (val) {
            console.log('testHref', val);
            return 'asdfasdf';
        });
        Vue.filter('filterMethods', function (value, key, val) {
            if (!codex.util.defined(value)) {
                return;
            }
            if (codex.util.defined(value[0]['inherited'])) {
                var filter = {};
                filter[key] = val;
                value = _.filter(value, filter);
            }
            return value;
        });
        Vue.filter('removeStartSlash', function (value) {
            if (!codex.util.defined(value)) {
                return;
            }
            var matches = value.match(/^\\(.*)/);
            if (matches !== null && matches.length === 2) {
                return matches[1];
            }
            return value;
        });
        var Tree = (function (_super) {
            __extends(Tree, _super);
            function Tree() {
                _super.apply(this, arguments);
                this.ignoreTreeSelect = false;
            }
            Tree.prototype.beforeCompile = function () {
                console.log('created phpdoc-tree', this);
            };
            Tree.prototype.ready = function () {
                var _this = this;
                this.$tree = $(this.$els.tree);
                this.$watch('items', function (newVal, oldVal) {
                    _this.makeTree(newVal);
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
                codex.prop({ ref: Object })
            ], Tree.prototype, "items", void 0);
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
        phpdoc.Tree = Tree;
        var Header = (function (_super) {
            __extends(Header, _super);
            function Header() {
                _super.apply(this, arguments);
            }
            Header.prototype.toggle = function () {
                this.$dispatch('settings.toggle');
                console.log('dispatched steetings.toglgle');
            };
            Header.template = "\n            <header>\n                <div class=\"phpdoc-settings-toggle\">\n                    <a href=\"#\" v-on:click=\"toggle\"><i class=\"fa fa-cog\"></i><span class=\"caret\"></span></a>\n                </div>\n                <small>{{ subtitle }} </small>\n                <h1>{{ title }}</h1>\n            </header>";
            __decorate([
                codex.prop({ type: String })
            ], Header.prototype, "title", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Header.prototype, "subtitle", void 0);
            Header = __decorate([
                codex.component('p-header')
            ], Header);
            return Header;
        }(codex.Component));
        phpdoc.Header = Header;
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
            Parameter = __decorate([
                codex.component('p-parameter')
            ], Parameter);
            return Parameter;
        }(codex.Component));
        phpdoc.Parameter = Parameter;
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
            Property = __decorate([
                codex.component('p-property')
            ], Property);
            return Property;
        }(codex.Component));
        phpdoc.Property = Property;
        var Method = (function (_super) {
            __extends(Method, _super);
            function Method() {
                _super.apply(this, arguments);
            }
            Method.template = "";
            __decorate([
                codex.prop({ type: Object })
            ], Method.prototype, "entity", void 0);
            __decorate([
                codex.prop({ type: String })
            ], Method.prototype, "name", void 0);
            Method = __decorate([
                codex.component('p-method')
            ], Method);
            return Method;
        }(codex.Component));
        phpdoc.Method = Method;
        var MethodSignature = (function (_super) {
            __extends(MethodSignature, _super);
            function MethodSignature() {
                _super.apply(this, arguments);
            }
            MethodSignature.prototype.ready = function () {
            };
            Object.defineProperty(MethodSignature.prototype, "method", {
                get: function () {
                    return _.find(this.entity.methods, 'name', this.name);
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(MethodSignature.prototype, "hasReturn", {
                get: function () {
                    return this.returns.length > 0;
                },
                enumerable: true,
                configurable: true
            });
            Object.defineProperty(MethodSignature.prototype, "returns", {
                get: function () {
                    return _.find(this.method.tags, 'name', 'return');
                },
                enumerable: true,
                configurable: true
            });
            MethodSignature.template = "\n\n        <span :class=\"['phpdoc-visibility-' + method.visibility, class ? class : '' ]\">{{ method.visibility }}</span>\n        {{ method.name }}<strong>(</strong>\n        <span v-for=\"argument in method.arguments\">\n            <strong v-if=\"$index !== 0\">,&nbsp;</strong>\n            {{ argument.name }}\n        </span>\n        <strong>)</strong>\n        <p-type v-if=\"hasReturn\" :entities=\"entities\" :type=\"returns.type\"></p-type>\n        ";
            __decorate([
                codex.prop({ type: Object })
            ], MethodSignature.prototype, "entities", void 0);
            __decorate([
                codex.prop({ type: Object })
            ], MethodSignature.prototype, "entity", void 0);
            __decorate([
                codex.prop({ type: String })
            ], MethodSignature.prototype, "name", void 0);
            __decorate([
                codex.lifecycleHook('ready')
            ], MethodSignature.prototype, "ready", null);
            MethodSignature = __decorate([
                codex.component('p-method-signature')
            ], MethodSignature);
            return MethodSignature;
        }(codex.Component));
        phpdoc.MethodSignature = MethodSignature;
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
        function init(selector, options) {
            if (options === void 0) { options = {}; }
            var vm = window['vm'] = new phpdoc.PhpdocApp;
        }
        phpdoc.init = init;
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
//# sourceMappingURL=phpdoc.js.map