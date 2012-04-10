<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<h2>Горящие предложения</h2>
			</div>
		</div>
	</div>
	<div class="block_a_bd">

    <div class="simple_container">
    {foreach from=$HotOffer item=offer}
        <div class="block_c_wrap">                
            <a  class="block_c_title" 
                href="{$offer->getURL()}" 
                title="{$offer->getName()|escape}"
                {if $offer->isTarget()}
                target="_blank"
                {/if}
            >
                {$offer->getName()}
            </a>
            <div class="block_c_img_wrap">
                <a  href="{$offer->getURL()}" 
                    title="{$offer->getName()|escape}"
                    {if $offer->isTarget()}
                    target="_blank"
                    {/if}                    
                >
                    <img src="{$offer->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$offer->getName()|escape}" />
                </a>
            </div>
            <div class="block_c_inf">{$offer->getShortText()}</div>
        </div>
    {/foreach}
    </div>



{if $HotOffer->hasPaginate()}
<div class="pager">
    {$HotOffer->showPager(false,"/hotoffer/list/")}
</div>
{/if}



	</div>
</div>