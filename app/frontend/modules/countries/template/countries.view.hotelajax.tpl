{foreach from=$hotelList item=hotel}
<div class="block_inf_b">
    <div class="img_wrap">
            <a target="_blank" href="{$hotel->getViewURL()}" title="{$hotel->getName()|escape} :: Просмотр информации">
                <img  src="{$hotel->getMainPhoto()->getImageSrc(true,'IMAGE_TYPE_NORMAL')}" />
            </a>
    </div>
    <div class="inf_wrp">   
        <div class="inf_top_plc">
            {* обычный переход *}
            <a class="block_c_title" href="{$hotel->getViewURL()}" title="{$hotel->getName()|escape}">
                {$hotel->getName()} 
            </a>           
            {* AJAX переход 
            <a  class="block_c_title" 
                href="javascript: void(0);" 
                onclick="javascript:getViewURLbyAJAX('{$hotel->getViewURL()}');" 
                title="{$hotel->getName()|escape}"
            >
                {$hotel->getName()} 
            </a>
             /AJAX переход *}
        </div>
        <span class="title_a">
            {$hotel->getCountry()->getName()}
        </span>
        <div class="star stars_{$hotel->getHotelType()->getName()}">
            {$hotel->getHotelType()->getName()}
        </div>
        <span class="title_b">
            {$hotel->getResort()->getName()}
        </span>
        <div class="inf_txt">
            {$hotel->getShortText()}
        </div>
        <div class="but2 control1"> 
            {* обычный переход *}

            <a href="{$hotel->getViewURL()}" title="{$hotel->getName()|escape}">Подробнее</a>                    

            {* AJAX переход 
            <a  href="javascript: void(0);" 
                onclick="javascript:getViewURLbyAJAX('{$hotel->getViewURL()}');" 
                title="{$hotel->getName()|escape}"
            >
                Подробнее 
            </a>
             /AJAX переход *}            
        </div>
        
    </div>
    <div class="title_a h_cb h_pad4">
        просмотров: {$hotel->getCntView()}
    </div>    
</div>
{/foreach}
{if $hotelList->hasPaginate()}
<div class="pager">
    {$hotelList->showPagerAjax(false,'exCountry.getHotel')}
</div>
{/if}
<script type="text/javascript">
{literal}
    function getViewURLbyAJAX(tagretURL)
    {
        jQuery.ajax({
            url: tagretURL,
            success: function(data, textStatus, jqXHR){
                jQuery('#country-showhotel').html(data).removeClass('_loaded');
                
                var instanceOne = new ImageFlow();
                instanceOne.init({ ImageFlowID: 'myImageFlow_hotel' });
                
            }
        });
    }
{/literal}
</script>