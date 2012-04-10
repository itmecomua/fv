<div class="menubox">
{foreach from=$menu item=ex name=menuItem}
    {if !$ex.active}
        {if $ex.url ne "#"}
			<a class="act" href="{$ex.url}"><span>{$ex.title}</span></a>
        {else}
			<a class="soon" href="{$ex.url}"><span style="color:#CCC;">{$ex.title}</span></a>
        {/if}
    {else}
			<a href="{$ex.url}"><span>{$ex.title}</span></a>
    {/if}
{/foreach}
<div class="cf"></div>
</div>
{foreach from=$submenu item=subEex name=submenuItem}
	{if $subEex.url ne "#"}
    	{if $subEex.active}
				<a href="{$subEex.url}" class="new_menu_bottom_active" style="background: gray; color: white;">{$subEex.title}</a>
	    {else}
				<a href="{$subEex.url}" >{$subEex.title}</a>
    	{/if}

	    {if $smarty.foreach.submenuItem.iteration != $submenu|@count}
				<span><br /></span>
    	{/if}
	{/if}
{/foreach}