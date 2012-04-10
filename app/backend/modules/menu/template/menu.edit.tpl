<h1>{if $ex->isNew()}Добавление{else}Редактирование{/if} пункта меню</h1>
<div class="operation">
    <a href="{$fvConfig->get('dir_web_root')}{$module}" onclick="go('{$fvConfig->get('dir_web_root')}{$module}'); return false;" class="left">вернутся к списку</a>    
</div>
<div style="clear: both">&nbsp;</div>
<form action="{$fvConfig->get('dir_web_root')}{$module}/save">
    <div class="form">
    <label>Имя</label>
    <input type="text" id="name_ru" value="{$ex->getName()}" class="notEmpty" maxlength="255" name="m[name]">
    <br />
    <label>Тип меню</label>
    <select name="m[type_id]" onchange="javascript:switchMenu(this);">
        {html_options options=$types selected=$ex->type_id}
    </select>             
    <br />
    <label>Родительский пункт</label>
    <select disabled="disabled" name="m[parent_id]" id="_menu-{$manager->getConst('TYPE_HORIZONTAL')}" class="menus" {if $ex->type_id == $manager->getConst('TYPE_VERTICAL')} style="display: none;" disabled="disabled" {/if}>
        <option value="">Нет</option>
        {html_options options=$menuTreeH selected=$ex->parent_id}
    </select>                                                  
    <select disabled="disabled" name="m[parent_id]" id="_menu-{$manager->getConst('TYPE_VERTICAL')}" class="menus" {if $ex->type_id != $manager->getConst('TYPE_VERTICAL')} style="display: none;" disabled="disabled" {/if}>
        <option value="">Нет</option>
        {html_options options=$menuTreeV selected=$ex->parent_id}
    </select>                                                  
    <br />
    <label>URL</label>
    <input type="text" name="m[url]" value="{$ex->getURL()}" id="url" />
    <a href='javascript:void(0);' onclick="javascript:window.doGenerateUrl('url');">
        <p style='font-size: 10px; color:#dddddd; margin-left: 210px; margin-bottom: 3px;'>сгенерировать URL по названию.</p>
    </a>
    <br />
    <label>Открывать в новом окне</label>
    <input type="checkbox" value="1" name="m[is_target]" {if $ex->isTarget()}checked="checked" {/if}>
    <br /><br />
    <label>Не активный пункт</label>
    <input type="checkbox" value="1" name="m[is_non_active]" {if $ex->isNonActive()}checked="checked" {/if}>
    <br />
    <div class="buttonpanel">
        <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value = '';">
        <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
    </div>
    </div>
    <input type="hidden" name="redirect" value="0" id="redirect">
    <input type="hidden" name="id" value="{$ex->getPk()}" id="id">
</form>
<div style="display: none;" id="buffer"></div>

<script type="text/javascript"> 
    {literal}
    Object.extend(window, {
        doGenerateUrl: function(res)           
        {
            var ajax = new Ajax.Updater(
            "buffer",
            "{/literal}{$fvConfig->get('dir_web_root')}transliterate/generateurl{literal}",
            {
                parameters : {name: $("name_ru").value},
                asynchronous:true,
                onComplete: function () 
                {
                    $(res).value = $("buffer").innerHTML;
                }
            }
            );
        }
    });
    
    switchMenu = function(self)
    {
        jQuery(".menus").attr("disabled", "disabled");
        jQuery(".menus").hide(0, function(){
            jQuery("#_menu-"+jQuery(self).val() ).show();
            //jQuery("#_menu-"+jQuery(self).val() ).removeAttr("disabled");
        })
    }
    {/literal}
</script> 