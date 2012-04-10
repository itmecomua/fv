{show_block file="click_1.tpl"}
<div class="block_a_wrap">
    <div class="block_a_lft">
        <div class="block_a_rt">
            <div class="block_a_md">
                <h2>{$tour->getName()}</h2>
            </div>
        </div>
    </div>
    <div class="block_a_bd">
        <div class="h_pad1">
        
<div class="twc">
    <div class="twc_l h_pad2">
        <div class="but1 active">
            <a onclick="javascript: window.location.href = '/tours/'">Все туры</a>
        </div>
    </div>
</div>
        

        
<div class="artical_img_wraper">
    <img src="{$tour->getMainPhoto()->getImageSrc('true' , IMAGE_TYPE_NORMAL)}" alt=""/>
</div>
<div class="artical_price">
    <span class="int_title">от : </span>
    <span class="int_inf"> {$tour->getPrice()} </span>
    <span class="int_title">{$tour->getCurrency()}</span>
    
</div>
<div class="artical_inf">
    <span class="int_title">Продолжительность:</span> 
    <span class="int_inf">  {$tour->getDuration()} </span>
    <span class="int_title">ночей</span>
</div>
<div class="artical_inf">
    <span class="int_title">типы:</span>
    {foreach from=$tour->getTourTypes() item=key}
        <a class="int_inf" href="{$key->getTourType()->getViewURL()}">
            {$key->getTourType()->getName()}
        </a>
    {/foreach}
</div>
<div class="artical_inf">
    <span class="int_title">страны:</span>
    {foreach from=$tour->getCountries() item=key}
        <a class="int_inf" href="{$key->getCountry()->getViewURL()}">
            {$key->getCountry()->getName()}
        </a>       
    {/foreach}
</div>
<div class="artical_inf">
    <span class="int_title">даты:</span>
    {foreach from=$tour->getDates() item=key}
        <span class="int_inf">{$key->getDateStart()}</span>
    {/foreach}
</div>
<div class="artical_full_txt static_page">
    {$tour->getFullText()}
</div>
{*
Фото:
<div style="margin: 10px; border: 1px solid #556677;">
    {foreach from=$tour->getPhoto() item=key}
        {$key->getImageSrc()}<br/>
    {/foreach}
</div>
*}
        </div>
    </div>
</div>
{show_module  module='ordertour' view='index'}





