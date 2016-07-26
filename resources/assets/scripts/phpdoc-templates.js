var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates = {};
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['content'] = '<div class="phpdoc-content" xmlns:v-on="http://www.w3.org/1999/xhtml">    <header>        <i class="phpdoc-type-{{ file.type }}"></i>        <h3 class="fs22">            <span class="phpdoc-type-{{ file.type }}">{{ entity.full_name | removeStartSlash}}</span>            <small v-if="hasExtend" class="pl-xs fs-13">extends</small>            <p-type v-if="hasExtend" :type="entity.extends" class="fs-13"></p-type>        </h3>    </header>    <!-- DESC-->    <div class="phpdoc-content-description">        <p v-if="hasDescription" class="fs-13">{{ entity.description }}</p>        <table v-if="entity.tags.length > 0" class="table table-hover table-bordered table-tags">            <tbody>            <tr v-for="tag in entity.tags">                <th width="150">{{ tag.name }}</th>                <td>{{ tag.description }}</td>            </tr>            </tbody>        </table>    </div>    <!-- TABS: METHODS, PROPERTIES, SOURCE-->    <div class="tabbable phpdoc-content-tabs" id="phpdoc-tabs">        <ul role="tablist" class="nav nav-tabs">            <li role="presentation" :class="{ \'active\' : isActive(\'methods\') }"><a href="#" v-on:click.prevent="setActive(\'methods\', $event)" aria-controls="phpdoc-methods" role="tab" >Methods</a></li>            <li role="presentation" :class="{ \'active\' : isActive(\'properties\') }"><a href="#" v-on:click.prevent="setActive(\'properties\', $event)" aria-controls="phpdoc-properties" role="tab" >Properties</a></li>            <li role="presentation" :class="{ \'active\' : isActive(\'source\') }"><a href="#" v-on:click.prevent="setActive(\'source\', $event)" aria-controls="phpdoc-source" role="tab" >Source</a></li>        </ul>        <div class="tab-content">            <!--METHODS-->            <div role="tabpanel" :class="[\'tab-pane\', { \'active\' : isActive(\'methods\') }]">                <div class="tabbable tabs-left phpdoc-content-method-tabs">                    <ul role="tablist" class="nav nav-tabs">                        <li v-for="method in entity.methods | filterMethods \'inherited\' false | orderBy \'name\'" role="presentation">                            <a v-on:click.prevent="setMethod(method)" role="tab" href="#">                                <i class="pr-xs phpdoc-visibility-{{ method.visibility }}"></i>                                {{ method.name }}                            </a>                        </li>                        <li v-if="settings.showInheritedMethods" role="presentation" class="seperator">                            <span>Inherited</span>                        </li>                        <li v-if="settings.showInheritedMethods" v-for="method in entity.methods | filterMethods \'inherited\' true  | orderBy \'name\'" role="presentation">                            <a v-on:click.prevent="setMethod(method)" role="tab" href="#">                                <i class="pr-xs phpdoc-visibility-{{ method.visibility }}"></i>                                {{ method.name }}                                <i class="phpdoc-inherited-method-icon" rel="tooltip" title="Inherited from: <br> {{ method.class_name }}"></i>                            </a>                        </li>                    </ul>                    <div class="tab-content">                        <div role="tabpanel" class="tab-pane active">                        <!--<div v-for="method in entity.methods" id="method-{{ method.name }}" role="tabpanel" :class="[\'tab-pane\', $index === 0 ? \'active\' : \'\']">-->                            <!--                            <div class="tab-pane-header">                                <p-method-signature :entities="entities" :entity="file.entity" :name="method.name"></p-method-signature>                            </div>                            <div class="tab-pane-content">                                <h4 v-if="method.description.length > 0">Description</h4>                                <div v-if="method.description.length > 0" class="block">                                    <p>{{ method.description }}</p>                                    <p v-if="method[\'long-description\'].length > 0">{{ method[\'long-description\'] }}</p>                                </div>                                <h4 v-if="method.arguments.length > 0">Arguments</h4>                                <div class="block" v-if="method.arguments.length > 0">                                    <div v-for="argument in method.arguments" >                                        <div class="argument">                                        <span v-for="type in argument.types">                                            <span v-if="$index > 0">|</span>                                            <p-type :type="type" :fqn="false"></p-type>                                        </span>                                            <span class="color-cyan-900">&nbsp;{{ argument.name }}</span>                                            <span v-if="argument.default.length > 0"> = {{ argument.default }}</span>                                        </div>                                        <div v-if="argument.description.length > 0" class="block">                                            {{ argument.description }}                                            <div v-if="argument[\'long-description\'].length > 0">                                                {{ argument[\'long-description\'] }}                                            </div>                                        </div>                                    </div>                                </div>                                <h4>Returns</h4>                                <div class="block">                                    <p-type :type="method.returns" :fqn="true"></p-type>                                </div>                            </div>                        </div>                        -->                        <p-method :method="method"></p-method>                    </div>                </div>            </div>            </div>            <!--PROPERTIES-->            <div role="tabpanel" :class="[\'tab-pane\', { \'active\' : isActive(\'properties\') }]">                asdf            </div>            <!--SOURCE-->            <div role="tabpanel" :class="[\'tab-pane\', { \'active\' : isActive(\'source\') }]">                <pre class="language-php line-numbers"><code class="language-php">{{ file.source }}</code></pre>            </div>        </div>    </div></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['header'] = '<header>    <div class="phpdoc-settings-toggle">        <a href="#" v-on:click="toggle($event)"><i class="fa fa-cog"></i><span class="caret"></span></a>    </div>    <small>{{ subtitle }} </small>    <h1>{{ title }}</h1></header>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['methodReturns'] = '<div v-if="hasReturnValue">    <p-type :type="type" :entities="entities" :fqn="fqn"></p-type>    <p v-if="hasDescription">{{ description}}</p></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['methodSignature'] = '<div class="phpdoc-method-signature">    <span :class="[\'phpdoc-visibility-\' + method.visibility, class ? class : \'\' ]">{{ method.visibility }}</span>&nbsp;{{ method.name }}&nbsp;<strong>(</strong>    <span v-for="argument in method.arguments">        <strong v-if="$index !== 0">,</strong>        <span v-for="type in argument.types">            <span v-if="$index > 0">|</span>            <p-type :type="type" :fqn="false"></p-type>        </span>        <span class="color-cyan-900">{{ argument.name }}</span>    </span>    <strong>)</strong>    <p-type v-if="hasReturn" :entities="entities" :type="returns.type"></p-type></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['method'] = '<div class="phpdoc-method">    <div class="tab-pane-header">        <p-method-signature :method="method"></p-method-signature>    </div>    <div class="tab-pane-content">        <h4 v-if="method.description.length > 0">Description</h4>        <div v-if="method.description.length > 0" class="block">            <p>{{ method.description }}</p>            <p v-if="method[\'long-description\'].length > 0">{{ method[\'long-description\'] }}</p>        </div>        <h4 v-if="method.arguments.length > 0">Arguments</h4>        <div class="block" v-if="method.arguments.length > 0">            <div v-for="argument in method.arguments">                <div class="argument">                                        <span v-for="type in argument.types">                                            <span v-if="$index > 0">|</span>                                            <p-type :type="type" :fqn="false"></p-type>                                        </span>                    <span class="color-cyan-900">&nbsp;{{ argument.name }}</span>                    <span v-if="argument.default.length > 0"> = {{ argument.default }}</span>                </div>                <div v-if="argument.description.length > 0" class="block">                    {{ argument.description }}                    <div v-if="argument[\'long-description\'].length > 0">                        {{ argument[\'long-description\'] }}                    </div>                </div>            </div>        </div>        <h4>Returns</h4>        <div class="block">            <p-type :type="method.returns" :fqn="true"></p-type>        </div>    </div></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['phpdoc'] = '<p-header title="Api Documentation"></p-header><p-settings  :settings="settings"></p-settings><div class="phpdoc">    <p-tree :items="tree" class="phpdoc-tree"></p-tree>    <p-content :file="file" :entities="entities" :settings="settings" class="phpdoc-content"></p-content></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['settings'] = '<div class="phpdoc-settings" v-if="settings.show">    <form class="form-horizontal">        <div class="row">            <div class="col-md-4">                <h5>Methods</h5>                <div class="checkbox">                    <label> <input type="checkbox" v-model="settings.showInheritedMethods"> Show inherited </label>                </div>                <!--<div class="form-group">-->                <!--<label for="inputEmail3" class="col-sm-2 control-label">Email</label>-->                <!--<div class="col-sm-10">-->                <!--<input type="email" class="form-control" id="inputEmail3" placeholder="Email">-->                <!--</div>-->                <!--</div>-->            </div>            <div class="col-md-4">                <!--<div class="checkbox">                    <label>                    <input type="checkbox" value="">                    Option one is this and that&mdash;be sure to include why it\'s great                    </label>                </div>                <div class="checkbox disabled">                    <label>                    <input type="checkbox" value="" disabled>                    Option two is disabled                    </label>                </div>                <div class="radio">                    <label>                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>                    Option one is this and that&mdash;be sure to include why it\'s great                    </label>                </div>                <div class="radio">                    <label>                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">                    Option two can be something else and selecting it will deselect option one                    </label>                </div>                <div class="radio disabled">                    <label>                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>                    Option three is disabled                    </label>                </div>-->            </div>            <div class="col-md-4">                <!--<div class="form-group">                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>                    <div class="col-sm-10">                        <select class="form-control">                          <option>1</option>                          <option>2</option>                          <option>3</option>                          <option>4</option>                          <option>5</option>                        </select>                    </div>                </div>-->            </div>        </div>    </form></div>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
var codex;
(function (codex) {
    var phpdoc;
    (function (phpdoc) {
        phpdoc.templates['type'] = '<span><span v-if="! isEntity" class="simple-type simple-type-string">{{ formattedType }}</span><a v-if="isEntity" class="type-link local" href="#" rel="tooltip" :title="type | removeStartSlash" :data-phpdoc-popover="type">{{ formattedType }}</a></span>';
    })(phpdoc = codex.phpdoc || (codex.phpdoc = {}));
})(codex || (codex = {}));
//# sourceMappingURL=phpdoc-templates.js.map