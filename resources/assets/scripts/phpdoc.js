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
        function template(name) {
            return codex.phpdoc['templates'][name];
        }
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
        var removeStartSlash = function (value) {
            if (!codex.util.defined(value)) {
                return;
            }
            var matches = value.match(/^\\(.*)/);
            if (matches !== null && matches.length === 2) {
                return matches[1];
            }
            return value;
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
                this.tree = {};
                this.fetch().then(function () {
                    var VM = Vue.extend({
                        components: [],
                        data: function () {
                            return {
                                title: 'Api Documentation',
                                project: _this.project,
                                ref: _this.ref,
                                fullName: _this.fullName,
                                tree: _this.tree,
                                file: _this.file,
                                entities: _this.entities
                            };
                        },
                        ready: function () {
                        }
                    });
                    _this.vm = new VM;
                    $('article.content').append(_this.template);
                    _this.vm.$mount('article.content');
                });
            }
            PhpdocApp.prototype.fetch = function () {
                var _this = this;
                if (typeof this.fetched !== 'undefined') {
                    return this.fetched;
                }
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
                return this.fetched = defer.promise;
            };
            return PhpdocApp;
        }());
        phpdoc.PhpdocApp = PhpdocApp;
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
        Vue.filter('removeStartSlash', removeStartSlash);
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
                console.log('root', this.$root, 'data', this.$root.$data);
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
                var _this = this;
                if (typeof localStorage.getItem('phpdoc') === 'string') {
                    this.settings = JSON.parse(localStorage.getItem('phpdoc'));
                }
                else {
                    this.settings = _.clone(Phpdoc.defaultSettings);
                }
                ['entities', 'file', 'tree'].forEach(function (name) {
                    _this[name] = _this.$root.$get(name);
                    console.log('phpdoc beforeCompile', name, _this[name]);
                });
            };
            Phpdoc.template = template('phpdoc');
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
        var Header = (function (_super) {
            __extends(Header, _super);
            function Header() {
                _super.apply(this, arguments);
            }
            Header.prototype.toggle = function ($event) {
                $event.preventDefault();
                this.$dispatch('settings.toggle');
                console.log('dispatched steetings.tlgle', $event, arguments, 'args');
            };
            Header.template = codex.phpdoc['templates']['header'];
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
        var Settings = (function (_super) {
            __extends(Settings, _super);
            function Settings() {
                _super.apply(this, arguments);
            }
            Settings.prototype.ready = function () {
            };
            Settings.template = template('settings');
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
                this.makeTree(this.$get('items'));
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
        var Content = (function (_super) {
            __extends(Content, _super);
            function Content() {
                _super.apply(this, arguments);
                this.file = {};
                this.active = 'methods';
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
            Content.prototype.setActive = function (name, $event) {
                this.$set('active', name);
                console.log('setActive', name, 'event', $event, 'p-content', this);
            };
            Content.prototype.isActive = function (name) {
                return this.$get('active') === name;
            };
            Content.prototype.setMethod = function (method) {
                if (typeof method === 'string') {
                    method = _.find(this.entity.methods, { name: name });
                }
                this.$set('method', method);
            };
            Content.prototype.beforeCompile = function () {
                console.log('phpdoc content beforeCompile', this);
                this.setMethod(this.entity.methods[0]);
            };
            Content.prototype.ready = function () {
                this.$watch('file', function () {
                    if (codex.util.defined(window['Prism'])) {
                        window['Prism'].highlightAll();
                        $('.line-numbers-rows span').wrap($('<a>').attr({
                            href: '#'
                        }));
                    }
                });
            };
            Content.template = template('content');
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
                this.fqn = true;
                this.isEntity = false;
                this.hasEntity = false;
            }
            Object.defineProperty(Type.prototype, "formattedType", {
                get: function () {
                    var type = removeStartSlash(this.$get('type'));
                    if (this.$get('fqn') === false) {
                        type = _.last(type.split('\\'));
                    }
                    return type;
                },
                enumerable: true,
                configurable: true
            });
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
            Type.template = template('type');
            __decorate([
                codex.prop({ type: String, required: true })
            ], Type.prototype, "type", void 0);
            __decorate([
                codex.prop({ type: Object })
            ], Type.prototype, "entities", void 0);
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
        phpdoc.Type = Type;
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
            Method.template = template('method');
            __decorate([
                codex.prop({ type: Object })
            ], Method.prototype, "method", void 0);
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
            Object.defineProperty(MethodSignature.prototype, "hasReturn", {
                get: function () {
                    return this.$get('method.returns').length > 0;
                },
                enumerable: true,
                configurable: true
            });
            MethodSignature.template = template('methodSignature');
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
        phpdoc.MethodSignature = MethodSignature;
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
                codex.component('p-argument')
            ], Parameter);
            return Parameter;
        }(codex.Component));
        phpdoc.Parameter = Parameter;
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