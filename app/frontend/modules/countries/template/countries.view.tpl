    <div class="block_a_wrap">
	<div class="block_a_lft">
		<div class="block_a_rt">
			<div class="block_a_md">
				<a href="/countries">Все страны</a>
			</div>
		</div>
	
    </div>
	<div class="block_a_bd">

<div class="tabs_a_wrap h_pad3" id="tabs-header-country">
    <ul>
        <li>
            <a class="countrytabs first activeTabs hachAjaxButton _showinfo" href="#showinfo" id="/countries/route/showinfo">
                <span class="countrytabs_txt " >{$country->getName()}</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs hachAjaxButton _showtour" href="#showtour" id="/countries/route/showtour">
                <span class="countrytabs_txt " >Туры</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs  hachAjaxButton _showviza" href="#showviza" id="/countries/route/showviza">
                <span class="countrytabs_txt " >Визы</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
        <li>
            <a class="countrytabs last hachAjaxButton _showhotel " href="#showhotel" id="/countries/route/showhotel">
                <span class="countrytabs_txt " >Отели</span>
                <span class="countrytabs_selected">выбраный пункт</span>
            </a>
        </li>
    </ul>
</div>

            
        <div id="tabs-country">
            <div class="countryinfo static_page h_pad3 content-tab _showinfo _loaded" id="_showinfo">
                {include file='countries.view.gallery.tpl'}
                {include file='countries.view.info.tpl'}
            </div>
            <div class="countryinfo static_page h_pad3 content-tab _showtour" id="_showtour" style="display: none;">
                {* include file='countries.view.tour.tpl' *}
            </div>
            <div class="countryinfo static_page h_pad3 content-tab _showviza _loaded" id='_showviza' style="display: none;">
                {include file='countries.view.viza.tpl'}
            </div>
            <div class="countryinfo static_page h_pad3 unload content-tab _showhotel" id='_showhotel' style="display: none;">
                {* include file='countries.view.hotel.tpl' *}
            </div>
            <div class="countryinfo static_page h_pad3 unload content-tab _curenthotelview" id='_curenthotelview' style="display: none;">
                {* место для конкретного отеля *}
            </div>            
        </div>
            <input type="hidden" value="{$country->getPk()}" id='country-key' />
        

	</div>
</div>

<script type="text/javascript"> 
{literal}
      checkAnchor = function () {
        var hash = document.location.hash;
        var mtch = hash.match(/\#[^\?]*/);        
        hash =  mtch != null ? mtch[0] : "";
        if ( (typeof hash=='string')&&(hash!=hashBuffer) ) {

            hashBuffer=hash;
            getDataByRoute( hash , document.location.href );
        }
    };
    
    hashBuffer = "";
    setInterval("checkAnchor()", 300);
    
    function getDataByRoute( hash , href, ref )
    {
      
            // снимаем "старый" активный пункт меню ( снимаются все активные подчистую )
        jQuery("#tabs-header-country .countrytabs").removeClass("activeTabs");
        
            // прячем "старый" контейнер с информацией ( прячутся все контейнеры подчистую )
        jQuery("#tabs-country .content-tab").hide();
        jQuery("#tabs-country").find('.temporaly').hide();
        
            // указатель
        var pointer     = hash.replace('#', ""), 
        
            // Контейнер для помещения в него информации
            cont_trg    = jQuery('#_' + pointer ),

            // узнаем url для перехода
            url_trg     = jQuery("a[href='#"+pointer+"']").attr('id'),          

            // узнаем id табсов
            tabs        = jQuery("#tabs-country"),
            
            // узнаем id текущей страны
            country_key = jQuery("#country-key").val();
            
        if( cont_trg.length == 0 )
        {
            
            $temporaly  = '<div class="temporaly" id="_'+pointer+'"></div>';
            jQuery("#tabs-country").append($temporaly);
            cont_trg    = jQuery('#_' + pointer );
        }

            //если информация еще не была ни разу загружена то ...
        if ( ! cont_trg.hasClass('_loaded') || ref )  
        {
            tabs.addClass('ajax_preloader');
            jQuery.ajax(
            {
                type    : "post",
                url     : url_trg + (ref?'?'+ref : '' ),
                data    : {
                            hash        : hash,
                            pointer     : pointer,
                            fullurl     : url_trg,
                            country_id  : country_key,
                            page        : 0,                            
                          },
                success : function(data,text,xhr)
                          {
                            cont_trg.html(data);
                            cont_trg.addClass('_loaded');
                            tabs.removeClass('ajax_preloader');
                          },
            });
        }
        jQuery('.tabs_a_wrap').find("._"+pointer).addClass("activeTabs");
        cont_trg.show();        
    }        
/*    
jQuery(document).ready(function(){
    jQuery('.hachAjaxButton').click(function(){
        jQuery()
    });
});
*/
{/literal} 
</script>
