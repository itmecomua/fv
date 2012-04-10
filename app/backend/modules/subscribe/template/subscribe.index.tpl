<h1>Подписка</h1>
<div style="width: 50%">
<div class="table_body">
<form id="search" method="post" action="{$manager->getBackendListURL()}" onsubmit="return false;">
    <div class="filter_form form" style="margin-bottom: 10px;">
    <h3>фильтр</h3>        
        
        <label for="message" style="width: 170px;">Название:</label>        
        {assign var=fName value=$manager->getConst('F_EMAIL')}
        <input type="text" id="name" name="search[{$fName}]" value="{$search.$fName}" style="width: 300px;">
        <label for="message" style="width: 170px;">Статус:</label>        
        {assign var=fName value=$manager->getConst('F_ISACTIVE')}
        {html_options options=$listIsActive 
                      selected=$search.$fName
                      name="search[`$fName`]"}
        <br clear="all" />        
        <div class="operation">
            <a href="javascript:void(0);" onclick="jaavscript: window.doDownloadCSVParam();" class="list">скачать CSV</a>
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
    <th style="text-align:center;">Имя</th>
    <th style="text-align:center;">
        <u>
            <a href="javascript:void(0);" onclick="window.doSort('{$manager->getConst('F_EMAIL')}','{if $order.direct=='asc'}desc{else}asc{/if}'); return false;">
                Email{if $order.field==$manager->getConst('F_EMAIL')}<img src="{$fvConfig->get('dir_web_root')}img/{$order.direct}_arrow.gif" width="11" height="13">{/if}
            </a>
        </u>            
    </th>    
    <th style="text-align:center;">Телефон</th>
    <th style="text-align:center;">Страна</th>    
    <th style="text-align:center;">Компания</th>    
    <th style="text-align:center;">Должность</th>    
    <th style="text-align:center;">Дата и время<br /> подписки</th>           
    <th style="text-align:center;">Активная<br /> подписка</th>    
</tr>
{foreach item=inst from=$list}
<tr>
    <td class="mixed">{$inst->getName()}</td>
    <td class="mixed">{$inst->getEmail()}</td>
    <td class="mixed">{$inst->getPhone()}</td>
    <td class="mixed">{$inst->getCountry()}</td>
    <td class="mixed">{$inst->getCompany()}</td>
    <td class="mixed">{$inst->getPost()}</td>
    <td class="mixed">{$inst->getCtime()}</td>
    <td class="mixed">{if $inst->isActive()}Да{else}Нет{/if}</td>
    
</tr>
{/foreach}
</table>
</div>
 {if $list->hasPaginate()}
<div id="manager_param_paging" class="paging">
    {$list->showPagerAjax(false,"window.doSendForm")}
</div>
{/if}
</div>
<div id="dialogDownloadCSVParam" style="display: none;" title="Выгрузка данных в CSV">
<div class="table_body">
    <form action="/backend/{$module}/downloadCSV" method="post" target="_blank">
        {foreach from=$search item=fieldValue key=fieldName}
            <input type="hidden" name="search[{$fieldName}]" value="{$fieldValue|escape}"/>
        {/foreach}
        <div class="filter_form form">
            <table class="text">
                <tr>
                    <th>Поле</th>
                    <th>Выгрузить</th>            
                </tr>
                {foreach from=$manager->getFieldListCSV() item=fieldName key=fieldLabel}
                <tr>    
                    <td>
                        <label for="dlCSVParam{$fieldLabel}">{$fieldName}</label>
                    </td>
                    <td>
                        <input type="checkbox" name="fields[{$fieldLabel}]" id="dlCSVParam{$fieldLabel}" value="{$fieldLabel|escape}"/>
                    </td>
                </tr>
                {/foreach}
            </table>
            <div class="buttonpanel">
                <input type="submit" name="save" value="Скачать" class="button" onclick="javascript: this.form.submit(); return false;" />
            </div>
        </div>
    </form>
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
        }
        ,doSort: function(field,direct)
        {        
            $('direct').value = direct;
            $('field').value = field;   
            window.doSendForm();
        }
        ,doDownloadCSVParam: function()
        {
            jQuery("#dialogDownloadCSVParam").dialog();                    
        }
        
});
{/literal}
</script>