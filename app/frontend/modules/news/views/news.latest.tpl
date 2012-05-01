{*
        <img src="{$iNews->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$iNews->getName()|escape}" />
        <a class="title_d" href="{$iNews->getIUrl()}">{$iNews->getName()}</a>
        {$iNews->getHeading()|escape|truncate:200:"..."}
        Просмотров: {$iNews->shows}  
        Дата: {$iNews->create_time|date_format:'%d.%m.%Y %H:%M'}
*}

            <div class="title_002">Новости</div>
            <div class="left_block_news">
                <div class="news_wrp">
                    <ul class="news_list">
                    {foreach from=$cNews item=iNews}
                        <li>
                            <div class="news_item_wrp">
                                <div class="news_item_img_wrp">
                                    <div class="news_item_img_holder">
                                        <img src="{$iNews->getImageSrc('true',IMAGE_TYPE_SMALLTWO)}"  alt="{$iNews->getName()|escape}" />
                                    </div>
                                </div>
                                <div class="news_item_info">
                                    <a class="title_01" href="{$iNews->getIUrl()}">{$iNews->getName()}</a>
                                    <div class="news_item_txt">
                                        <span>
                                            {$iNews->getHeading()|escape|truncate:200:"..."}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
