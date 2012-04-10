        <div class="menu_wrp">
            <div class="top_menu">
                <div class="menu_home">
                    <a href="/"></a>
                </div>
                <ul>
                {foreach from=$List item=ex name=menuItem}
                {if $ex->isActive()}
                    {if $ex->getURL(true) ne "#"}
                    {* "Зажатый, активный" пункт меню *}
                    <li class="active">
                        <a href="{$ex->getURL()|escape}"><span>{$ex->getName()}</span></a>
                    </li>
                    {else}
                    {* "Скоро" пункт меню *}
                    <li>
                        <a href="{$ex->getURL()|escape}"><span>{$ex->getName()}</span></a>
                    </li>
                    {/if}
                {else}
                    {* Обычный пункт меню *}
                    <li>
                        <a href="{$ex->getURL()|escape}"><span>{$ex->getName()}</span></a>
                    </li>
                {/if}
                {*
                шо это такое ? какие еще дети ?
                {if $ex->hasChild()}
                {show_module module=$module view="horizontal" parent_id=$ex->getPk()}
                {/if}
                *}
                {/foreach}            
                </ul>
            </div>
        </div>