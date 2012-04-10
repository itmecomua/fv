<form id="countriesHotelRef" class="form_b">
    <fieldset  class="fbh_6"  >
        <legend>
            Название
        </legend>
        <label>
            <input type="text" class="_decimal" name="params[hotel_name]" value="{$params.hotel_name}">
        </label>
    </fieldset>        
    <fieldset class="fbh_8" >        
        <legend>
            Курорт
        </legend>
        <label>
            {html_options options=$listResort selected=$params.resort_id name="params[resort_id]"}
        </label>
    </fieldset>        
    <fieldset class="fbh_8" >               
        <legend>
            Категория
        </legend>            
        <label>
            {html_options options=$listHotelType selected=$params.hotel_type_id name="params[hotel_type_id]"}
        </label>
    </fieldset>        
    <fieldset class="fbh_7" >               
        <legend class="fbh_5">
            &nbsp;
        </legend>            
        <label>
            <div class="twc_r">
                <div class="but1">        
                    <a href="/countries/route/showhotel" onclick="javascript: getDataByRoute( document.location.hash, document.location.href, jQuery('#countriesHotelRef').serialize() ); return false;">Поиск</a>
                </div>
            </div>                
        </legend>
    </fieldset>        
</form>
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

            <a target="_blank" class="block_c_title" href="{$hotel->getViewURL()}" title="{$hotel->getName()|escape}">
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
            
            {* hesh-AJAX переход 
            <a  class="block_c_title hachAjaxButton _curenthotelview " 
                href="#{$hotel->getViewURL()|replace:'/hotels/view/':''}" 
                title="{$hotel->getName()|escape}"
                id="{$hotel->getViewURL()}"
            >
                {$hotel->getName()} 
            </a>
             /hesh-AJAX переход *}
            
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

            <a target="_blank" href="{$hotel->getViewURL()}" title="{$hotel->getName()|escape}">Подробнее</a>                    

            {* AJAX переход 
            <a  href="javascript: void(0);" 
                onclick="javascript:getViewURLbyAJAX('{$hotel->getViewURL()}');" 
                title="{$hotel->getName()|escape}"
            >
                Подробнее 
            </a>
             /AJAX переход *}            
            {* hesh-AJAX переход 
            <a  href="javascript: void(0);" 
                onclick="document.location.hash = '{$hotel->getViewURL()|replace:'/hotels/view/':''}'" 
                title="{$hotel->getName()|escape}"
            >
                Подробнее 
            </a>
             /hesh-AJAX переход *}
            
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
                jQuery('#country-showhotel').html(data);
                jQuery('#country-showhotel').removeClass('_loaded');
                var instanceOne = new ImageFlow();
                instanceOne.init({ ImageFlowID: 'gallery_hotel' });

                
            }
        });
    }
{/literal}
</script>