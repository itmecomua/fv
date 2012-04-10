<div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
                <a href="/news" alt="" >Новости</a>
			</div>
		</div>
	</div>
	<div class="block_a_bd">

<ul class="NWS h_p25">
     {foreach from=$cNews item=iNews}
        <li class="border_simple" >
            <div class="title_g">
                <a href="{$iNews->getIUrl()}">{$iNews->getName()}</a>
            </div>
            <p class="n_txt_inf_a">
                {$iNews->getHeading()|escape|truncate:200:"..."}
            </p>
            <div class="view">
                <span>Просмотров: {$iNews->shows}</span>
                <span>Дата: {$iNews->create_time|date_format:'%d.%m.%Y %H:%M'}</span>
            </div>
        </li>
    {/foreach}
</ul>

{if $cNews->hasPaginate()}
<div class="pager">
        {$cNews->showPager()}
</div>
{/if}



	</div>
</div>

