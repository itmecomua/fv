<h1>Управление пользователями</h1>

<div style="width: 50%">
<div class="table_body">
<table class="text">
<tr><th>Логин</th><th>Полное имя</th><th>Дата создания</th><th>Дата изменения</th><th>&nbsp;</th></tr>
{foreach item=User from=$Users}
<tr>
    <td>{$User->login}</td>
    <td class="mixed">{$User->full_name}</td>
    <td class="number">{$User->create_date}</td>
    <td class="number">{if $User->update_date ne '0000-00-00 00:00:00'}{$User->update_date}{else}не доступно{/if}</td>
    <td>
        {if $fvConfig->get('modules.users.access.enable') && $fvUser->check_acl($fvConfig->get('modules.users.access.acl'), 'edit')}
        <A 
           href="{$fvConfig->get('dir_web_root')}users/edit/?id={$User->getPk()}" 
        onclick="go('{$fvConfig->get('dir_web_root')}users/edit/?id={$User->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16"></a>
        {/if}{if !$User->is_root}{if $fvConfig->get('modules.users.access.enable') && $fvUser->check_acl($fvConfig->get('modules.users.access.acl'), 'delete')}<a
           href="javascript: void(0);" 
        onclick="if (confirm('Вы действительно желаете удалить менежера?')) go('{$fvConfig->get('dir_web_root')}users/delete/?id={$User->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16"></a>
       </td>
       {/if}
       {else}
       &nbsp;
       {/if}
</tr>
{/foreach}
</table>
</div>
{if $Users->hasPaginate()}
<div id="user_paging" class="paging">
{$Users->showPager()}
{literal}
<script>
    new Pager("user_paging");
</script>
{/literal}
</div>
{/if}
<div class="operation">
    <a href="{$fvConfig->get('dir_web_root')}users/edit/" onclick="go('{$fvConfig->get('dir_web_root')}users/edit/'); return false;" class="add">добавить</a>
    <div style="clear: both;"></div>
    
</div>
</div>