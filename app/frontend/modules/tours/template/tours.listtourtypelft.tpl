<div class="left_menu_01">
    <div class="list_a_wrap">
        <ul>    
        {foreach from=$tourtypes item=tourtype name="listtourtypelft"}    
            <li class="list_a_full" >
			    <a class="block_c_title" href="{$tourtype->getViewURL()}" title="{$tourtype->getName()|escape}">
				    {$tourtype->getName()}
			    </a>
		    </li>
        {/foreach}
        </ul>
    </div>
</div>