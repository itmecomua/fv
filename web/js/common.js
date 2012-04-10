function checkedElementToString(box,delimiter)
    {
        var arr = new Array();
        jQuery(box + " input:checkbox:checked").each(function(){
            arr.push(jQuery(this).val());
        });
        
        if(arr.length)
            return arr.join(delimiter);
        return 0;
    }
    
function onChangeCity(value)
{
    jQuery("#searchcity_from_id option").removeAttr("selected");
    jQuery("#StartDirect_input option").removeAttr("selected");
    
    jQuery("#searchcity_from_id option[value='"+value+"']").attr('selected','selected');
    jQuery("#StartDirect_input option[value='"+value+"']").attr('selected','selected');
    
    jQuery(jQuery("#searchcity_from_id").parent()).find("span.jNiceSelectText").html(jQuery("#searchcity_from_id option[value='"+value+"']").html());
    jQuery(jQuery("#StartDirect_input").parent()).find("span.jNiceSelectText").html(jQuery("#StartDirect_input option[value='"+value+"']").html());
    
    //jQuery("#searchcity_from_id").val(value);
    //jQuery("#StartDirect_input").val(value);
    if (typeof DirectionBalloon !== 'undefined') DirectionBalloon.select();
}
function onChangeDate(val)
{    
    var date = jQuery("td.Day[date='"+val+"']")
    if (date.size()) {
        date.click();
    } else {
        jQuery(".DateLabel").html('');
        DirectionBalloon.hide();
    }
    
}
function onSelectHotel(event,ui)
{
    //jQuery("#ssHotel").val(ui.item.hotelName);
    window.location.href = "/hotel/view/" + ui.item.pk;     
}



function onSelectRegion(event,ui)
{    
    jQuery("#region_id").val(ui.item.value);
    jQuery("#region_selected").val(ui.item.pk);
    jQuery("#EndDirect_input").focus().val(ui.item.value);
    jQuery("#region_selected_type").val(ui.item.typeid);
    if (typeof DirectionBalloon !== 'undefined') DirectionBalloon.select();
}
function onFocusRegion(event,ui)
{    
    jQuery("#region_id").val(ui.item.value);
    jQuery("#region_selected").val(ui.item.pk);
    jQuery("#EndDirect_input").val(ui.item.value);
    jQuery("#region_selected_type").focus().val(ui.item.typeid);
    if (typeof DirectionBalloon !== 'undefined') DirectionBalloon.select();
}

function onSelectFormHotel(event,ui)
{
    //jQuery("#hotel_id").val(ui.item.id);    
    window.location.href = "/hotel/view/" + ui.item.pk;     
}

function autoCompleteClose (event)
{
    
}
function doSelectHotelType(elem, id)
{
    if(jQuery("#"+id).attr("checked"))
    {
        jQuery(elem).removeClass("selected");
    }
    else
    {
        jQuery(elem).addClass("selected");
    }
}
function setPreload(selector,isMini){ 
    var gif = isMini ? '/img/miniload.gif' : '/img/mwait2.gif';
    jQuery(selector).html('<img src="' + gif + '"/>').show(); 
}
handlerForm = function(selector,selectorMessage,selectorData,isMini) {
   this.selector = selector;   
   this.selectorMessage = selectorMessage || '';
   this.selectorData = selectorData || '';
   this.isMini = isMini || false;      
}
handlerForm.init = function(selector,selectorMessage,selectorData,isMini){
    var obj = new handlerForm(selector,selectorMessage,selectorData,isMini);
    return obj;
}
handlerForm.prototype = {        
    $ : function(selector) { 
        if (typeof selector != 'undefined')
            return jQuery(selector);
        return jQuery; 
        
    },
    serialize : function ()
    {
        return this.$(this.selector).serialize();
    },
    confirm: function (dlog) {
        var self = this;
        self.$(dlog).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {                
                "Отмена": function() {
                    jQuery( this ).dialog( "close" );
                },
                "Выполнить": function() {
                    jQuery( this ).dialog( "close" );
                    self.ajax();                    
                }
            }
        });
    },
    ajax : function(onSuccess) {         
        
        var self = this;
        self.setPreload()                              
        this.$(this.selector).find('*').removeClass("ui-state-error");
        if(!self.formValidate()) {
            self.setMsg(self.getErrorMsg('Некоторые поля заполнены некорректно. Пожалуйста, проверьте ввод данных'));
            return false;   
        }
        
        this.$().ajax({url: this.$(this.selector).attr('action'),
                  cache: false,type: "POST",
                  data: self.serialize(),
                  async: false,
                  success: function(data,status,xhr)
                  {
                      self.onSuccessReq(self,data,status,xhr)
                      if (typeof onSuccess == 'function') {
                          onSuccess(self);
                      }
                  }
              });
    },
    getInfoMsg : function(message) {
            var msg = '';
            msg += '<div style="margin-top: 5px;" class="ui-state-highlight ui-corner-all">';
            msg += '<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>';
            msg += '<strong style="display: block;">Информация</strong>' + message + '</p></div>';
            return msg;
    },
    getErrorMsg : function(message) {
            var msg = '';
            msg += '<div style="margin-top: 5px;" class="ui-state-error ui-corner-all">';
            msg += '<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>';
            msg += '<strong style="display: block;">Ошибка</strong>' + message + '</p></div>';
            return msg;
    },
    setMsg : function(message) {
         this.$(this.selectorMessage).html(message);         
    },
    setData : function(data) {
        this.$(this.selectorData).html(data);         
    },
    setValidationResult : function(elem,is_valid,message)
    {
        if(is_valid) {
            jQuery(elem).removeClass("ui-state-error");
            jQuery(elem).attr("title","");
        } else {
            jQuery(elem).addClass("ui-state-error");
            jQuery(elem).attr("title",message);
        }    
        return is_valid;    
    },
    onSuccessReq : function (self,data,status,xhr) {
         
         var captcha = typeof xhr.getResponseHeader('captcha') == "string" ? eval(xhr.getResponseHeader('captcha')) : '';
         var exception = typeof xhr.getResponseHeader('exception') == "string" ? eval(xhr.getResponseHeader('exception')) : '';
         var message = typeof xhr.getResponseHeader('message') == "string" ? eval (xhr.getResponseHeader('message')) : '';
         self.getLocation = typeof xhr.getResponseHeader('getLocation') == "string" ? eval (xhr.getResponseHeader('getLocation')) : '';
         var validation =  '';
         if (typeof xhr.getResponseHeader('validation') == "string" && xhr.getResponseHeader('validation').length > 0) {
             eval('validation = ' + xhr.getResponseHeader('validation'));
         }         
         var redirect = typeof xhr.getResponseHeader('redirect') == "string" ? eval(xhr.getResponseHeader('redirect')) : '';
         if(validation != '') {
            for(var i in validation) {
                this.setValidationResult('#'+i, false, validation[i]);                             
            }
         }
         
         var msg = '';
         if (message != '' && typeof message != 'undefined') {
            msg += this.getInfoMsg(message);
         }
         if (exception != '' && typeof exception != 'undefined') {
            msg += this.getErrorMsg(exception);
         }
         if (redirect != '' && typeof redirect != 'undefined') {
             window.location.href = redirect;
         }
         if (captcha != '' && typeof captcha != 'undefined') {
             this.$("._captcha").html(captcha);
         }
         
         this.setMsg(msg);
         this.setData(data);         
    },
    setPreload : function(){ 
        
        var gif = this.isMini ? '/img/miniload.gif' : '/img/mwait2.gif';
        this.$(this.selectorMessage).html('<img src="' + gif + '"/>').show(); 
    },
    formValidate: function()
    {
        var self = this;
        var valid = true;
        self.$(self.selector).find(".notEmpty").removeAttr("title");
        self.$(self.selector).find(".notEmpty").removeClass("ui-state-error");
        self.$(self.selector).find(".notEmpty").each(function(){
            if(self.$(this).is('input'))
                if(self.$(this).val() <= 0)
                {
                    self.$(this).addClass("ui-state-error");                    
                    self.$(this).attr("title", "Некорректно введены данные");
                    valid = false;
                }            
            if(self.$(this).is('textarea'))
                if(self.$(this).val() <= 0)
                {
                    self.$(this).addClass("ui-state-error");
                    self.$(this).attr("title", "Некорректно введены данные");
                    valid = false;
                }
            if(self.$(this).hasClass('isEmail'))            
            {
                var email = self.$(this).val();                
                    var re = new RegExp('[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z0-9]{2,4}');
                    if(!re.exec(email))
                    {
                        self.$(this).addClass("ui-state-error");                    
                        self.$(this).attr("title", "Некорректно введены данные");
                        valid = false;
                    }                                
            }
        });
        self.$(self.selector).find(".isEmail").each(function(){            
            
                var email = self.$(this).val();
                if (email.length > 0) {
                    var re = new RegExp('[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z0-9]{2,4}');
                    if(!re.exec(email))
                    {
                        self.$(this).addClass("ui-state-error");                    
                        self.$(this).attr("title", "Некорректно введены данные");
                        valid = false;
                    }                
                }
            });
        self.$(self.selector).find(".isInn").each(function(){  
                var inn = self.$(this).val();
                var re = new RegExp('[0-9]{9}');
                if(inn && !re.exec(inn))
                {
                    self.$(this).addClass("ui-state-error");                    
                    self.$(this).attr("title", "Поле должно содержать 9 цифр от 0 до 9");
                    valid = false;
                } 
        });
        return valid;
    }
    
}

Online = function()
{
    var self = this;
}
Online.prototype = 
{
    module: '',
    getOnline: function()
    {
       var self = this; 
       jQuery.post('/'+self.module+'/getonline', function(r,s,xhr){
           self.showDialog(r);
       }); 
    },
    getHistory: function(date)
    {
        var self = this;
        self.showLoad(true);
        jQuery.post('/'+self.module+'/gethistory', {date:date}, function(r,s,xhr){
            self.showLoad(false);
            jQuery("#box_day_history").html(r);
        });          
    },
    showDialog: function(html)
    {
        jQuery("<div title='Статистика онлайн'>" + html + "</div>").appendTo("body").dialog({
            modal:true,
            width:600,
            height:600,
            resizable: false,
            close: function(){jQuery(this).remove()}
        });
    }, 
    initDate: function(box)
    {
        var self = this;
        jQuery(box).datepicker({
            dateFormat: 'dd.mm.yy',
            maxDate: "+1D",
            showAnim: "clip",
            onSelect:  function(dateText, inst)
            {
                self.getHistory(dateText);
            }
        })
    },
    showLoad: function(trigger)
    {
        if(trigger)
            jQuery("#box_day_history").html("<img id='loadTrig' src='/img/mwait2.gif' />");
        else {
            jQuery("#loadTrig").remove();
        }
    },
    viewMore: function(id, a)
    {
        jQuery(".view_more_info_" + id).slideToggle('slow', function(){
        if(jQuery(".view_more_info_" + id).css('display') == 'block')
            jQuery(a).text("Скрыть");
        if(jQuery(".view_more_info_" + id).css('display') == 'none')
            jQuery(a).text("Подробнее...");
        });
    },
    getCountOnline: function()
    {
        var self = this;
        jQuery.post('/' + self.module + '/getcountonline', function(r,s,xhr){
           eval('var count = ' + xhr.getResponseHeader('countOnline') );
           count = typeof count != 'undefined' ? count : 0;
           jQuery("#linkUserCount").html("Пользователей онлайн: " + count); 
        });
    }
}

_online = new Online();



//Сохранение данных по аяксу
(function($) {
    $.fn.formAjax = function(options) {

        var defaults = {
            callback: function(){ window.location.href="/order/"; }
        };

        var opts = $.extend(defaults, options);

        this.find( "input, textarea" ).focus(function(){
            $(this).removeClass( "error" ).attr({title: ''});
        });

        this.submit(function(){
            var iForm = jQuery( this );

            jQuery.ajax({
                url: iForm.attr( 'action' ),
                type: iForm.attr( 'method' ),
                data: iForm.serialize(),
                success: function( data, status, xhr )
                {
                    try
                    {
                        iForm.find( ".error" ).removeClass( "error" );

                        var h = jQuery.parseJSON( xhr.getResponseHeader( "actionmessage" ) );
                        if ( h.type == "error" )
                            {
                            var v = jQuery.parseJSON( xhr.getResponseHeader( "X-JSON" )  );
                            for ( var field in v  )
                                {
                                iForm.find( "#"+field ).addClass( "error" ).attr({ title: v[field] });
                            }

                            jQuery( "<p>" + h.message + "</p>" ).splash({header: "Ошибка"});
                        }
                        else
                            {
                            jQuery( "<p>" + h.message + "</p>" ).splash({header: "Сохранено", callback: opts.callback( iForm ) });
                        }
                    }
                    catch( e )
                    {
                        jQuery( "<p>Во время выполнения возникла ошибка.</p>" ).splash({header: "Ошибка"});   
                    }
                    $( "form#subscribe-form .placeholder" ).placeholder();  
                }
            });

            return false;
        });
    };    
})(jQuery); 

//Всплывающее окошко
(function($) {
    $.fn.splash = function(options) {

        var defaults = {
            header: "",
            callback: function(){}
        };

        var opts = $.extend(defaults, options);
        var self = this;

        var wrapper = jQuery( "<div class='splash'><p>"+opts.header+"</p><p>"+this.html()+"</p><a class='close' href='javascript:void(0)'>Закрыть</a></div>" );
        wrapper.find( "a.close" )
        .click(function(){
            jQuery(this).parents( "div.splash" ).fadeOut( "300", function(){
                $(this).remove();  
            })
            opts.callback();
        });

        wrapper.appendTo( jQuery( "body" ) ).fadeIn( "200" );
        window.setTimeout( function(){
            if (wrapper)
                {
                wrapper.fadeOut( "300", function(){
                    $(this).remove();
                    opts.callback();    
                });
            }
        }, 5000 );

    };    
})(jQuery);

//Рыбина для тектовых полей
(function($) {
    $.fn.placeholder = function(options) {

        var defaults = {
        };

        var opts = $.extend(defaults, options);
        this.each(function(){
            $(this).focus(function(){
                if( $(this).val() == $(this).attr( 'alt' ) )   
                    $(this).val('').removeClass( "inactive" );
            });

            $(this).blur(function(){
                if ( $(this).val().length == 0 )
                    $(this).val( $(this).attr( 'alt' ) ).addClass( "inactive" );    
            });

            $(this).parents("form").eq(0).submit(function(){
                $(this).find(".placeholder").each(function(){
                    if( $(this).val() == $(this).attr( 'alt' ) )   
                        $(this).val('');    
                }); 
            });

            $(this).blur();
        });

        return this;

    };
})(jQuery);

jQuery(document).ready(function(){
    if (typeof jQuery(".placeholder").placeholder != "undefined")
    jQuery(".placeholder").placeholder();
});

(function ($){
    $.fn.regexMask = function (mask) {
        if (!mask) {
            throw 'mandatory mask argument missing';
        } else if (mask == 'float-ptbr') {
            mask = /^((\d{1,3}(\.\d{3})*(((\.\d{0,2}))|((\,\d*)?)))|(\d+(\,\d*)?))$/;
        } else if (mask == 'float-enus') {
            mask = /^((\d{1,3}(\,\d{3})*(((\,\d{0,2}))|((\.\d*)?)))|(\d+(\.\d*)?))$/;
        } else {
            try {
                mask.test("");
            } catch(e) {
                throw 'mask regex need to support test method';
            }
        }
        $(this).keypress(function (event) {
            if (!event.charCode) return true;
            var part1 = this.value.substring(0,this.selectionStart);
            var part2 = this.value.substring(this.selectionEnd,this.value.length);
            if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
                return false;
        });
    };    
})(jQuery);

jQuery(document).ready(function(){
    if (typeof jQuery('._maskDigital').regexMask != "undefined")
    jQuery('._maskDigital').regexMask(/^\d+$/);
});


/**Init Hint*/
Hints = function()
{
    var self = this;
}
Hints.prototype = 
{
    mime_id : "hint_",
    mime_class : "_hint",
    width: 700,
    height: 500,
    init : function()
    {
        var self = this;
        jQuery("[id*='"+self.mime_id+"']").each(function(){
              jQuery(this).addClass(self.mime_class);
              jQuery(this).click(function(){
                    var length = (self.mime_id).length;
                    var hint_id = jQuery(this).attr("id").substr(length);                
                    self.show(hint_id);
              });
          });        
    },
    show : function(hint_id)
    {
        var self = this;
        var d = self.showDialog(hint_id);
        if(d)
        {
            jQuery.post('/hint', {hint_id : hint_id} , function(r){
               d.html(r); 
            });
        }
    },
    showDialog : function(hint_id)
    {
        var self = this;
        var existDialog = jQuery("#_hint_info_" + hint_id).length;
        if(existDialog)
        {
           jQuery("#_hint_info_" + hint_id).dialog('open');
           return false;
        }
        var newDialog = jQuery("<div id='_hint_info_" + hint_id +"'></div>" ).html("<img src='/img/mwait2.gif' />");
        newDialog.dialog({
            width: self.width,
            height: self.height,
            resizable: false,
            modal: false
        });
        return newDialog;
    }
    
}
var _hint = new Hints();
jQuery(function(){
    _hint.init();
});

CRB = function()
{
    var self = this;
}
CRB.prototype = 
{
    path : "/index/crb",
    classBox : "._crb",
    reload : function()
    {
        jQuery( "#banner" ).fadeTo(0, 0);
        jQuery( "#banner" ).tabs("destroy");
        var self = this;
        jQuery.post(self.path, function(r){
            jQuery(self.classBox).html(r);
            jQuery( "#banner" ).fadeTo(0, 0);
            jQuery( "#banner" ).tabs();
            jQuery( "#banner" ).fadeTo(1000, 1);
        });       
    }
}
_crb = new CRB();
    
    