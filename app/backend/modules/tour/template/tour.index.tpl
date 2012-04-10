<h1>Справочник Туров</h1>
<div style="width: 90%">
<div class="table_body">
<form id="search" method="post" action="{$manager->getBackendListURL()}" onsubmit="return false;">
    <div class="filter_form form" style="margin-bottom: 10px;">
    <h3>фильтр</h3>        
        
        <label for="message" style="width: 170px;">Название:</label>        
        {assign var=fName value=$manager->getConst('F_NAME')}
        <input type="text" id="name" name="search[{$fName}]" value="{$search.$fName}" style="width: 300px;">
        <br clear="all" />
        <div class="operation">
            <a href="javascript:void(0);" onclick="$('clear').value = 1; window.doSendForm();" class="delete">очистить</a>
            <a href="javascript:void(0);" onclick="$('clear').value = ''; window.doSendForm();" class="accept">применить</a>
            <a href="javascript:void(0);" onclick="window.doImportTour();" class="page_go">импорт туров</a>
            <div style="clear: both;"></div>
        </div>
        <input type="hidden" id="clear" name="search[_clear]" value="">
        <input type="hidden" id="field" name="order[field]" value="{$order.field}">
        <input type="hidden" id="direct" name="order[direct]" value="{$order.direct}">
        <input type="hidden" id="page" name="page" value="{$page}">
    </div>
</form> 
<table class="text" style="width: 30%;">
    <tr>
        <th style="text-align:center;">Всего записей</th>
        <th style="text-align:center;">{$list->getElementCount()}</th>
    </tr>
</table>
<br />
<table class="text">
<tr>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_NAME')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Название{if $order.field==$manager->getConst('F_NAME')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_DURATION')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Кол-во ночей{if $order.field==$manager->getConst('F_DURATION')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_PRICE')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Цена от{if $order.field==$manager->getConst('F_PRICE')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_CNT_VIEW')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Просмотров{if $order.field==$manager->getConst('F_CNT_VIEW')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>  
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_WEIGHT')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Вес{if $order.field==$manager->getConst('F_WEIGHT')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_IS_SHOW')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Отображать{if $order.field==$manager->getConst('F_IS_SHOW')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>    
    <th>&nbsp;</th>
</tr>
{foreach item=inst from=$list}
<tr>
    <td class="mixed">{$inst->getName()}</td>
    <td class="mixed">{$inst->getDuration()}</td>
    <td class="mixed">{$inst->getPrice()} {$inst->getCurrency()}</td>
    <td class="mixed">{$inst->getCntView()}</td>
    <td class="mixed">{$inst->getWeight()}</td>
    <td class="mixed">{if $inst->isShow()}Да{else}Нет{/if}</td>
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
<div id="importTourBox" title="Импорт туров"></div>
<script type="text/javascript">
{literal}
Object.extend(window, {
        __checkImport : null
        ,doSendForm: function (page) 
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
        }
        ,doSort: function(field,direct)
        {        
            $('direct').value = direct;
            $('field').value = field;   
            window.doSendForm();
        }
        ,doImportTour: function()
        {
            blockScreen();
            var query = jQuery.ajax({url: "tour/importtour",timeout: 2000});            
            __checkImport = setInterval(window.doCheckImport,2000);
        }
        ,doCheckImport: function ()
        {            
            jQuery.ajax({
                url: "tour/checkImport",
                success: function(resp) {
                    if (resp.length>0) {                        
                        jQuery("#importTourBox").html(resp).dialog();                        
                        clearInterval(__checkImport);
                        unblockScreen();
                    }
                }
            });            
        }
});
{/literal}
</script>