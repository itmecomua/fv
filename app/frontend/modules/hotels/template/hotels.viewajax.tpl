<div class="block_inf_b">
    <div class="inf_wrp">
        <div class="inf_top_plc">
            <h2 class="block_c_title">
                {$hotel->getName()}
            </h2>
        </div>
        <div class="h_pad1">
            <a class="title_a" href="{$hotel->getCountry()->getViewURL()}">
            {$hotel->getCountry()->getName()}
            </a>       
            :
            <span class="title_b">
            {$hotel->getResort()->getName()}
            </span>
        </div>
    </div>

<div id="gallery_hotel" class="imageflow">
{foreach from=$hotel->getPhotoGallery() item="Gallery"}
    <img src="{$Gallery->getImageSrc(true,'IMAGE_TYPE_NORMAL')}" alt="{$Gallery->getImageSrc(true,'IMAGE_TYPE_LARGE')}" >
{/foreach}
</div>
    
    <div class="star stars_{$hotel->getHotelType()->getName()}">Тип отеля</div>
    <div class="h_pad1">
{*
        <div class="artical_img_wraper">
            <img src="{$hotel->getMainPhoto()->getImageSrc('true' , IMAGE_TYPE_NORMAL)}" alt=""/>
        </div>
*}            
        <div class="artical_inf">   </div>
        <div class="artical_full_txt static_page">
            {$hotel->getFullText()}
        </div>
        <div class="artical_full_txt static_page">
            просмотров: {$hotel->getCntView()} 
        </div>
        
    </div>
</div>
