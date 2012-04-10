<h1>{$fvConfig->getModuleName()}</h1>

{* Кнопока добавить *}
<div class="h_clear">
{$fvModule->getAdd($ex)}
</div>
{* /Кнопока добавить *}

<form id="filter" method="post" action="/backend/{$module}" onsubmit="return true;">
	{if $List->getElementCount() > 0}
    <div class="table_body">
    <table class="text">
        <thead>
            <tr>
                <th class="mixed">
                    телефоны
                </th>
                <th class="mixed">
                    адрес
                </th>
                <th class="mixed">
                    показывать ?
                </th>
                <th class="mixed" >
                    &nbsp;
                </th>
            </tr>
        </thead>
        <tbody>
		    {foreach from=$List item=ex}
            <tr>
                <td class="mixed" >
                    {* Телефоны *}
                    {if $ex->getPhone()}{$ex->getPhone()}
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Адрес *}
                    {if $ex->getAddress()}{$ex->getAddress()}                                  
                    {else}Нет значения{/if}
                </td>
			<td class="mixed" >
                    {* Показывать ? *}
                    {if $ex->isShow()}Да
                    {else}Нет{/if}
                </td>
			<td class="mixed" >
                    {* Кнопка редактировать *}
                    {$fvModule->getEdit($ex)}
                    {* Кнопка удалить *}
                    {$fvModule->getDelete($ex)}
                </td>
		    {/foreach}
            </tr>
        </tbody>
    </table>
    </div>
    {else}
        <div><p>Пусто. Для добавления используйте кнопку "Добавить"</p></div>
    {/if}
</form>	

{* Кнопка добавить *}
<div class="h_clear">
{$fvModule->getAdd($ex)}
</div>
{* /добавить *}

{* Пейджер *}
{if $List->hasPaginate()}
<div id="paging" class="paging">
	{$List->showPagerAjax(false,"window.doPager")}
</div>
{/if}
{* /Пейджер *}