<div id="toolbar" class="text">
{foreach item=ModuleNode from=$currentModuleTree key=nodeKey}

{if is_array($ModuleNode.child_nodes)}  
    <DIV id="{$nodeKey}" class="main_menu_group">
        {$ModuleNode.name}
    </DIV>
    
    <SCRIPT>
        $('{$nodeKey}').onclick = doPupUp.bindAsEventListener();
        $('{$nodeKey}').onmouseover = mouseOver.bindAsEventListener();
        $('{$nodeKey}').onmouseout = mouseOut.bindAsEventListener();
    </SCRIPT>
    
    <DIV id="{$nodeKey}_popup" style="position: absolute; left: 0; top: 0; display: none; border: 1px solid black; background-color: #FFFFFF; background-image: url('{$fvConfig->get('dir_web_root')}img/bg_menu.gif'); background-repeat: repeat-y; background-position: left">
        {foreach item=childNode from=$ModuleNode.child_nodes key=child_key}
        
        {if $childNode.image_name}
            <DIV id="{$nodeKey}_child_{$child_key}" class="main_menu_child_item" onclick="go('{$childNode.href}');" go_href="{$childNode.href}" onmouseover="this.style.backgroundColor = '#FFFFFF';" onmouseout="this.style.backgroundColor = '#ccccdd';">
                <IMG src="{$fvConfig->get('dir_web_root')}img/menu_icons/{$childNode.image_name}" width="16" height="16" border="0" align="absmiddle" style="float: left; margin: 0 7 0 -1;">
                {$childNode.name}
            </DIV>
        {else}
            <DIV id="{$nodeKey}_child_{$child_key}" class="main_menu_child_item_noimage" onclick="go('{$childNode.href}');" go_href="{$childNode.href}" onmouseover="this.style.backgroundColor = '#FFFFFF';" onmouseout="this.style.backgroundColor = '#ccccdd';">
                {$childNode.name}
            </DIV>
        {/if}
            
            <SCRIPT>
                var popup_child_{$child_key} = new PopupMenu();
                
                popup_child_{$child_key}.add('Открыть в новом окне', {literal}function(target){
                    window.open(target.getAttribute("go_href"));
                });
                {/literal}
                popup_child_{$child_key}.bind('{$nodeKey}_child_{$child_key}');

                $('{$nodeKey}_child_{$child_key}').onmouseover = mouseOver.bindAsEventListener();
                $('{$nodeKey}_child_{$child_key}').onmouseout = mouseOut.bindAsEventListener();
            </SCRIPT>
        {/foreach}
    </DIV>

    {else}
    <DIV id="{$nodeKey}" class="main_menu_item" onclick="go('{$ModuleNode.href}');">
        {$ModuleNode.name}
    </DIV>

    <SCRIPT>
        $('{$nodeKey}').onmouseover = mouseOver.bindAsEventListener();
        $('{$nodeKey}').onmouseout = mouseOut.bindAsEventListener();
    </SCRIPT>
    
    {/if}
{/foreach}
<div style="clear: both;">
</div>
</div>