<script type="text/javascript">
{literal}
    jQuery(window).load(function() {
        jQuery('#sliderIndex').nivoSlider();
    });
{/literal}
</script>
<div class="slider_ins slider_big">
    <div class="ribbon"></div>
    <div id="sliderIndex" class="nivoSlider">
        {foreach from=$list item=ex}                                                             
            <img src="{$ex->getImageSrc('true',IMAGE_TYPE_CRBCENTER)}" alt="{$ex->getName()}"/>
            {if $ex->getText()}<div class="nivo-caption"><p>{$ex->getText()}</p></div>{/if}
        {/foreach}
    </div>
</div>