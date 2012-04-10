{*
getPhoto()
getMainPhoto()
*}
<div class="block_a_wrap">
    <div class="block_a_lft">
        <div class="block_a_rt">
            <div class="block_a_md">
                <h2>{$hotel->getName()}</h2>
                {* $hotel->getHotelType()->getName() *}
            </div>
        </div>
    </div>
    <div class="block_a_bd">
        <div class="h_pad1">
{*
<div class="artical_img_wraper">
    <img src="{$hotel->getMainPhoto()->getImageSrc('true' , IMAGE_TYPE_NORMAL)}" alt="" />
</div>
*}
<div id="gallery" class="simple_border video m_p10">
        {if $hotel->getPhotoGallery()}
        <div id="photo-wrapper">
            <div id="myImageFlow" class="imageflow">
                {foreach from=$hotel->getPhotoGallery() item=photo}
                <img src="{$photo->getImageSrc(true,'IMAGE_TYPE_NORMAL')}" alt="{$photo->getImageSrc(true,'IMAGE_TYPE_LARGE')}" />
                {/foreach}
            </div>
        </div>
        {/if}
</div>



<div class="artical_inf">
    <span class="int_title">страна:</span>
        <a class="int_inf" href="{$hotel->getCountry()->getViewURL()}">
            {$hotel->getCountry()->getName()}
        </a>       
</div>

<div class="artical_inf">
    <span class="int_title">курорт:</span>
    <span class="int_inf" >
            {$hotel->getResort()->getName()}
    </span>
</div>



<div class="artical_full_txt static_page">
    {$hotel->getFullText()}
</div>
<div class="artical_full_txt static_page">
просмотров: {$hotel->getCntView()} 
</div>
       

        </div>
    </div>
</div>






