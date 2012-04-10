<h1>{$fvConfig->getModuleName()}</h1>
<p style="clear: both; float: none;" >
    местоположение вывода на сайт для кодов размещенных в коллекции кодов 1 и 2  вы можете задать самостоятельно
    зайдя в меню "содержание/управление страницами"
</p>
<div style="width: 50%">
<div class="table_body">
    <form id="search" method="post" action="{$manager->getBackendListURL()}" onsubmit="return false;">
        <input type="hidden" id="clear" name="search[_clear]" value="">
        <input type="hidden" id="field" name="order[field]" value="{$order.field}">
        <input type="hidden" id="direct" name="order[direct]" value="{$order.direct}">
        <input type="hidden" id="page" name="page" value="{$page}">    
    </form>
    <table class="text">
    <tr>
        <th>Название</th>
        <th>Место на сайте</th>
        <th>Активен</th>
        <th>&nbsp;</th>
    </tr>
    {foreach item=inst from=$list}
    <tr>
        <td class="mixed">{$inst->getName()}</td>
        <td class="mixed">{$inst->getPositionName()}</td>    
        <td class="mixed">{if $inst->isActive()}да{else}нет{/if}</td>    
        <td>
            <a href="{$inst->getBackendEditURL()}" onclick="go('{$inst->getBackendEditURL()}'); return false;">
                <img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16">
            </a>
            <a href="javascript: void(0);" onclick="if (confirm('Вы действительно желаете удалить код?')) go('{$inst->getBackendDeleteURL()}'); return false;">
                <img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16">
            </a>
        </td>
    </tr>
    {/foreach}
    </table>
</div>
 {if $list->hasPaginate()}
<div id="manager_param_paging" class="paging">
    {$list->showPagerAjax(false,"window.doSendForm")}
</div>
{/if}
<div class="operation">
    <a href="{$manager->cloneRootInstance()->getBackendEditURL()}" onclick="go('{$manager->cloneRootInstance()->getBackendEditURL()}'); return false;" class="add">добавить</a>
    <div style="clear: both;"></div>
    
</div>
</div>
 <script type="text/javascript">
{literal}
Object.extend(window, {
        doSendForm: function (page) 
        {
            if($('clear').value == 1) {
                $('search').reset();
            }
            $('page').value = typeof page == 'undefined' ? 0 : page;
            window.blockScreen(); 
            var data = Form.serialize("search");      
            new Ajax.Updater(
            "content", 
            "{/literal}{$manager->getBackendListURL()}{literal}", 
            {
                parameters: data,                    
                onComplete: function(transport){window.completeRequest(transport);window.parseForms();},
            });
        },
        doSort: function(field,direct)
        {        
            $('direct').value = direct;
            $('field').value = field;   
            window.doSendForm();
        }
});
{/literal}
</script>
