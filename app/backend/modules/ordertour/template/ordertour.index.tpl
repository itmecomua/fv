
<h1>Заказы туров</h1>
<div style="width: 50%">
<div class="table_body">
<form id="search" method="post" action="{$manager->getBackendListURL()}" onsubmit="return false;">
    <div class="filter_form form" style="margin-bottom: 10px;">
    <h3>фильтр</h3>        
        
        <label for="message" style="width: 170px;">ФИО:</label>        
        {assign var=fName value=$manager->getConst('F_NAME')}
        <input type="text" id="name" name="search[{$fName}]" value="{$search.$fName}" style="width: 300px;">
        <br />
        <label for="message" style="width: 170px;">Email:</label>        
        {assign var=fName value=$manager->getConst('F_EMAIL')}
        <input type="text" id="email" name="search[{$fName}]" value="{$search.$fName}" style="width: 300px;">
        <br />
        <label for="message" style="width: 170px;">Статусы:</label>        
        {assign var=fName value=$manager->getConst('F_STATE')}
        {html_options options=$manager->getListState("Любой")
                      name="search[`$fName`]"
                      selected=$search.$fName
                      id="state"}        
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
    <th> Тур </th>
    <th> на дату</th>
    <th> Количество человек</th>
    <th style="text-align:center;">
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_NAME')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                ФИО
                {if $order.field==$manager->getConst('F_NAME')}
                    <img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13" />
                {/if}
            </a>           
    </th>
    <th style="text-align:center;">
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_EMAIL')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Email{if $order.field==$manager->getConst('F_EMAIL')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13" />
                {/if}
            </a>            
    </th>
    <th>телефон</th>    
    <th>пожелания</th>    
    <th style="text-align:center;">
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_STATE')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Статус{if $order.field==$manager->getConst('F_STATE')}
                <img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13" />
                {/if}
            </a>           
    </th>
    <th style="text-align:center;">
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_CTIME')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                создано{if $order.field==$manager->getConst('F_CTIME')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13" />
            {/if}
            </a>           
    </th>
    <th>&nbsp;</th>
</tr>
{if $list->getElementCount() > 0}
{foreach item=inst from=$list}
<tr>
    <td class="mixed">{$inst->tour_name}</td>    
{*    <td class="mixed">{if $inst->date_tour}{$inst->date_tour|date_format:"%d.%m.%Y"}{/if}</td>    *}
    <td class="mixed">{if $inst->date_tour}{$inst->date_tour|date_format:"%d.%m.%Y"}{/if}</td>    
    <td class="mixed">{if $inst->cnt_adult}{$inst->cnt_adult}{/if}</td>
    <td class="mixed">{$inst->name}</td>
    <td class="mixed">{$inst->email}</td>
    <td class="mixed">{$inst->phone}</td>
    <td class="mixed">{if $inst->wish_text}{$inst->wish_text}{/if}</td>    
    <td class="mixed">{$inst->getStateName()}</td>
    <td class="mixed">{$inst->ctime|date_format:"%d.%m.%Y %H:%M"}</td>        


    <td>
        <a href="{$inst->getBackendEditURL()}" onclick="go('{$inst->getBackendEditURL()}'); return false;">
            <img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16">
        </a>
        <a href="javascript: void(0);" onclick="if (confirm('Вы действительно хотите удалить эту запись?'))  go('{$fvConfig->get('dir_web_root')}{$module}/delete/?id={$inst->getPk()}'); return false;">
            <img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16">
        </a>
    </td>
</tr>
{/foreach}
{else}
    <tr><td colspan="17">Список пуст</tr>
{/if}
</table>
</div>
 {if $list->hasPaginate()}
<div id="manager_param_paging" class="paging">
    {$list->showPagerAjax(false,"window.doSendForm")}
</div>
{/if}
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
