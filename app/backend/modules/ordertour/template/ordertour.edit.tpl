<form method="post" action="/backend/{$module}/save/">
    <div class="form">
        <h1>Заказ #{$inst->getPk()}</h1>
        <div class="operation">
            <a href="{$manager->getBackendListURL()}" onclick="go('{$manager->getBackendListURL()}'); return false;" class="left">
                вернутся к списку
            </a>
            <div style="clear: both;"></div>
        </div>
        <div>
        <table class="form"> 
            <tr>
                <td colspan="2">
                    <label style="width: 165px;" for="tech_url">Статус</label>                
                    {html_options options=$manager->getListState()
                                  selected=$inst->state
                                  name="update[state]"}
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
</form>
<div id='buffer' style="display: none;"></div>