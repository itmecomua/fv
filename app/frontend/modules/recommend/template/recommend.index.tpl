                <div class="title_001">Мы рекомендуем</div>
                <div class="center_conteinet_02">
                    <ul class="center_conteinet_02_list">
                        {foreach from=$Recommend item=reco}
                        <li>
                            <div class="center_conteinet_02_item_wrp">
                                <div class="center_conteinet_02_item_img_wrp">
                                    <div>
                                        <h4 title="{$reco->getName()|escape}">{$reco->getName()|escape}<span></span></h4>
                                        
                                    </div>
                                    <div class="center_conteinet_02_img_holder">
                                        <div>
                                            <a  href="{$reco->getURL()}" 
                                                title="{$reco->getName()|escape}"
                                                {if $reco->isTarget()}
                                                target="_blank"
                                                {/if}                    
                                                >
                                                <img src="{$reco->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$reco->getName()|escape}" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="center_conteinet_02_star{$reco->getStars()}"></div>
                                    <div class="center_conteinet_02_txt_01">
                                        {$reco->getShortText()}
                                    </div>
                                    <div class="center_conteinet_02_txt_02">
                                        <span>{$reco->getDuration()}</span>
                                        <b>{$reco->getPrice()}</b>
                                    </div>
                                </div>
                                
                            </div>
                        </li>
                       {/foreach}
                    </ul>
                </div>
{if $Recommend->hasPaginate()}
<div class="pager">
    {$Recommend->showPager(false,"/Recommend/list/")}
</div>
{/if}