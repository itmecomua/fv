<FORM method="post" action="/backend/users/save/">
<div class="form">
    <H1>{if $User->isNew()}Добавление пользователя{else}Редактирование пользователя '{$User->login}'{/if}</H1>
    <div class="operation">
    	<a href="{$fvConfig->get('dir_web_root')}users/" onclick="go('{$fvConfig->get('dir_web_root')}users/'); return false;" class="left">вернутся к списку</a>
    	<div style="clear: both;"></div>
    </div>
	<div style="width: 50%; float: left;">
	    <fieldset>
	        <legend>Общая информация</legend>
	        <label for="login">Логин</label> <input type="text" id="login" name="m[login]" value="{$User->login}" {if !$User->isNew()}readonly="readonly"{/if}/> <br />
	        <label for="group_id">Группа</label>
	        {html_options name=m[group_id] id=group_id options=$GroupManager->htmlSelect('group_name') selected=$User->group_id}<br />
	        <label for="full_name">Полное имя</label> <input type="text" id="full_name" name="m[full_name]" value="{$User->full_name}" /> <br />
	        <label for="email">E-mail</label> <input type="text" id="email" name="m[email]" value="{$User->email}" /> <br />
	        <label for="info">Описание</label> <textarea rows="3" id="info" name="m[info]">{$User->info}</textarea> <br />
	    </fieldset>
	    <fieldset>
	        <legend>Пароль пользователя</legend>
	        {if !$User->isNew()}<p style="color: #922;">Оставте пароль пустым, если не хотите его изменять</p>{/if}
	        <label for="password">Пароль</label> <input type="password" id="password" name="m[password]" /> <br />
	        <label for="password1">Повторите пароль</label> <input type="password" id="password1" name="m[password1]" /> <br />
	    </fieldset>
	    <fieldset>
	        <legend>Параметры пользователя</legend>
	        <input type="checkbox" name="m[is_root]" value="1" id="m_is_root" {if $User->is_root}checked="true"{/if}>
	        <label for="m_is_root" class="checkbox">суперпользователь</label> <br />
	        <input type="checkbox" name="m[active]" value="1" id="m_active" {if $User->active}checked="true"{/if}>
	        <label for="m_active" class="checkbox">активный пользователь</label> <br />
	        <input type="checkbox" name="m[inherit]" value="1" id="m_inherit" {if $User->inherit}checked="true"{/if}>
	        <label for="m_inherit" class="checkbox">наследовать параметры из группы</label> <br />
	    </fieldset>
	    <div class="buttonpanel">
	        <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value = '';">
	        <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
	    </div>
	    <input type="hidden" name="id" id="id" value="{$User->getPk()}" />
	    <input type="hidden" id="redirect" name="redirect" value="" />
	</div>
	<div style="float: left; width: 40%; margin-left: 10px;">
        <div id="manager_params" {if $Manager->is_root}style="display: none"{/if}>
              {foreach item=acl_group from=$fvConfig->get('acls')}
                <fieldset>
                    <legend>{$acl_group.name}</legend>        
                    <ul class="acls">
                    {foreach item=acl from=$acl_group key=acl_name}
                        {if $acl_name ne 'name'}
                          {if is_array($acl)} 
                            <li> 
                                <input type="checkbox" name="" value="" id="m_permitions_{$acl_name}" {literal}onclick="$$('#content div.form ul.acls li input#m_permitions_{/literal}{$acl_name}{literal} + label + ul > li > input').each(function (el) {el.checked = $('m_permitions_{/literal}{$acl_name}{literal}').checked}); $('m_inherit').checked = false;"{/literal}>
                                <label for="m_permitions_{$acl_name}" class="checkbox">{$acl.name}</label>
                                <ul class="acls">
                                    {foreach item=acl_chld from=$acl key=acl_chld_name}
                                        {if $acl_chld_name ne 'name'}
                                            <li><input type="checkbox" name="m[permitions][]" value="{$acl_chld_name}" id="m_permitions_{$acl_chld_name}" {if in_array($acl_chld_name, $User->permitions)}checked="true"{/if} onchange="$('m_inherit').checked = false; $('m_permitions_{$acl_name}').checked = false;">
                                            <label for="m_permitions_{$acl_chld_name}" class="checkbox">{$acl_chld}</label></li>
                                        {/if}
                                    {/foreach}
                                </ul>
                            </li>
                          {else}
                            <li>
                                <input type="checkbox" name="m[permitions][]" value="{$acl_name}" id="m_permitions_{$acl_name}" {if in_array($acl_name, $User->permitions)}checked="true"{/if} onchange="$('m_inherit').checked = false;">
                                <label for="m_permitions_{$acl_name}" class="checkbox">{$acl}</label>
                            </li>
                          {/if}
                        {/if}
                {/foreach}
                </ul>
                </fieldset>
              {/foreach}
        </div>
    </div>
<div style="clear: both;" />
</div>
</FORM>
{literal}
<script>
    function changeInherit(e) {
        if ($('m_inherit') && $('m_inherit').checked) {
            window.blockScreen();

            new Ajax.Request("{/literal}{$fvConfig->get('dir_web_root')}{literal}usergroups/getparams", {
                parameters: {'group_id': $F('group_id')},
                onComplete: function (response, json) {
                    if ($('contentblocker')) $('contentblocker').hide();
                    $$("div#manager_params input").each(function (checkbox){
                        checkbox.checked = false;
                    });
                    $A(json).each(function (acl) {
                        if ($('m_permitions_'+acl)) {
                            $('m_permitions_'+acl).checked = true;
                        }
                    });
                }
            });
        }
    }

    $('m_is_root').observe('change', function (e) {
        if (Event.element(e) && Event.element(e).checked) {
            $('manager_params').hide();
        } else {
            $('manager_params').show();
        }
    });
    $("m_inherit").observe('change', changeInherit);
    $('group_id').observe('change', changeInherit);
</script>
{/literal}
