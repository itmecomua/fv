{if !$search}
<h1>Новости</h1>
<h4>Список всех новостей</h4>
<div style="width: 100%">
    <div class="table_body">
        <form id="filter" method="post" action="/backend/{$module}" onsubmit="return true;">

            <div class="form">
                <fieldset>
                    <legend>Поиск</legend>
                    <label for='name'>Имя</label><input type='text' name='search[name]' id='name' /><br/>
                </fieldset>
            </div>
            <div class="operation">
                <a href="javascript:void(0);" onclick="$('clear').value = 1; window.doSendForm();" class="delete">очистить</a>
                <a href="javascript:void(0);" onclick="$('clear').value = '';  window.doSendForm();" class="accept">применить</a>
                <div style="clear: both;"></div>
            </div>

            <input type="hidden" id="clear" name="search[_clear]" value="">
            <input type="hidden" id="tag" name="search[tag]" value="">
            <input type="hidden" id='page' name="page" value="{$page}"/>
            <input type="hidden" id='direct' name="direct" value="{$sort.dir}"/>
            <input type="hidden" id='field' name="field" value="{$sort.field}"/>
        </form>
    </div>
</div>
<div id="result">
{/if}

<table cellpadding="10" cellspacing="10">
<tr><td style="padding:10px;">
<div style="width: 100%">
<div class="table_body">
    <table class="text">
        <tr>
            <th style="text-align:center;">
                <u>
                    <a href="javascript:void(0);" onclick="window.doSort('name','{if $sort.dir=='asc'}desc{else}asc{/if}'); return false;">Название{if $sort.field=='name'}<img src="{$fvConfig->get('dir_web_root')}img/{$sort.dir}_arrow.gif" width="11" height="13">{/if}
                    </a>
                </u>
            </th>
           <th style="text-align:center;">
                <u>
                    <a href="javascript:void(0);" onclick="window.doSort('is_promo','{if $sort.dir=='asc'}desc{else}asc{/if}'); return false;">Промо{if $sort.field=='create_time'}<img src="{$fvConfig->get('dir_web_root')}img/{$sort.dir}_arrow.gif" width="11" height="13">{/if}
                    </a>
                </u>
            </th>
            <th style="text-align:center;">
                <u>
                    <a href="javascript:void(0);" onclick="window.doSort('create_time','{if $sort.dir=='asc'}desc{else}asc{/if}'); return false;">Дата добавления{if $sort.field=='create_time'}<img src="{$fvConfig->get('dir_web_root')}img/{$sort.dir}_arrow.gif" width="11" height="13">{/if}
                    </a>
                </u>
            </th>

            <th style="text-align:center;" width="50px">
                <u>
                    <a href="javascript:void(0);" onclick="window.doSort('weight','{if $sort.dir=='asc'}desc{else}asc{/if}'); return false;">Вес{if $sort.field=='weight'}<img src="{$fvConfig->get('dir_web_root')}img/{$sort.dir}_arrow.gif" width="11" height="13">{/if}</a>
                </u>
            </th>

            <th style="text-align:center;" width="130px">
                <u>
                    <a href="javascript:void(0);" onclick="window.doSort('is_active','{if $sort.dir=='asc'}desc{else}asc{/if}'); return false;">Отображается{if $sort.field=='is_active'}<img src="{$fvConfig->get('dir_web_root')}img/{$sort.dir}_arrow.gif" width="11" height="13">{/if}</a>
                </u>
            </th>

            <th>
                &nbsp;
            </th>
        </tr>
         {if $List->getElementCount()>0}
        {foreach from=$List item=ex}
        <tr>
            <td style="text-align:left;">
                {$ex->name}
            </td>
            <td style="text-align:left;">
                {if $ex->is_promo}Да{/if}
            </td>
            <td style="text-align:center;" width="150px;">
                {$ex->create_time|date_format:"%d.%m.%Y %H:%M"}
            </td>

            <td style="text-align:center;" >
                {$ex->weight}
            </td>

            <td style="text-align:center;">
                {if $ex->is_active}Да{else}Нет{/if}
            </td>

            <td width="80px" style="text-align:center">
                <a href="javascript:void();" onclick="go('{$fvConfig->get('dir_web_root')}{$module}/edit/?id={$ex->getPk()}'); return false;">
                    <img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16">
                </a>

                <a href="javascript: void(0);" onclick="if (confirm('Вы действительно хотите удалить эту запись?'))  go('{$fvConfig->get('dir_web_root')}{$module}/delete/?id={$ex->getPk()}'); return false;">
                    <img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16">
                </a>

            </td>
        </tr>
        {/foreach}
        {else}
            <tr><td colspan="6">Пусто. Для добавления нажмите кнопку "Добавить"</td></tr>
        {/if}
    </table>


    {if $List->hasPaginate()}
    <div id="paging" class="paging">
        {$List->showPagerAjax(false,"window.doPager")}
    </div>
    {/if}
    {if $search}
</div>
{/if}

<div class="operation">
    <a href="javascript: void(0);" onclick="go('{$fvConfig->get('dir_web_root')}{$module}/edit/'); return false;" class="add">добавить</a>
    <div style="clear: both;"></div>
</div>

{literal}
<script> 
    Object.extend(window, {

        doSendForm: function () 
        {
            if($('clear').value == 1)
                {
                $('filter').reset();
            }
            $('page').value = 0;
            window.blockScreen(); 
            var data = Form.serialize("filter");      
            new Ajax.Updater(
            "result", 
            "{/literal}{$fvConfig->get('dir_web_root')}{$module}/index{literal}", 
            {
                parameters: data,                    
                onComplete: function(transport){window.completeRequest(transport);window.parseForms();},
            });
        },
        doPager: function (page) 
        {
            if($('clear').value == 1)
                {
                $('filter').reset();
            }
            window.blockScreen();
            $('page').value = page;
            var data = Form.serialize("filter");      
            new Ajax.Updater(
            "result", 
            "{/literal}{$fvConfig->get('dir_web_root')}{$module}/index{literal}", 
            {
                parameters: data,                    
                onComplete: function(transport){window.completeRequest(transport);window.parseForms();},
            });
        },
        doSort: function (field,direct) 
        {
            if($('clear').value == 1)
                {
                $('filter').reset();
            }
            window.blockScreen(); 
            $('direct').value = direct;
            $('field').value = field;
            var data = Form.serialize("filter");      
            new Ajax.Updater(
            "result", 
            "{/literal}{$fvConfig->get('dir_web_root')}{$module}/index/{literal}", 
            {
                parameters: data,                    
                onComplete: function(transport){window.completeRequest(transport);window.parseForms();},
            });
        },        

    });
</script>
{/literal}