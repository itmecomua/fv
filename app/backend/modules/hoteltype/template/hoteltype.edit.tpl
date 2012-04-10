<div style="width: 50%">
<FORM method="post" action="/backend/{$module}/save/">
    <div class="form">
        <H1>Класс отеля</H1>
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
                   <label for="title">Вес</label>
                </td>
                <td>
                    {html_options options=$listWeight
                                  selected=$inst->getWeight()
                                  name="update[weight]"}
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
</div>
