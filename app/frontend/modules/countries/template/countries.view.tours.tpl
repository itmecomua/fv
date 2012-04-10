<div id="countriesViewTour">

<form id="countriesTourRef" class="form_b " >

<fieldset class="fbh_1" >
    <legend>
        Тип тура
    </legend>
    <label>
        {html_options options=$tourType selected=$params.type_id name="params[type_id]"}<br />
    </label>
</fieldset>
<fieldset class="fbh_2" >
    <legend>
        Дата начала тура  
    </legend>
    <div class="form_b_data">
        <span>с </span> <input type="text" class="_datepicker" id="dateTourFr" name="params[date_start_fr]" value="{$params.date_start_fr}">
        <span>по</span> <input type="text" class="_datepicker" id="dateTourTo" name="params[date_start_to]" value="{$params.date_start_to}">
    </div>
</fieldset>
<fieldset class="fbh_3" >
    <legend>
        Стоимость до
    </legend>
    <label class="form_b_mid">
        <input type="text" class="_decimal" name="params[price_from]" value="{$params.price_from}">
    </label>
</fieldset>
<fieldset class="fbh_4" >
    <legend class="fbh_5">
        &nbsp;
    </legend>
    <label>

<div class="twc_r">
    <div class="but1">
        <a href="/countries/route/showtour" onclick="javascript: getDataByRoute( document.location.hash, document.location.href, jQuery('#countriesTourRef').serialize() ); return false;">Поиск</a>
    </div>
</div>    
    
        
    </label>
</fieldset>
        
</form>

    {foreach from=$tours item=tour name=toursforeach}
    <div class="block_inf_a">
        <div class="img_wrap">
            {if $tour->getImportURL()}
                <a class="block_c_title" id="dialog_{$smarty.foreach.toursforeach.iteration}" ref="javascript:void(0);" onclick="javascript:getImportTour('{$tour->getImportURL()}');" title="{$tour->getName()|escape}">
            {else}
                <a href="{$tour->getViewURL()}" title="{$tour->getName()|escape}">
            {/if}
                <img src="{$tour->getMainPhoto()->getImageSrc('true', IMAGE_TYPE_SMALL)}"  alt="{$tour->getName()|escape}" />
                
            </a>
        </div>
        <div class="inf_wrp">
    {*
            <div class="inf_top_plc">
            <a class="block_c_title" href="{$tour->getViewURL()}" title="{$tour->getName()|escape}">{$tour->getName()}</a>
            </div>
    *}
            {if $tour->getImportURL()}
                    <a class    = "block_c_title" 
                       id       = "dialog_{$smarty.foreach.toursforeach.iteration}"
                       href     = "javascript:void(0);" 
                       onclick  = "javascript:getImportTour('{$tour->getImportURL()}');"
                       title    = "{$tour->getName()|escape}"
                    >
                        {$tour->getName()}
                    </a>
                    
            {else}
                <a                  class="block_c_title" href="{$tour->getViewURL()}" title="{$tour->getName()|escape}">{$tour->getName()}</a>        
            {/if}

            <span class="title_r">
               {* Цена *} от {$tour->getPrice()} {* + валюта *} {$tour->getCurrency()}
            </span>
            <div class="inf_txt">
                {$tour->getShortText()}
            </div>
            <ul class="list_d h_pab">
            <li>
                Кол-во просмотров: {$tour->getCntView()}
            </li>
            <li>
                Даты заездов  {$tour->getDates(",","m.d")}
            </li>
            <li>
                Продолжительность {$tour->getDuration()}
            </li>
            </ul>
        </div>
    </div>
    {/foreach}
    {if $tours->hasPaginate()}
    <div class="pager">    
        {* $tours->showPagerAjax(false,$pagerURL) *}
        {$tours->showPagerAjaxV2(false, 'DoShowPagerCountriesAjax' , $params.country_id) }
    </div>
    {/if}
    <script type="text/javascript">
    {literal}
    function DoShowPagerCountriesAjax(params)
    {
        var ref = jQuery('#countriesTourRef').serialize();
        var url = "/countries/tour"+(ref?'?'+ref:'');
        jQuery('#countriesViewTour').html('').addClass('ajax_preloader');        
        jQuery.ajax({
            type    : "post",
            url     : url,
            data    : {                    
                        href: document.location.href,
                        country_id: params.param_country_id,
                        page: params.param_curent_page,
                      },
            success : function(data,text,xhr)
                      {
                        jQuery('#countriesViewTour').html(data).removeClass('ajax_preloader');
                      }    
        });

    }
    (function($){
        var box = '#dateTourFr';
        var boxTo = '#dateTourTo';
        var self = this;
        this.dateRender =  function(date)
        {
            var YEAR = date.getFullYear();
            var MONTH = (date.getMonth() < 9) ? "0"+(date.getMonth()+1) : (date.getMonth()+1);
            var DAY = (date.getDate() < 10) ? "0"+ date.getDate() : date.getDate();
            var str = DAY + "." + MONTH + "." + YEAR; 
            
            return str;
        }
        beforeShowTo = function() {            
            return {
                    minDate: (
                        jQuery(box).datepicker("getDate")
                        ? jQuery(box).datepicker("getDate")
                        : jQuery.datepicker.parseDate("dd/mm/yy", "01/01/2012") )                    
            };
        };
        var beforeShowFr = function() {
            return {
                minDate: (jQuery.datepicker.parseDate("dd.mm.yy", self.dateRender(new Date())))                    
            }; 
        }
        var _onSelectFr = function() {
           var dateFr = jQuery(box).datepicker("getDate");
           var dateTo = jQuery(boxTo).datepicker("getDate");
           
           if (dateFr && dateTo){
                if (dateFr.getTime() > dateTo.getTime()) {
                    jQuery(boxTo).datepicker("setDate",dateFr)
                }
           }
           
        }
        $(box).datepicker({
            beforeShow: beforeShowFr,
            onSelect: _onSelectFr
        }); 
        $(boxTo).datepicker({
            beforeShow: beforeShowTo
        });    
    })(jQuery);
    {/literal}            
    </script>

    {*
    скрипт для открывания "popap" окна аккорд-тур
    *}
    <div class="dialog_container" ></div>
    <script type="text/javascript">
    {literal}
        jQuery(function() {
            jQuery(".dialog_container").dialog({
                autoOpen: false,
                width: 980,
                height: 400,
            });
        });

        function getImportTour(LinkToTour)
        {
            var TourContainer = '<iframe src="'+ LinkToTour +'"></iframe>';
            jQuery( ".dialog_container" ).html(TourContainer);
            jQuery( ".dialog_container" ).dialog( "open" );
            return false;
        }     

    {/literal}
    </script>
</div>