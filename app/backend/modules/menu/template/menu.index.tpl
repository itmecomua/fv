<link href="{$fvConfig->get('path.css')}backend_menu_tree.css" type="text/css" rel="stylesheet">
<h1>{$fvConfig->getModuleName($module)}</h1>
<div class="form">
<div class="operation">
    <a href="{$fvConfig->get('dir_web_root')}{$module}/edit" onclick="go('{$fvConfig->get('dir_web_root')}{$module}/edit'); return false;" class="add">добавить</a>
</div>
<div style="clear: both;"></div> 
    <div id="multi-derevo" class="_horizontal" style="float: left;">
    {assign var=typeMenuId value=$manager->getConst('TYPE_HORIZONTAL')}
    <h4>{$manager->getTypeMenu($typeMenuId)} меню</h4>
    <form action="{$fvConfig->get('dir_web_root')}{$module}/savewt" >
        {include file="menu.index.list.h.tpl"}   
        <div style="clear: both;">&nbsp;</div>
        <div class="buttonpanel" style="display: none;">
            <input type="submit" name="save" value="Сохранить вес" class="button _horizontal" style="border-radius: 4px;">        
        </div>     
        <input type="hidden" name="type" value="{$typeMenuId}">
    </form>
    </div>
    
    <div id="multi-derevo" class="_vertical" style="float: right;">
    {assign var=typeMenuId value=$manager->getConst('TYPE_VERTICAL')}
    <h4>{$manager->getTypeMenu($typeMenuId)} меню</h4>
    <form action="{$fvConfig->get('dir_web_root')}{$module}/savewt" >
        {include file="menu.index.list.v.tpl"}   
        <div style="clear: both;">&nbsp;</div>
        <div class="buttonpanel" style="display: none;" >
            <input type="submit" name="save" value="Сохранить вес" class="button _vertical" style="border-radius: 4px;">        
        </div>     
        <input type="hidden" name="type" value="{$typeMenuId}">
    </form>
    </div>
<div style="clear: both;"></div>
<div class="operation">
    <a href="{$fvConfig->get('dir_web_root')}{$module}/edit" onclick="go('{$fvConfig->get('dir_web_root')}{$module}/edit'); return false;" class="add">добавить</a>
</div>
<div style="clear: both;"></div>
</div>

<script type="text/javascript">
{literal}
jQuery(function($){
        $("#multi-derevo._horizontal").find("ul").each(function(){
           $(this).sortable({
               stop: function(event, ui){
                    elementReposition(ui.item);
                    $(".button._horizontal").parent("div").fadeIn();
               },
           });
           $(this).disableSelection();  
        });        
        
        $("#multi-derevo._vertical").find("ul").each(function(){
           $(this).sortable({
               stop: function(event, ui){
                    elementReposition(ui.item);
                    $(".button._vertical").parent("div").fadeIn();
               },
           });
           $(this).disableSelection();  
        });
});

elementReposition = function(item)
{
    var ul = item.parent("ul");
    ul.children("li").each(function(index){
        var weight = index + 1;
        jQuery(this).children("span").children(".item").children("input").val(weight);
    });
}

{/literal}
</script>
  
  