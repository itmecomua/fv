fvCountry = function(){}
fvCountry.prototype = {
    
    /**
    * Имя класса информационных блоков
    */
    infoBoxClass: ".countryinfo",
    
    /**
    * Имя класса табов
    */
    tabsBoxClass: ".countrytabs",    
    
    /**
    * URL для получение отелей
    */
    getHotelsURL: "/countries/gethotels",
    
    /**
    * ID блока отелей
    */
    hotelBox: "#hotel",
    
    /**
    * ID текущей страны
    */
    countryKeyBox: "#country-key",
    
    /**
    * Отобразить блок информации
    */
    viewInfo: function(box,elem)
    {
        var self = this;
        
        jQuery(self.tabsBoxClass).removeClass("activeTabs");
        jQuery(elem).addClass("activeTabs");
        
        jQuery(self.infoBoxClass).hide();            
        
        if(jQuery("#" + box).hasClass("unload"))
        {
            if(box == 'hotel') self.getHotel(0);
            jQuery("#" + box).removeClass("unload")
        }
        jQuery("#"+box).show();
    },
    
    getHotel: function(page)
    {
      var self = this; 
      
      jQuery.ajax({
            url: self.getHotelsURL,
            cache: false,
            type: "POST",
            data: { country_id: jQuery(self.countryKeyBox).val(), page: page},
            async: false,
            success: function(data,status,xhr)
            {
               jQuery(self.hotelBox).html(data);               
            }
        });
    }
    
};
exCountry = new fvCountry();