<form id="paramsForm">
<div class="form">
    <label for="name">название</label>{html_options name=name id=name options=$modulesList selected=$nodeName class=flat style="width: 200px;"}<br/>
    <label for="view">вид отображения</label>
    <div style="float: left; width: 210px;" id="view_div"></div><br/>
    <div id="params_div" style="clear: both;">

    </div>
</div>
<input type="hidden" name="_nodeName" id="_nodeName" value="module">
<input type="hidden" name="_nodeId" id="__nodeId" value="{$_nodeId}">
<input type="hidden" name="_xmlContent" id="__xmlContent" value="{$_xmlContent}">
<input type="hidden" name="_add" id="_add" value="{$_add}">
</form>
{literal}
<script>
    function changeName(e) {
        new Ajax.Updater('view_div', "{/literal}{$fvConfig->get('dir_web_root')}pages/getmoduleview/{literal}", {
            parameters: {
                _xmlContent: $F('__xmlContent'),
                _nodeId: $F('__nodeId'),
                module_name: $F('name')
            },
            onComplete: function (transport, json) {
                changeView();
                $('view').observe('change', changeView);
            }
        });
    };
    
    function changeView(e) {
        new Ajax.Updater('params_div', "{/literal}{$fvConfig->get('dir_web_root')}pages/getmoduleparam/{literal}", {
            parameters: {
                _xmlContent: $F('__xmlContent'),
                _nodeId: $F('__nodeId'),
                module_name: $F('name'), 
                module_view: $F('view')
            },
            onComplete: function (transport, json) {
                
            }
        });
    };

    $('name').observe('change', changeName);
    changeName();
</script>
{/literal}