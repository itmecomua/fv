<FORM method="post" action="/backend/{$module}/save/">
    <div class="form">
        <H1>Курорт</H1>
        <div class="operation">
            <a href="{$manager->getBackendListURL()}" onclick="go('{$manager->getBackendListURL()}'); return false;" class="left">
                вернутся к списку
            </a>
            <div style="clear: both;"></div>
        </div>
        <div>
        <table class="form">
            <tr>
                <td style="width: 1px;">
                    <label for="name">Имя</label>
                </td>
                <td>
                    <input type="text" id="name" name="update[name]" value="{$inst->getName()|escape}" class="full" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="tech_url">Страна</label>
                </td>
                <td>
                    {html_options options=$listCountry
                                  selected=$inst->country_id
                                  name="update[country_id]"}
                    
                </td>
            </tr>
             <tr>
                <td><label>URL:</label></td>
                <td>
                    <input type="text" name="update[url]" value="{$inst->getURL()}" id="url" />
                    <a href='javascript:void(0);' onclick="javascript:window.doGenerateUrl('url');"><p style='font-size: 10px; color:#dddddd; margin-left: 210px; margin-bottom: 3px;'>сгенерировать URL по названию.</p></a>
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
});
{/literal}
</script>