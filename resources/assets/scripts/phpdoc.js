(function(){

    var app = packadic.Application.instance;

    var util = packadic.util;

    var trim         = util.trim,
        def          = packadic.def,
        defined      = packadic.defined,
        cre          = packadic.cre,
        ucfirst      = util.ucfirst,
        kindOf       = packadic.kindOf,
        makeString   = util.makeString,
        ConfigObject = packadic.ConfigObject;

    function PhpdocMenuTree(phpdoc, $el) {
        this.phpdoc = phpdoc;
        this.$el = $el;

        var self = this;
        ['class', 'interface', 'trait'].forEach(function (type) {
            self.options.types[type] = {icon: phpdoc.getTypeIcon(type)};
        });


    }

    PhpdocMenuTree.prototype = {
        phpdoc : {},
        $el    : {},
        data   : {},
        options: {
            'plugins': ['types', 'search'],
            'core'   : {
                'themes': {
                    'responsive': false,
                    'name'      : 'default'
                }
            },
            'types'  : {
                'default': {'icon': 'fa fa-file'},
                'folder' : {'icon': 'fa fa-folder color-blue-grey-500'}
            }
        },

        getJsTree: function () {
            return this.$el.jstree();
        },

        generate: function () {
            $root = cre('ul');
            this.data = this.phpdoc.getData();
            this.traverse(this.data.tree, $root, 0);
            return this.$el.append($root).jstree(this.options);
        },

        traverse: function (items, $el, level) {
            for (k in items) {
                var item = items[k];
                if ( isNaN(parseInt(k)) ) { // this is a string, with children
                    $nel = cre('ul');
                    $nli = cre('li').text(k).append($nel);
                    $nli.addClass('fs-12');
                    if ( level == 0 ) {
                        $nli.attr('data-jstree', '{ "opened" : true, "type" : "folder" }');
                    } else {
                        $nli.attr('data-jstree', '{ "type" : "folder" }');
                    }

                    var namePath = "\\" + k;
                    if ( typeof $el.closest('li').attr('data-full-name') !== "undefined" ) {
                        namePath = $el.closest('li').attr('data-full-name') + namePath;
                    }
                    $nli.attr('data-full-name', namePath);

                    $el.prepend($nli);
                    this.traverse(item, $nel, level ++, namePath);
                } else {
                    $el.append(
                        cre('li')
                            .text(item['name'])
                            .attr('data-jstree', '{ "type": "' + item['type'] + '" }')
                            .attr('data-full-name', item['full_name'])
                    );
                }
            }
        },

        search: function (fullName) {
            var items = this.getJsTree().get_json(null, {flat: true});
            var found = false;
            items.forEach(function (item) {
                if ( typeof item.data.fullName !== "undefined" && item.data.fullName == fullName ) {
                    found = item;
                    return false;
                }
            });
            return found;
        },

        openTo: function(fullName){
            var node = this.search(fullName);
            if(node !== false){
                this.getJsTree().close_all();
                this.getJsTree()._open_to(node);
            }
        }
    };



    function Phpdoc() {
        console.log('phpdoc init', this);
    }

    Phpdoc.prototype = {
        template: $.noop,
        loader: {},
        data    : {},
        $content: null,
        _$tree  : null,
        tree    : null,
        util    : util,

        start: function (callback) {
            var that = this;
            // Instanciate the tree
            this.tree = new PhpdocMenuTree(this, this._$tree);
            // make the nav tree
            that.tree.generate().bind('select_node.jstree', this, function (event, data) {
                if ( typeof data.node.data.fullName !== 'string' ) {
                    return
                }
                that.openDocPage(data.node.data.fullName);
                window.history.pushState(null, data.node.data.fullName, window.location.pathname + "#!/" + data.node.data.fullName);
            });
            // select random class to start with
            var fullName = app.config.get('codex.project.phpdoc_hook_settings.default_class') || that.data.data[0].full_name;
            // check if the current location contains a hash, which means we want to open a specific doc page
            if ( location.hash.indexOf('#!/') !== - 1 ) {
                fullName = location.hash.replace(/\#\!\//, '');
            }
            // Open the doc page
            that.openDocPage(fullName, true);
            // do the magic with window.history
            window.history.replaceState(null, fullName, window.location.pathname + "#!/" + fullName);
            window.addEventListener("popstate", function (event) {
                console.log('popstate', window.location);
                if ( location.hash.indexOf('#!/') !== - 1 ) {
                    that.openDocPage(location.hash.replace(/\#\!\//, ''));
                }
            }, false);
            if(kindOf(callback) === 'function'){
                callback(this);
            }
        },

        openDocPage: function (fullName, openInMenu) {
            var that = this;
            var classData = this.getClass(fullName);
            console.log('openDocPage', classData);

            this.$content.find('.type-link').tooltip('hide');

            // generate content from template
            this.$content.html(this.template(_.merge(classData, {
                iconClass: this.getTypeIcon(classData.type),
                extend   : classData.extends
            })));

            // Bind stuff
            this.$content.find('.type-link').tooltip({viewport: 'body'});
            this.$content.find('.type-link.local').on('click', function (event) {
                var $this = $(this);
                var fullName = $this.data('full-name');
                that.openDocPage(fullName, true);
                window.history.pushState(null, fullName, window.location.pathname + "#!/" + fullName);
            });

            // sroll to top
            app.layout.scrollTop();

            // open menu item
            if(defined(openInMenu) && openInMenu === true){
                this.getTree().openTo(fullName);
            }
        },

        getClass: function (fqn) {
            var that = this;
            var classData = _.find(this.data.data, {full_name: fqn});
            if ( ! defined(classData) ) {
                return undefined;
            }
            var parseMethods = function (methods) {
                parsed = [];
                _.each(methods, function (method, i) {
                    parsed.push(_.merge(method, {
                        abstract: defined(method['abstract']) && method['abstract'] === "true",
                        final   : defined(method['final']) && method['final'] === "true",
                        static  : defined(method['static']) && method['static'] === "true"
                    }));
                });
                return parsed;
            };

            var parseProperties = function (properties) {
                parsed = [];
                _.each(properties, function (property, i) {
                    parsed.push(_.merge(property, {
                        final : defined(property['final']) && property['final'] === "true",
                        static: defined(property['static']) && property['static'] === "true"
                    }));
                });
                return parsed;
            };

            return _.merge(classData, {
                showDoc    : function () {
                    return that.openDocPage(fqn);
                },
                link       : that.getClassLink(fqn),
                typeIcon   : that.getTypeIcon(classData.type),
                abstract   : defined(classData['abstract']) && classData['abstract'] === "true",
                final      : defined(classData['final']) && classData['final'] === "true",
                methods    : parseMethods(classData.methods),
                properties : parseMethods(classData.properties),
                description: makeString(classData['description']) + makeString(classData['long-description'])
            });
        },

        getClassLink: function (fqn) {
            return window.location.pathname + "#!/" + fqn;
        },

        makeClassLink: function (types) {
            var that = this;
            var els = [];

            types.toString().split('|').forEach(function (type) {
                var isAdvancedtype = type.indexOf('\\') !== - 1;

                if ( ! isAdvancedtype ) {
                    els.push(cre('span').text(type).addClass('simple-type').get(0).outerHTML);
                } else {
                    var typeClass = that.getClass(type);
                    var $a = cre('a')
                        .text(type.split('\\').reverse()[0])
                        .addClass('type-link')
                        .attr('data-full-name', type)
                        .attr('title', type);

                    if ( defined(typeClass) ) {
                        $a.addClass('local');
                    }

                    els.push($a.get(0).outerHTML);
                }
            }.bind(this));

            return els.join(' | ');
        },

        /**
         * @return PhpdocMenuTree
         */
        getTree: function () {
            return this.tree;
        },

        getTypeIcon: function (type) {
            var icon = '';
            switch (type) {
                case 'class':
                    icon = 'fa fa-file-code-o color-green-500';
                    break;
                case 'interface':
                    icon = 'fa fa-code color-purple-800';
                    break;
                case 'trait':
                    icon = 'fa fa-terminal color-blue-500';
                    break;
            }
            return icon;
        },

        setTemplate: function (templateSelector) {
            this.template = _.template($(templateSelector).get(0).innerHTML);
            return this;
        },

        setContentSelector: function (contentSelector) {
            this.$content = $(contentSelector);
            return this;
        },

        setTreeSelector: function (menuSelector) {
            this._$tree = $(menuSelector);
            return this;
        },

        setData: function (data) {
            this.data = data;
            return this;
        },
        getData: function () {
            return this.data;
        }
    };

    window.Phpdoc = Phpdoc;
}.call());
