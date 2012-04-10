<script type="text/javascript">
{literal}
    jQuery(window).load(function() {
        jQuery('#sliderLeft').nivoSlider();
    });
{/literal}
</script>
<div class="slider_ins slider_big">
    <div class="ribbon"></div>
    <div id="sliderLeft"  class="nivoSlider">
        {foreach from=$list item=ex}                                                             
            <div class="nv_i" >
                <img src="{$ex->getImageSrc('true', IMAGE_TYPE_CRBLEFT)}"  alt="{$ex->getName()}"/>
                {if $ex->getText()}<div class="nivo-caption"><p>{$ex->getText()}</p></div>{/if}
            </div>
        {/foreach}            
    </div>
</div>