<div id="page_path">
    {html_options name=_partName id=_partName class="flat" options=$_pageParts selected=$_currentPart onchange="window.getNodeContent()"}<br />
    <span class="delim">//</span>{foreach item=pathItem from=$_nodePath}{if $pathItem.current}<b>{$pathItem.name}</b><span 
            class="delim">/</span>{else}<a 
            href="javascript:void(0);" onclick="window.getNodeContent('{$pathItem.id}')">{$pathItem.name}</a><span class="delim">/</span>{/if}{/foreach}
</div>
<div id="page_zone" class="drop_zone{if $_currentNode->nodeName ne 'horisontal_layoult'} vertical{else} horizontal{/if}">
{foreach item=oneNode from=$_currentNodes}
    <div class="drop_zone {if $oneNode->nodeName ne 'horisontal_layoult'} vertical{else} horizontal{/if}"
        id="item_{$oneNode->getAttribute('order')|intval}">
        {$oneNode->getAttribute('name')}
        {if $oneNode->nodeName eq 'horisontal_layoult'}(горизонтальное размещение){else}
        {if $oneNode->nodeName eq 'vertical_layoult'}(вертикальное размещение){else}
        {if $oneNode->nodeName eq 'module'}(модуль){else}
        {if $oneNode->nodeName eq 'div'}(блок "div"){else} 
        {if $oneNode->nodeName eq 'current_module'}(текущий модуль){else}{/if}{/if}{/if}{/if}{/if}
        <BR>
        <div class="operation">
        {if strpos($oneNode->nodeName, 'layoult') ne false}
            <a href="javascript:void(0);" onclick="window.getNodeContent('{$oneNode->getAttribute("id")}')" class="page_go">содержимое узла</a>
        {/if}
        <a href="javascript:void(0);" onclick="window.editNode('{$oneNode->getAttribute("id")}', '{$oneNode->nodeName}')" class="page_edit">редактировать узел</a>
        <a href="javascript:void(0);" onclick="window.deleteNode('{$oneNode->getAttribute("id")}')" class="page_delete">удалить узел</a>
        </div>
    </div>
{/foreach}
</div>

{literal}
<SCRIPT>    
    if (!window.paramWindow) 
    {
        
        var p_wnd = new PopUpWindow({
            width: 400,
            height: 400,
            center: true,
            url: '/backend/',
            title: "параметры",
            name: 'params_edit',
            zIndex: 501,
            onShow: function (params) 
            {
                new Ajax.Updater('params_edit_content', "{/literal}{$fvConfig->get('dir_web_root')}pages/getmoduleparams/{literal}", {
                    parameters: Object.extend(params, {_xmlContent: $F('_xmlContent')}),
                    evalScripts: true
                });
            },
            onOk: function (params) {
                new Ajax.Updater("content_zone", "{/literal}{$fvConfig->get('dir_web_root')}pages/addpagenode/{literal}", {
                    parameters: Object.extend($('paramsForm').serialize(true), {
                        _xmlContent: $F('_xmlContent'),
                        _nodeId: params._nodeId || $F('_nodeId'),
                        _add: params._add,
                        _partName: ($('_partName'))?$F('_partName'):null
                    }),
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
    
        Object.extend(window, {
            paramWindow: p_wnd 
        })
    }

    Droppables.add("page_zone", {
        accept: 'drag_item',
        onDrop: function (element) {
            try {
            window.paramWindow.show({
                _type: element.id,
                _nodeId: $F("_nodeId"),
                _add: 1
            });
            } catch (e) {
            }
        }
    });
    
    Object.extend(window, {
        editNode: function(_nodeId, _type) {
            window.paramWindow.show({
                _type: _type,
                _nodeId: _nodeId,
                _add: 0
            });
        },
        deleteNode: function (_nodeId) {
            if (confirm('Удалить этот узел')) {
		        new Ajax.Updater("content_zone", "{/literal}{$fvConfig->get('dir_web_root')}pages/deletepagenode/{literal}", {
		            parameters: {
		                _xmlContent: $F('_xmlContent'),
		                _nodeId: _nodeId
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
        }
    })
    
    Sortable.create('page_zone', {
        tag: "div",
        onUpdate: function () {
            new Ajax.Request("{/literal}{$fvConfig->get('dir_web_root')}pages/reorder/{literal}", {
                parameters: {
                    _nodeOrder: Sortable.sequence('page_zone').join(","),
                    _xmlContent: $F('_xmlContent'),
                    _nodeId: $F('_nodeId')
                },
                onComplete: function (transport, json) {
                    for (var i = 0; i < $("page_zone").childNodes.length; i++) {
                        $("page_zone").childNodes[i].id = 'item_' + i;
                    }
                    if (json && json._xmlContent) {
                        $("_xmlContent").value = json._xmlContent;
                    }
                }
            });
        }
    });    
</SCRIPT>
{/literal}