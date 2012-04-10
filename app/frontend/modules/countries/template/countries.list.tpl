<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<h2>Все страны</h2>
                
                <div class="gotocountry">
                    <script type="text/javascript">
                    {literal}
                    function gotocountry(elem)
                    {
                        window.location = elem.options[elem.selectedIndex].value;
                    }
                    {/literal}
                    </script>                        
                    <select onChange="gotocountry(this)">
                    {*
                    {foreach from=$countries item=country}
                        <option value="{$country->getViewURL()}">{$country->getName()|escape}</option>
                    {/foreach}
                    *}                    
                    
                    {html_options values=$full_list_country_url output=$full_list_country_name}
                    
                    </select>
                    
                </div>
                
			</div>
		</div>
	</div>
	<div class="block_a_bd">

    <div class="simple_container">
    {foreach from=$countries item=country}
    <div class="block_c_wrap">
            <a class="block_c_title" href="{$country->getViewURL()}" title="{$country->getName()|escape}">
                {$country->getName()}
            </a>
            <div class="block_c_img_wrap_s">
                <a href="{$country->getViewURL()}" title="{$country->getName()|escape}">
                    <img src="{$country->getMainPhoto()->getImageSrc('true',IMAGE_TYPE_SMALLONE)}"  alt="{$country->getName()|escape}" />
                </a>
            </div>
            <div class="block_c_inf">
                {$country->getShortText()}
            </div>

{* нету в дизайне
            <div  class="block_c_inf_dop">
                Кол-во просмотров: {$country->getCntView()}
            </div>
*}

    </div>
    {/foreach}
    </div>



{if $countries->hasPaginate()}
<div class="pager">
    {$countries->showPager(false,"/countries/list/")}
</div>
{/if}



	</div>
</div>