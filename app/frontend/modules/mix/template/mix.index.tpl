                <div class="title_001">Сезонный микс</div>
                <div class="season_mix">
                    <ul class="season_mix_list">
                        {foreach from=$Mix item=mx}
                        <li>
                            <div class="season_mix_item_wrp">
                                <div class="season_mix_item_img_wrp">
                                    <div>
                                        <h4>
                                            {$mx->getName()|escape}
                                        </h4>
                                    </div>
                                    <div class="season_mix_price_in">
                                        <h3>{$mx->getPrice()}</h3>
                                    </div>
                                    <div class="season_mix_item_img_holder">
                                        <div>
                                            <a  href="{$mx->getURL()}" 
                                                title="{$mx->getName()|escape}"
                                                {if $mx->isTarget()}
                                                target="_blank"
                                                {/if}                    
                                                >
                                                <img src="{$mx->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$mx->getName()|escape}" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {/foreach}
                    </ul>
                </div>

{if $Mix->hasPaginate()}
<div class="pager">
    {$Mix->showPager(false,"/Mix/list/")}
</div>
{/if}