<h1>Справочник типов туров</h1>
<div style="width: 50%">
<div class="table_body">
<form id="search" method="post" action="{$manager->getBackendListURL()}" onsubmit="return false;">
    <div class="filter_form form" style="margin-bottom: 10px;">
    <h3>фильтр</h3>        
        
        <label for="message" style="width: 170px;">Название:</label>        
        {assign var=fName value=$manager->getConst('F_NAME')}
        <input type="text" id="name" name="search[{$fName}]" value="{$search.$fName}" style="width: 300px;"><br />

        <br clear="all" />
        <div class="operation">
            <a href="javascript:void(0);" onclick="$('clear').value = 1; window.doSendForm();" class="delete">очистить</a>
            <a href="javascript:void(0);" onclick="$('clear').value = ''; window.doSendForm();" class="accept">применить</a>
            <div style="clear: both;"></div>
        </div>
        <input type="hidden" id="clear" name="search[_clear]" value="">
        <input type="hidden" id="field" name="order[field]" value="{$order.field}">
        <input type="hidden" id="direct" name="order[direct]" value="{$order.direct}">
        <input type="hidden" id="page" name="page" value="{$page}">
    </div>
</form>

<table class="text">
<tr>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_NAME')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Название{if $order.field==$manager->getConst('F_NAME')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>        
    </th>
    <th>
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_WEIGHT')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Вес{if $order.field==$manager->getConst('F_WEIGHT')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th>
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_IS_SHOW')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Отображать{if $order.field==$manager->getConst('F_IS_SHOW')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th>&nbsp;</th>
</tr>
{if $list->getElementCount()}
    {foreach item=inst from=$list}
    <tr>
        <td class="mixed">{$inst->getName()}</td>
        <td class="mixed">{$inst->getWeight()}</td>
        <td class="mixed">{if $inst->getIsShow()}Да{else}Нет{/if}</td>
        <td>
            <a href="{$inst->getBackendEditURL()}" onclick="go('{$inst->getBackendEditURL()}'); return false;">
                <img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16">
            </a>
            <a href="javascript: void(0);" onclick="if (confirm('Вы действительно желаете удалить запись?')) go('{$inst->getBackendDeleteURL()}'); return false;">
                <img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16">
            </a>
        </td>
    </tr>
    {/foreach}
{else}
    <tr>
        <td colspan="2">Для добавления справочных данных нажмите "Добавить"</td>
    </tr>
{/if}
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