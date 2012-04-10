<ul class="menu">
    {foreach from=$List item=ex name=menuList}
        <li {if $ex->isActive()}class="active"{/if}>            
            <a href="{$ex->getURL()|escape}">{$ex->getName()}</a>            
        </li>
    {/foreach}
</ul>
