<h1>{$fvConfig->getModuleName($module)}</h1>
<div class="form">
    <form action="/backend/{$module}/save">
    <div class="buttonpanel"> 
        <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value= '';">
    </div>
        <div class="table_body">
            <table class="text">
                <tr>
                    <td class="mixed">
                       <span style="color: red"> &darr; Ключи / Языки     &rarr; </span>
                    </td>
                    {foreach from=$langKeys item=lang}
                        <th>{$langs.$lang.legend}</th>
                    {/foreach}    
                </tr>
                    {foreach from=$keys item=key}
                         <tr>
                              <th>
                                {$key}
                              </th>
                              {foreach from=$langKeys item=lang}
                                  <td class="mixed">
                                    <input type="text" name="m[{$key}][{$lang}]" value="{$tranliterate.$key.$lang}" style="width:170px" />
                                  </td>
                              {/foreach} 
                         </tr>
                    {/foreach}
            </table>
        </div>
    <div class="buttonpanel"> 
        <input type="submit" name="save" value="Сохранить" class="button">
    </div>
    </form>
</div>

