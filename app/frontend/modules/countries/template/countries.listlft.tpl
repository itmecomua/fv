                        <div class="left_menu_01">
                            <div class="list_a_wrap">
                                <ul>
                                    {foreach from=$countries item=country name="listlft"}    
                                    <li {cycle values='class="list_a_l",class="list_a_r"'}>
			                            <a class="block_c_title" href="{$country->getViewURL()}" title="{$country->getName()|escape}">
				                            {$country->getName()}
			                            </a>
		                            </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>