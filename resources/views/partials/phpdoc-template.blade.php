<script type="text/html" id="phpdoc-content-template">
    <header class="phpdoc-file-header">
        <i class="fa <%= iconClass %>"></i>
        <h3>
            <% if ( typeof namespace == 'string' ) { %><span class="color-green-a700"><%= namespace %>\</span><% } %><span class=""><%= name %></span>
            <% if ( typeof extend == 'string' ) { %>
            <small class="pl-md">extends</small>
            <a href="<%= this.getClassLink(extend) %>" class="pl-md color-orange-800 fs-13"><%= extend %></a>
            <% } %>
        </h3>

    </header>
    <blockquote>
        <% if(description){ %>
        <p class="phpdoc-file-description fs-13">
            <%= description %>

        </p>
        <% } %>
    </blockquote>

    <% if(typeof properties == 'object' && properties.length > 0){ %>
    <h4 class="doc-heading">Properties</h4>
    <table class="table table-hover table-striped table-condensed table-light table-bordered">
        <thead>
        <tr>
            <th class="text-right pr-xs pl-xs" width="200px"><strong>Property</strong></th>
            <th class="text-center" width="130px"><strong>Attributes</strong></th>
            <th class="text-center" width="130px"><strong>Type</strong></th>
            <th><strong>Description</strong></th>
        </tr>
        </thead>
        <tbody>
        <% for(k in properties){ %>
        <tr>
            <td class="text-right color-teal-500 pr-xs pl-xs"><%= properties[k].name %></td>
            <td class="text-center">
                <% if(properties[k].static == true){ %>
                <span class="label label-xs label-info">static</span>
                <% } %>
                <%
                    var color = 'bg-color-green-500';
                    if(properties[k].visibility == 'protected') color = 'bg-color-amber-500';
                    if(properties[k].visibility == 'private') color = 'bg-color-deep-orange-800';
                %>
                <span class="label label-xs <%= color %>"><%= properties[k].visibility %></span>

            </td>
            <td>
                <p class="mb-n"><%= this.makeClassLink(properties[k].type || 'mixed') %></p>
            </td>
            <td>
                <small><%= properties[k].description + (typeof properties[k]['long-description'] == 'string' ? properties[k]['long-description'] : '') %></small>
            </td>
        </tr>
        <% } %>
        </tbody>
    </table>
    <% } %>


    <% if(typeof methods == 'object'){ %>
    <h4 class="doc-heading">Methods</h4>
    <% for(k in methods){
         var method = methods[k];
         var visibilityColor = 'color-green-800';
         if(method.visibility == 'protected') visibilityColor = 'color-deep-orange-800';
         if(method.visibility == 'private') visibilityColor = 'color-grey-700';
    %>
    <h5 class="method-heading">
        <small class="method-visibility <%= visibilityColor %>"><%= method.visibility %></small>
        <%= method.name %>
        <span class="fs-12">(
            <% if(method.parameters.length > 0) { %>
            <% for(p in method.paramedters) { var parameter = method.parameters[p]; %>
            <span class="param-name">
                        <% if (parameter.reference === true) { %><strong>&amp;</strong><% } %>
                <%= parameter.name %>
                    </span>
            <% if( typeof parameter.default === 'string' ) { %>
            <span class="param-default">= <%= parameter.default %></span>
            <% } %>
            <% if(p < method.parameters.length -1){ %>,<% } %>
            <% } %>

            <% } %>
            )
        </span>
        -&gt;
        <span class="method-return"><%= this.makeClassLink(method.returns || 'void') %></span>
    </h5>
    <div class="row method-row">
        <div class="col-md-6">
            <p class="method-full-name fs-10 color-indigo-400"><%= method.full_name %></p>
            <p><%= method.description %></p>
            <p><%= (typeof method['long-description'] == 'string' ? method['long-description'] : '') %></p>
        </div>
        <div class="col-md-6">
            <% if(method.parameters.length > 0){ %>
            <table class="table table-hover table-condensed table-light table-striped table-bordered table-small">
                <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <% for(p in method.parameters) { var parameter = method.parameters[p]; %>

                <tr>
                    <td><%= parameter.name %></td>
                    <td>
                        <%= this.makeClassLink(parameter.type || 'mixed') %>
                    </td>
                    <td>
                        <small><%= parameter.description %></small>
                    </td>
                </tr>
                <% } %>
                </tbody>
            </table>
            <% } %>
        </div>
    </div>
    <% } %>
    <% } %>
</script>
