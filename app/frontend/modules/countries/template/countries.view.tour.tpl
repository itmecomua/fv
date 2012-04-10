{foreach from=$tour item=tour}
<div class="block_inf_a">
    <div class="img_wrap">
        <a href="{$tour->getViewURL()}" title="{$tour->getName()|escape}">
            <img src="{$tour->getMainPhoto()->getImageSrc('true', IMAGE_TYPE_SMALL)}"  alt="{$tour->getName()|escape}" />
        </a>
    </div>
    <div class="inf_wrp">
        <div class="inf_top_plc">
        <a class="block_c_title" href="{$tour->getViewURL()}" title="{$tour->getName()|escape}">{$tour->getName()}</a>
        </div>
        <span class="title_r">
           {* Цена *} от {$tour->getPrice()} {* + валюта *} {$tour->getCurrency()}
        </span>
        <div class="inf_txt">
            {$tour->getShortText()}
        </div>
        <ul class="list_d h_pab">
        <li>
            Кол-во просмотров: {$tour->getCntView()}
        </li>
        <li>
            Даты заездов  {$tour->getDates(",","m.d")}
        </li>
        <li>
            Продолжительность {$tour->getDuration()}
        </li>
        </ul>
    </div>
</div>
{/foreach}

{if $tours->hasPaginate()}
<div class="pager">    
    {$tours->showPager(false,$pagerURL)}
</div>
{/if}