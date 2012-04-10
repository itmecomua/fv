<style type="text/css">
{literal}
div.form label 
{
    width: auto;
}
.checkboxItem
{
    float: left; 
    width: 145px; 
    margin-left: 30px;
}
.checkboxItem:hover
{
    background-color: silver;
    cursor: pointer;
}
{/literal}
</style>
<FORM method="post" action="/backend/{$module}/save/">
    <div class="form">
        <H1>Тур</H1>
        {if !$inst->isNew()}<span style="font-size: 12px;">количество просмотров - {$inst->getCntView()}</span>{/if}
        <div class="operation">
            <a href="{$manager->getBackendListURL()}" onclick="go('{$manager->getBackendListURL()}'); return false;" class="left">
                вернутся к списку
            </a>
            <div style="clear: both;"></div>
        </div>
        <div>
        <table class="form">
            <tr>
                <td style="width: 150px;">
                    <label for="name">Имя *</label>
                </td>
                <td>
                    <input type="text" id="name" name="update[name]" value="{$inst->getName()|escape}" class="full" />
                </td>
            </tr>            
            <tr>
                <td><label>URL *:</label></td>
                <td>
                    <input type="text" name="update[url]" value="{$inst->getURL()}" id="url" style="width: 70%"/>
                    <a style="display: inline;" href='javascript:void(0);' onclick="javascript:window.doGenerateUrl('url');"><span style='font-size: 10px; margin-bottom: 3px;'>сгенерировать URL по названию.</span></a>
                </td>
            </tr>
            <tr>
                <td><label>Цена *:</label></td>
                <td>
                    <input type="text" id="price" name="update[price_from]" value="{$inst->getPrice()|escape}" class="full" style="width: 282px;" />
                    <input type="text" id="currency" name="update[currency]" value="{$inst->getCurrency()|escape}" class="full" style="width: 50px; text-align: center;" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Продолжительность</label>
                </td>
                <td>
                    {html_options options=$listDuration
                                  selected=$inst->getDuration()
                                  name="update[duration]"}
                </td>
            </tr>
            <tr>
                <td><label>Отображать:</label></td>
                <td>
                    {html_options options=$listVisible
                                  selected=$inst->isShow()
                                  name="update[is_show]"}                    
                </td>
            </tr>
            <tr>
                <td><label>Страны:<br /><br /></label></td>
                <td>
                    <a style="display: inline;" href='javascript:void(0);' onclick="javascript:window.getDialig('Countries');"><span style='font-size: 12px; color: red; margin-left: 12px;'>Выбрать</span></a>
                    <span style='font-size: 10px; color: blue; margin-left: 10px;' id='selectedCountries'>
                    {foreach from=$inst->getCountries() item=tCountry}
                         {$tCountry->getCountry()->getName()}, <input type="hidden" value="{$tCountry->getCountry()->getPk()}" name="update[Countries][]">
                    {/foreach}
                    </span>
                    
                </td>
            </tr> 
            <tr>
                <td><label>Типы туров:<br /><br /></label></td>
                <td>
                    <a style="display: inline;" href='javascript:void(0);' onclick="javascript:window.getDialig('TourType');"><span style='font-size: 12px; color: red; margin-left: 12px;'>Выбрать</span></a>
                    <span style='font-size: 10px; color: blue; margin-left: 10px;' id='selectedTourType'>
                    {foreach from=$inst->getTourTypes() item=tType}
                         {$tType->getTourType()->getName()}, <input type="hidden" value="{$tType->getTourType()->getPk()}" name="update[TourType][]">
                    {/foreach}
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td><label>Даты:<br /><br /></label></td>
                <td>
                    <a style="display: inline;" href='javascript:void(0);' onclick="javascript:window.getCalendar();"><span style='font-size: 12px; color: red; margin-left: 12px;'>Выбрать</span></a>
                    <span style='font-size: 10px; color: blue; margin-left: 10px;' id='selectedCalendar'>
                    {foreach from=$inst->getDates() item=tDate}
                         <span id="elem-{$tDate->getDateStart('dmY')}" style="margin-right:15px;">{$tDate->getDateStart()}<a onclick="javascript:window.removeCalendar('{$tDate->getDateStart("dmY")}');" href="javascript:void(0);"><img height="10" title="Удалить" src="/backend/img/delete.png"></a><input type="hidden" value="{$tDate->getDateStart('Y-m-d')}" name="update[dates][]"></span>
                    {/foreach}
                    </span>
                    <input type="text" id='calendar' style="visibility: hidden;">
                    
                </td>
            </tr> 
            <tr>
                <td>                
                   <label for="title">Вес</label>
                </td>
                <td>
                    {html_options options=$listWeight
                                  selected=$inst->getWeight()
                                  name="update[weight]"}
                </td>
            </tr>
            <tr>
                <td><label>Короткое описание: </label></td>
                <td><textarea cols="" rows="" name="update[short_text]"  style="width: 95%; height: 65px;">{$inst->getShortText()}</textarea></td>
            </tr>           
            <tr>
                <td><label>Полное описание: </label></td>
                <td>                
                {fckeditor name="update[full_text]" 
                       id="_full_text"
                       width="95%"
                       height="500px"
                       text=$inst->getFullText()}          
                </td>
            </tr>
            <tr>
                <td><label>Медиа</label></td>
                <td>                
                    <div class="operation">
                    {if !$inst->isNew()}                                        
                        <a style="float: left;" href="javascript:void(0)" onclick="javascript:window.goEditPhoto();" class="add">редактирование</a>
                    {else}
                        Доступно после сохранения объекта
                    {/if}
                    </div>            
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
    
    <div style="display: none;">
        <div id='boxCountries' title="Выбор стран">
        {foreach from=$countires item=country}
            <div class="checkboxItem"><label style="float: left; width: 120px; height: 15px; overflow: hidden;" for="cnt{$country->getPk()}">{$country->getName()}</label>
            <input type="checkbox" id='cnt{$country->getPk()}' value="{$country->getPk()}" {if $inst->hasCountry($country->getPk())}checked='checked'{/if}></div>
        {/foreach}
        </div> 
        <div id='boxTourType' title="Выбор типов туров">
        {foreach from=$types item=type}
            <div class="checkboxItem"><label style="float: left; width: 120px; height: 15px; overflow: hidden;" for="tt{$type->getPk()}">{$type->getName()}</label>
            <input type="checkbox" id='tt{$type->getPk()}' value="{$type->getPk()}" {if $inst->hasTourType($type->getPk())}checked='checked'{/if}></div>
        {/foreach}
        </div>
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
    },
    
    getDialig: function(type)
    {
      jQuery("#box"+type).dialog({
          width: 900,
          height: 600, 
          modal: true,
          close: function(event, ui) {
              jQuery("#selected"+type).html();
              var data = new Array();
              var dataInput = new Array();
                jQuery("#box"+type+" input:checkbox:checked").each(function(){
                    data.push(jQuery(this).prev("label").html());
                    var inputCode = "<input type='hidden' name='update["+type+"][]' value='"+jQuery(this).val()+"'>";
                    dataInput.push(inputCode);                                                                 
                });
                jQuery("#selected"+type).html(data.join(", ") + dataInput.join(""));
          }
      });  
    },
    
    getCalendar: function()
    {
      jQuery("#calendar").datepicker({
          dateFormat: "dd.mm.yy",
          minDate: "+1D",
          onClose: function(dateText, inst) {
             var key = jQuery("#calendar").datepicker('getDate').format('ddmmyyyy');//parseInt(Math.random() * 100000);
             if(jQuery("#elem-" + key).length > 0) return;
             
             var selDate = jQuery("#calendar").datepicker('getDate').format('yyyy-mm-dd');
             var data = jQuery("#selectedCalendar").html();
             var removeCode = "<a href='javascript:void(0);' onclick='javascript:window.removeCalendar(\""+key+"\");'><img src='/backend/img/delete.png' height='10' title='Удалить'></a>";
             var inputCode = "<input type='hidden' name='update[dates][]' value='"+selDate+"'>";
             dateText = "<span style='margin-right:15px;' id='elem-"+key+"'>" +  dateText + removeCode + inputCode +"</span>";
            
            jQuery("#selectedCalendar").html(data + " " + dateText);  
          }
      });
      jQuery("#calendar").datepicker("show");
    },
    
   removeCalendar: function(key)
   {
       jQuery("#elem-"+key).remove();
   },
   
   goEditPhoto : function()
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
        window.getEditPhoto();
        
    },
    
    getEditPhoto: function()
    {
        jQuery.post('{/literal}{$fvConfig->get('dir_web_root')}{$module}/editphoto/?id={$inst->getPk()}{literal}', function(r){
            jQuery("#_edit_attr").html(r);
        });
    }
});
{/literal}
</script>