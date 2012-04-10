<h1>Статические страницы</h1>

<div style="width: 50%">
<div class="table_body">
<table class="text">
<tr><th>Имя</th><th>URL</th><th>Заголовок</th><th>&nbsp;</th></tr>

{foreach item=StaticPage from=$StaticPages}
<tr>
    <td class="mixed">{$StaticPage->getName()}</td>
    <td class="mixed">{$StaticPage->getTechURL()}</td>
    <td class="mixed">{$StaticPage->getTitle()}</td>
    <td><A 
           href="{$fvConfig->get('dir_web_root')}staticpages/edit/?id={$StaticPage->getPk()}" 
        onclick="go('{$fvConfig->get('dir_web_root')}staticpages/edit/?id={$StaticPage->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16"></a><a
           href="javascript: void(0);" 
        onclick="if (confirm('Вы действительно желаете удалить страницу?')) go('{$fvConfig->get('dir_web_root')}staticpages/delete/?id={$StaticPage->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16"></a>
       </td>
</tr>
{/foreach}
</table>
</div>
{if $StaticPages->hasPaginate()}
<div id="manager_param_paging" class="paging">
{$StaticPages->showPager()}
{literal}
<script>
    new Pager("manager_param_paging");
</script>
{/literal}
</div>
{/if}
<div class="operation">
    <a href="{$fvConfig->get('dir_web_root')}staticpages/edit/" onclick="go('{$fvConfig->get('dir_web_root')}staticpages/edit/'); return false;" class="add">добавить</a>
    <div style="clear: both;"></div>
    
</div>
</div>