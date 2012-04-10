<script type="text/javascript">
{literal}
    jQuery(window).load(function() {
        jQuery('#sliderRight').nivoSlider();
    });
{/literal}
</script>
<div class="slider_ins slider_big">
    <div class="ribbon"></div>
    <div id="sliderRight" class="nivoSlider">
        {foreach from=$list item=ex}                                                             
            <div class="nv_i" >
                <img src="{$ex->getImageSrc('true', IMAGE_TYPE_CRBRIGHT)}" alt="{$ex->getName()}"/>
                {if $ex->getText()}<div class="nivo-caption"><p>{$ex->getText()}</p></div>{/if}
            </div>
        {/foreach}            
    </div>
</div>