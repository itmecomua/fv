<FORM method="post" action="/backend/usergroups/save/">
<div class="form">
    <H1>Группы пользователей</H1>
    <div class="operation"><a href="{$fvConfig->get('dir_web_root')}usergroups/" onclick="go('{$fvConfig->get('dir_web_root')}usergroups/'); return false;" class="left">вернутся к списку</a><div style="clear: both;"></div></div>
<div style="width: 50%; float: left;">
<fieldset>
    <legend>Общая информация</legend>  
    <label for="group_name">Название</label> <input type="text" id="group_name" name="mg[group_name]" value="{$UserGroup->group_name}" /><br/>
    <label for="info">Описание</label> <textarea rows="3" id="info" name="mg[info]">{$UserGroup->info}</textarea><br clear="all">
    <input type="hidden" name="id" id="id" value="{$UserGroup->getPk()}" />
    
    <p style="color: #922;">Новые пользователи без указания группы автоматически попадают в группу, которая установленна по умолчанию.
    Только одна группа может иметь этот признак.</p>
    
    <input type="checkbox" name="mg[default_group]" value="1" id="m_default_group" {if $UserGroup->default_group}checked="true"{/if}>
    <label for="m_default_group" class="checkbox">Группа по умолчанию</label> <br />
    
    
    <input type="hidden" id="redirect" name="redirect" value="" />
    </fieldset>
</div>
<div style="float: left; width: 40%; margin-left: 10px;">
    <fieldset>
        <legend>Права доступа</legend>

        <div style="" id="parameters_list">
{foreach item=acl_group from=$fvConfig->get('acls')}

<fieldset>
    <legend>{$acl_group.name}</legend>         
        
    <ul class="acls">
{foreach item=acl from=$acl_group key=acl_name}

    {if $acl_name ne 'name'}{if is_array($acl)} 
        <li> 
        <input type="checkbox" name="" value="" id="mg_permitions_{$acl_name}" {literal}onclick="$$('#content div.form ul.acls li input#mg_permitions_{/literal}{$acl_name}{literal} + label + ul > li > input').each(function (el) {el.checked = $('mg_permitions_{/literal}{$acl_name}{literal}').checked});"{/literal}>
        <label for="mg_permitions_{$acl_name}" class="checkbox">{$acl.name}</label>
        <ul class="acls">
        {foreach item=acl_chld from=$acl key=acl_chld_name}
            {if $acl_chld_name ne 'name'}
                <li><input type="checkbox" name="mg[permitions][]" value="{$acl_chld_name}" id="mg_permitions_{$acl_name}_{$acl_chld_name}" {if in_array($acl_chld_name, $UserGroup->permitions)}checked="true"{/if} onchange="$('mg_permitions_{$acl_name}').checked = false;">
                <label for="mg_permitions_{$acl_name}_{$acl_chld_name}" class="checkbox">{$acl_chld}</label></li>
            {/if}
        {/foreach}
        </ul>
        </li>
    {else}
        <li><input type="checkbox" name="mg[permitions][]" value="{$acl_name}" id="mg_permitions_{$acl_name}" {if in_array($acl_name, $UserGroup->permitions)}checked="true"{/if}>
        <label for="mg_permitions_{$acl_name}" class="checkbox">{$acl}</label></li>
    {/if}{/if}
{/foreach}
</ul>
</fieldset>
{/foreach}
</div>

</fieldset>

</div>
<div style="clear: both;"></div>

<div class="buttonpanel">
     <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value = '';">
     <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
</div>



</FORM>
