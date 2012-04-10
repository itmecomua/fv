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
                    Скидка
                </th>
                <th class="mixed">
                    Название
                </th>
                <th class="mixed">
                    Описание
                </th>
                <th class="mixed">
                    Старая цена
                </th>
                <th class="mixed">
                    Новая цена
                </th>
                <th class="mixed">
                    Картинка
                </th>
                <th class="mixed">
                    Ссылка перехода
                </th>
                <th class="mixed">
                    В новом окне ?
                </th>
                <th class="mixed">
                    Показывать ?
                </th>
                <th class="mixed">
                    Вес
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
                    {* Скидка *}
                    {if $ex->getDiscount()}{$ex->getDiscount()}
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Название *}
                    {if $ex->getName()}{$ex->getName()}                                  
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Описание *}
                    {if $ex->getShortText()}{$ex->getShortText()}                        
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Старая цена *}
                    {if $ex->getPriceold()}{$ex->getPriceold()}                          
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Новая цена *}
                    {if $ex->getPricenew()}{$ex->getPricenew()}                          
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Картинка *}
                    {if $ex->getImageSrc()}<img src="{$ex->getImageSrc()}" alt="" />     
                    {else}Нет значения{/if}
                </td>
                <td class="mixed" >
                    {* Ссылка перехода *}
                    {if $ex->getURL()}<a target="_blank" href="{$ex->getURL()}" alt="" />{$ex->getURL()}</a>
                    {else}Нет значения{/if}
                </td>
			    <td class="mixed" >
                    {* В новом окне ? *}
                    {if $ex->isTarget()}Да
                    {else}Нет{/if}
                </td>
			    <td class="mixed" >
                    {* Показывать ? *}
                    {if $ex->isShow()}Да
                    {else}Нет{/if}
                </td>
			    <td class="mixed" >
                    {* Вес *}
                    {$ex->getWeight()}
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