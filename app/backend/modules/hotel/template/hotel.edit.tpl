<FORM method="post" action="/backend/{$module}/save/">
    <div class="form">
        <H1>Отель</H1>
        <div class="operation">
            <a href="{$manager->getBackendListURL()}" onclick="go('{$manager->getBackendListURL()}'); return false;" class="left">
                вернутся к списку
            </a>
            <div style="clear: both;"></div>
        </div>
        <br />
        <div>
        <table class="form">
            <tr>
                <td style="width: 1px;">
                    <label for="name">Название*:</label>
                </td> 
                <td>
                    <input type="text" id="name" name="update[name]" value="{$inst->getName()|escape}" class="full" />
                </td>
            </tr>
             <tr>
                <td><label>URL*:</label></td>
                <td>
                    <a style="float: left;" href='javascript:void(0);' onclick="javascript:window.doGenerateUrl('url');"><p style='font-size: 10px; color:#dddddd; margin-left: 210px; margin-bottom: 3px;'>сгенерировать URL по названию.</p></a>
                    <input type="text" name="update[url]" value="{$inst->getURL()}" id="url" />
                    
                </td>
            </tr>
             <tr>
                <td><label>Страна*:</label></td>
                <td>
                    {html_options options=$listCountry
                                  selected=$inst->getCountry()->getPk()
                                  name="update[country_id]"
                                  id="country_id"
                                  onchange="javascript: reloadResort();"}
                </td>
            </tr>
            <tr>
                <td><label>Курорт*:</label></td>
                <td >
                    {html_options options=$listResort
                                  selected=$inst->getResort()->getPk()
                                  name="update[resort_id]"
                                  id="resort_id"}
                </td>
            </tr>
            <tr>
                <td><label>Тип отеля:</label></td>
                <td >
                    
                    {html_options options=$listHotelType
                                  selected=$inst->getHotelType()->getPk()
                                  name="update[hotel_type_id]"
                                  id="hotel_type_id"}
                </td>
            </tr>
            <tr>
                <td><label>Короткое описание: </label></td>
                <td><textarea cols="" rows="" name="update[short_text]"  style="width: 95%; height: 65px;">{$inst->getShortText()}</textarea><br /></td>
            </tr>           
            <tr>
                <td><label>Полное описание: </label></td>
                <td>                
                {fckeditor name="update[full_text]" 
                       id="_full_text"
                       width="95%"
                       height="400px"
                       text=$inst->getFullText()}          
                </td>
            </tr>                       
            <tr>
                <td><label>Медиа:</label></td>
                <td>                                    
                    {if !$inst->isNew()}                                        
                        <div class="operation">
                            <a style="float: left;" href="javascript:void(0)" onclick="javascript:goEditPhoto();" class="add">редактирование</a>
                        </div>
                    {else}
                        Доступно после сохранения объекта
                    {/if}
                </td>
            </tr>
        </table>
        <div class="buttonpanel">
            <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value = '';">
            <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
        </div>
        <input type="hidden" name="id" id="id" value="{$inst->getPk()}" />
        <input type="hidden" id="redirect" name="redirect" value="" />
    </div>

    <div style="clear: both;" />
    </div>
</FORM>
<div id='buffer' style="display: none;"></div>
<script type="text/javascript">
{literal}
Object.extend(window, {
    doGenerateUrl: function(res)           
    {
        var ajax = new Ajax.Updater(
        "buffer",
        "{/literal}{$fvConfig->get('dir_web_root')}transliterate/generateurl{literal}",
        {
            parameters : {name: $("name").value},
            asynchronous:true,
            onComplete: function () 
            {
                $(res).value = $("buffer").innerHTML;
            }
        }
        );
    }
    ,goEditPhoto : function()
    {
        var div = jQuery("<div id='_edit_attr' title='Редактирование изображений'><img src='{/literal}{$fvConfig->get('dir_web_root')}img/progressbar.gif{literal}'/></div>");
        div.dialog({
           height: 500,
           width: 800, 
           modal: true,
           resizable: false,
           close : function(){
               jQuery(this).remove();
           }
        });
        getEditPhoto();
        
    }
    ,getEditPhoto: function()
    {
        jQuery.post('{/literal}{$fvConfig->get('dir_web_root')}{$module}/editphoto/?id={$inst->getPk()}{literal}', function(r){
            jQuery("#_edit_attr").html(r);
        });
    }
    ,reloadResort: function()
    {
        var countryId = jQuery("#country_id").val();        
        jQuery.post('{/literal}{$fvConfig->get('dir_web_root')}{$module}/reloadresort/?id={literal}'+countryId, function(r){
            jQuery("#resort_id").html(r);
        });
    }

});
{/literal}
</script>