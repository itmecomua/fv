<FORM method="post" action="/backend/{$module}/save/">
    <div class="form">
        <H1>Код</H1>
<p style="clear: both; float: none;" >
    местоположение вывода на сайт для кодов размещенных в коллекции кодов 1 и 2  вы можете задать самостоятельно
    зайдя в меню "содержание/управление страницами"
</p>
        
        <div class="operation">
            <a href="{$manager->getBackendListURL()}" onclick="go('{$manager->getBackendListURL()}'); return false;" class="left">
                вернутся к списку
            </a>
            <div style="clear: both;"></div>
        </div>
        <div>
        <table class="form">
            <tr>
                <td style="width: 400px;">
                    <label for="name">Название</label>
                </td>
                <td>
                    <input type="text" id="name" name="update[name]" value="{$inst->getName()|escape}" class="full" />
                </td>
            </tr>
             <tr>
                <td style="width: 400px;">
                    <label for="name">Тех. имя</label>
                </td>
                <td>
                    <input type="text" id="techname" name="update[techname]" value="{$inst->techname|escape}" class="full" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="name">Код</label>
                </td>
                <td>
                    <textarea name="update[code]" id="code">{$inst->getCode()|escape}</textarea>                    
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Место на сайте</label>
                </td>
                <td>
                    {html_options options=$listPositions selected=$inst->position_id name="update[position_id]"}
                </td>
            </tr>
            <tr>
                <td><label>Активен</label></td>
                <td><input type="checkbox" value="1" name="update[is_active]" {if $inst->isActive()}checked="checked"{/if}></td>
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
