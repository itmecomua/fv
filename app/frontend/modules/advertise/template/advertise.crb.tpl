<script type="text/javascript">
{literal}
    jQuery(window).load(function() {
        //jQuery('#sliderCrb').nivoSlider();
        jQuery('#sliderCrb').nivoSlider( {
            effect:"fold",
            slices:10,
            boxCols:10,
            boxRows:10
        } );
    });
{/literal}
</script>
<div class="center_photo_wrap">
    <div class="center_photo">
        <div class="slider_ins slider_center">
            <div class="ribbon"></div>
            <div id="sliderCrb" class="nivoSlider">
                    {foreach from=$list item=ex}
                        {*<div class="nv_i" >*}
                            <a href="{$ex->getURL()}" {if $ex->isTargetBlank()}target="_blank"{/if}>
                                <img src="{$ex->getImageSrc('true',IMAGE_TYPE_CRBCENTER)}" alt="{$ex->getName()}"/>
                                {if $ex->getText()}<div class="nivo-caption"><p>{$ex->getText()}</p></div>{/if}
                            </a>
                        {*</div>*}
                    {/foreach}
            </div>
        </div>
    </div>
</div>