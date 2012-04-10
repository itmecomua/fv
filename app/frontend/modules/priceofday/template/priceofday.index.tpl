    {* Получить скидку 
    {$priceofday->getDiscount()}
    *}

    {* Получить заголовок 
    {$priceofday->getName()}
    *}

    {* Получить короткий текст 
    {$priceofday->getShortText()}
    *}

    {* Получить старую цену 
    {$priceofday->getPriceold()}
    *}

    {* Получить новую цену 
    {$priceofday->getPricenew()}
    *}

    {* Получить путь к изображению 
    {$priceofday->getImageSrc()}
    *}

    {* Получить URL 
    {$priceofday->getURL()}
    *}

    {* Получить вес 
    {$priceofday->getWeight()}
    *}

    {* Отображать? 
    {$priceofday->isShow()}
    *}

    {* Открывать в новом окне? 
    {$priceofday->isTarget()}
    *}
                <div class="wrp_day_price">    
                {foreach from=$PriceOfDay item=priceofday}
                    <div class="{cycle values='day_price_1,day_price_2'}">
                        <div class="day_price_item_img_holder">
                            <div>
                                <a  href="{$priceofday->getURL()}"  
                                    title="{$priceofday->getName()|escape}" 
                                    {if $priceofday->isTarget()} target="_blank"{/if} 
                                    >
                                    <img src="{$priceofday->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$priceofday->getName()|escape}" />
                                </a>
                            </div>
                        </div>
                        <div class="day_price_txt">
                            <div class="day_price_txt_01">
                                {* Получить скидку  *}
                                {$priceofday->getDiscount()}%
                            </div>
                            <div class="day_price_txt_02">
                                <b>
                                    {* Получить заголовок *}
                                    {$priceofday->getName()}
                                </b> 
                                {* Получить короткий текст *}
                                {$priceofday->getShortText()}
                            </div>
                            <div class="day_price_txt_03">
                                {* Получить старую цену *}
                                {$priceofday->getPriceold()}
                            </div>
                            <div class="day_price_txt_04">
                                <b>
                                    {* Получить новую цену *}
                                    {$priceofday->getPricenew()}
                                </b>
                            </div>
                        </div>
                    </div>
                {/foreach}
                </div>
<div class="tour_search"><a href="/tours"></a></div>
{* пейджер *}
{*
{if $HotOffer->hasPaginate()}
<div class="pager">
    {$HotOffer->showPager(false,"/hotoffer/list/")}
</div>
{/if}
*}
{* /пейджер *}
