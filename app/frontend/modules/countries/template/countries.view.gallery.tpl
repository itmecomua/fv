<div id="gallery" class="simple_border video m_p10">
        {if $country->getGalleryPhoto()}
        <div id="photo-wrapper">
            <div id="myImageFlow" class="imageflow">
                {foreach from=$country->getGalleryPhoto() item=photo}
                <img src="{$photo->getImageSrc(true,'IMAGE_TYPE_NORMAL')}" alt="{$photo->getImageSrc(true,'IMAGE_TYPE_LARGE')}" />
                {/foreach}
            </div>
        </div>
        {/if}
</div>

{*
{foreach from=$country->getGalleryPhoto() item=photo}
    <img src="{$photo->getImageSrc(true,'IMAGE_TYPE_NORMAL')}" title="{$photo->getName()|escape}" />
{/foreach}
*}