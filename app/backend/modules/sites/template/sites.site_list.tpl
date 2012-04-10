<table>
  <tr>
    <td id="leftpanel" valign="top">
        <div id="leftpaneldiv">
            <a href="javascript:void(0);" id="hidePanel"><img id="collapse" src="{$fvConfig->get('dir_web_root')}img/collapse.gif" width="15" height="15"></a>
            <div class="header" id="leftPanelHeader">Список страниц</div>
            <div id="leftpaneldata">

                {foreach item=oneSite from=$Sites}
                <div style="float: right">
        <A
           href="{$fvConfig->get('dir_web_root')}sites/?id={$oneSite->getPk()}"
        onclick="go('{$fvConfig->get('dir_web_root')}sites/?id={$oneSite->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16"></a><a
           href="javascript: void(0);"
        onclick="if (confirm('Вы действительно желаете удалить пункт меню. Все дочерние меню перенесутся в корень.')) go('{$fvConfig->get('dir_web_root')}sites/delete/?id={$oneSite->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16"></a>
                    </div><div class="{if $oneSite->getPk() eq $smarty.request.id}selected{/if}">{$oneSite.name}</div><div style="clear: both;"></div>


                 {/foreach}

                <div class="operation">
                    <a href="{$fvConfig->get('dir_web_root')}sites/" onclick="go('{$fvConfig->get('dir_web_root')}sites/'); return false;" class="add">добавить</a>
                </div>
            </div>
        </div>
    </td>
    <td class="spacer">&nbsp;</td>
    <td id="datapanel">

<div class="form">
    <H1>{if $Site->isNew()}Добавление сайта сети{else}Редактирование сайта сети '{$Site->name}'{/if}</H1>
    <form method="post" action="/backend/sites/save/">
    <fieldset>
        <legend>Общая информация</legend>
        <table class="form">
        <tr><td style="width: 1px;">
        <label for="name">Название</label></td><td> <input type="text" id="name" name="s[name]" value="{$Site->name|escape}"/>
        </td></tr><tr><td>
        <label for="url">имя сайта (URL без http://)</label></td><td><input type="text" id="url" name="s[url]" value="{$Site->url|escape}" /> <br />
        </td></tr><tr><td>
        <label for="ip">IP сайта</label></td><td><input type="text" id="ip" name="s[ip]" value="{$Site->ip|escape}" /> <br />
        </td></tr><tr><td>
        <label for="app">Приложение</label></td>
        <td>
        	<select id="app" name="s[app]">
        	{foreach from=$apps item=app}
        		<option value='{$app}' {if $Site->app == $app}selected{/if}>{$app}</option>
        	{/foreach}
        	</select>
        <br />
        </td></tr><tr><td colspan="2">
        <input type="checkbox" name="s[active]" value="1" id="s_active" {if $Site->active}checked="true"{/if}>
        <label for="s_active" class="checkbox">активный сайт сети</label> <br /></td></tr>
        </table>
    </fieldset>

    <div class="buttonpanel">
        <input type="submit" name="save" value="Сохранить" class="button">
    </div>
    <input type="hidden" name="id" id="id" value="{$Site->getPk()}" />

    </form>
</div>

    </td>
  </tr>
</table>

<div style="display: none" id="headers_content">
    <div class="popup_content">
        <a href="javascript: void(0)">StaticPages_getTitle</a> - заголовок статической страницы
    </div>
</div>

{literal}
<script>
    function moveLeftPanel (e) {
        if ($('leftpanel').getDimensions().width > 100) {
            $('leftpanel').morph('width: 20px;');
            $('leftPanelHeader').update("");
            $('leftpaneldata').hide();
            $('collapse').src = '{/literal}{$fvConfig->get('dir_web_root')}img/expand.gif{literal}';
        } else {
            $('leftpanel').morph('width: 300px;');
            $('collapse').src = '{/literal}{$fvConfig->get('dir_web_root')}img/collapse.gif{literal}';
            setTimeout("$('leftPanelHeader').update('Список страниц')", 1000);
            setTimeout("$('leftpaneldata').show()", 1000);
        }
    }

    $('hidePanel').observe('click', moveLeftPanel);
</script>
{/literal}