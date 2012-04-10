<table id="page_conent" style="width: 100%;">
    <tr>
        <td id="left_zone" style="width:150px;">
           <div id="vertical_layoult" class="drag_item">вертикальное размещение</div>
           <div id="horisontal_layoult" class="drag_item">горизонтальное размещение</div>
           <div id="current_module" class="drag_item">текущий модуль</div> 
           <div id="new_module" class="drag_item">новый модуль</div>
           <div id="new_div" class="drag_item">новый 'div'</div>
        </td>
        <td id="content_zone" style="vertical-align: top;">
        </td>
    </tr>
</table>

<input type="hidden" name="_xmlContent" id="_xmlContent" value="{$XML_CONTENT|escape}" />
<input type="hidden" name="_nodeId" id="_nodeId" value="{$NODE_ID}" />

{literal}
<script>
    
    
    $$('td#left_zone div.drag_item').each(function (element) {
        new Draggable(element, {revert: true});
    });
    
    Object.extend(window, {
        getNodeContent: function (nodeId) {
            new Ajax.Updater("content_zone", "{/literal}{$fvConfig->get('dir_web_root')}pages/getpagecontent/{literal}", {
                parameters: {
                    _xmlContent: $F('_xmlContent'),
                    _nodeId: nodeId,
                    _partName: ($('_partName'))?$F('_partName'):null
                },
                evalScripts: true,
                onComplete: function (transport, json) {
                    if (json && json._nodeId) {
                        $("_nodeId").value = json._nodeId;
                    }
                    if (json && json._xmlContent) {
                        $('_xmlContent').value = json._xmlContent;
                    }
                }
            });
        }
    });
    
    getNodeContent($F("_nodeId"));
    
</script>
{/literal}