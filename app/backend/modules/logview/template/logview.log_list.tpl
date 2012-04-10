<h1>Просмотр лога</h1>

<div style="width: 100%">
<div class="table_body">
<form id="filter" method="post" action="/backend/logview/" onsubmit="return false;">
<div class="filter_form" style="margin-bottom: 10px;">
<h3>фильтр</h3>
    <label for="date_from">дата с:</label> <input type="text" id="date_from" name="filter[date_from]" value="{$filter_date_from}" readonly="readonly"> 
    <img src="{$fvConfig->get('dir_web_root')}img/calendar_delete.png" width="16" height="16" border="0" class="dateselector_clear" title="очистить дату" onclick="$('date_from').value='';">
    <img src="{$fvConfig->get('dir_web_root')}img/calendar.png" width="16" height="16" border="0" class="dateselector" id="date_from_pick" title="выбор даты">
    <label for="date_to" style="margin-left: 20px;">дата по:</label> <input type="text" id="date_to" name="filter[date_to]" value="{$filter_date_to}"> 
    <img src="{$fvConfig->get('dir_web_root')}img/calendar_delete.png" width="16" height="16" border="0" class="dateselector_clear" title="очистить дату" onclick="$('date_to').value='';">
    <img src="{$fvConfig->get('dir_web_root')}img/calendar.png" width="16" height="16" border="0" class="dateselector" id="date_to_pick" title="выбор даты">
    <label for="object_name" style="margin-left: 20px;">имя:</label> <input type="text" id="object_name" name="filter[object_name]" value="{$filter_object_name}">
    <label for="operation" style="margin-left: 20px;">операция:</label> 
    <select id="operation" name="filter[operation]" style="width: 100px; float: left; display: block;">
        <option value="">любая</option>
        <option value="insert" {if $filter_operation eq 'insert'}selected{/if}>добавление</option>
        <option value="update" {if $filter_operation eq 'update'}selected{/if}>изменение</option>
        <option value="delete" {if $filter_operation eq 'delete'}selected{/if}>удаление</option>
        <option value="error" {if $filter_operation eq 'error'}selected{/if}>ошибка</option>
    </select>
    <label for="manager_id" style="margin-left: 20px;">менеджер:</label>
    {html_options name=filter[manager_id] id=manager_id options=$UserManager->htmlSelect('full_name', 'любой') selected=$filter_manager_id style="width: 150px; float: left; display: block;"}
    <br clear="all">
    <label for="message" style="width: 170px;">сообщение (может быть долго):</label> <input type="text" id="message" name="filter[message]" value="{$filter_message}" style="width: 300px;">
    <div class="operation">
        <a href="javascript:void(0);" onclick="$('clear').value = 1; window.sendForm.bind($('filter'), null, $('filter').readAttribute('action')).call();" class="delete    ">очистить</a>
        <a href="javascript:void(0);" onclick="$('clear').value = ''; window.sendForm.bind($('filter'), null, $('filter').readAttribute('action')).call();" class="accept">применить</a>
        <div style="clear: both;"></div>
    </div>
    <input type="hidden" id="clear" name="filter[_clear]" value="">
</div>
</form>

<table class="text">
<tr><th>&nbsp;</th><th>Объект</th><th>Имя объекта</th><th>Дата</th><th>Сообщение</th><th>&nbsp;</th></tr>
{foreach item=Log from=$Logs}
<tr>
    <td>
        <img src="{$fvConfig->get('dir_web_root')}img/report_{$Log->operation}.png" border="0" width="16" height="16">
    </td>
    <td>{$Log->object_type}[{$Log->object_id}]</td>
    <td>{$Log->object_name}</td>
    <td class="mixed">{$Log->date}</td>
    <td>{$Log->message}</td>
    <td>
        {if $Log->edit_link}
            <a href="{$Log->edit_link}" onclick="go('{$Log->edit_link}'); return false;"><img src="{$fvConfig->get('dir_web_root')}img/report_go.png" border="0" width="16" height="16" title="Перейти к объекту"></a>
        {/if}
    </td>
</tr>
{/foreach}
</table>
</div>
{if $Logs->hasPaginate()}
<div id="log_paging" class="paging">
{$Logs->showPager(false)}
{literal}
<script>
    new Pager("log_paging");
</script>
{/literal}
</div>
{/if}
</div>

{literal}
<script>
    Calendar.setup({
        inputField : "date_from",
        ifFormat : "%Y-%m-%d %H:%M",
        button : "date_from_pick",
        align : "Bl",
        firstDay: 1,
        showsTime: true,
        singleClick : true
    });
    Calendar.setup({
        inputField : "date_to",
        ifFormat : "%Y-%m-%d %H:%M",
        button : "date_to_pick",
        align : "Bl",
        firstDay: 1,
        showsTime: true,
        showsTime: true,
        singleClick : true
    });
</script>
{/literal}