<h1>{$fvConfig->getModuleName()}</h1>
<div class="h_clear">
    <h2>{if $ex->isNew()}Добавление{else}Редактирование{/if}</h2>
</div>
<div class="h_clear">
    {* Кнопка вернутся к списку *}
    {$fvModule->getReturn()}
    {* /Кнопка вернутся к списку *}
</div>
<div class="form">
<form method="post" action="/backend/{$module}/save/">
    <div class="form_stage">
        <label>Скидка</label>
        <input  name="m[discount]" 
                id="discount" 
                value="{$ex->getDiscount()}" 
                type="text" 
                />
    </div>
	<div class="form_stage">
        <label>Имя:</label>
        <input 	name="m[name]" 
                id="name" 
                value="{$ex->getName()|escape}" 
                type="text" 
                />
    </div>
    <div class="form_stage">
        <label>Короткое описание</label>
        <textarea   name="m[short_text]" 
                    id="short_text" 
                    >{$ex->getShortText()}</textarea>
    </div>
    <div class="form_stage">
        <label>Старая цена:</label>
        <input  name="m[priceold]"  
                id="priceold"    
                value="{$ex->getPriceold()}"  
                type="text" 
                />
    </div>
    <div class="form_stage">
        <label>Новая цена цена:</label>
        <input  name="m[pricenew]"  
                id="pricenew"    
                value="{$ex->getPricenew()}"  
                type="text" 
                />
    </div>    
    <div class="form_stage">
        <label>URL:</label>
	    <input 	name="m[url]"  
                id="url"    
                value="{$ex->getURL()}"  
                type="text" 
                />
    </div>
    <div class="form_stage">
        <label>Показывать ?</label>
	    Да
        <input 	name="m[is_show]"   
                value="1" 	   
                type="radio"  
                {if $ex->isShow()}
                checked="checked" 
                {/if} 
                />
        Нет
	    <input  name="m[is_show]"   
                value="0" type="radio"	
                {if !$ex->isShow()}
                checked="checked" 
                {/if}  
                />
    </div>
    <div class="form_stage">
        <label>Открывать в новом окне ?</label>
	    Да
        <input 	name="m[is_target]" 
                value="1" 
                type="radio" 
                {if $ex->isTarget()}
                checked="checked" 
                {/if}  
                />
	    Нет
        <input 	name="m[is_target]" 
                value="0" 
                type="radio" 
                {if !$ex->isTarget()}
                checked="checked" 
                {/if} 
                />
    </div>
    <div class="form_stage">
        <label>Вес </label>
        {html_options output=$wt values=$wt name="m[weight]" selected=$ex->getWeight()}
    </div>   
    <fieldset>
            <legend>Изображение</legend>
            <input  name="m[image]" 
                    id="image" 
                    value="{$ex->image}" 
                    type="text" 
                    readonly="readonly" 
                    />            
            <div id="mainimage"         ></div>
            <div class="progressbar"    ></div>
            <div id="mainimage_result"  ></div>
            <div id='box_image'         >
                {if $ex->getImageSrc() && !$ex->isNew()}
                    <img src="{$ex->getImageSrc(true)}"  /> 
                {/if}
            </div>
    </fieldset>
    <div class="buttonpanel">
	    <input  name="save" 			
                value="Сохранить" 				
                type="submit"	
                />
	    
        <input	name="id"
                id="id" 		
          		value="{$ex->getPk()}" 			
                type="hidden"	
                />
	    <input 	name="redirect"	
                id="redirect" 	 		
                value="" 						
                type="hidden"	
                />
    </div>
</form>
</div>
<br>
<div id='buffer' style='display:none;'></div>
<br>

<script type="text/javascript"> 
{literal}
    
    jQuery(document).ready(function(){
        /*Init Main Image*/
        jQuery('#mainimage').uploadify(
        {
                'uploader'  : '/js/uploader/uploadify.swf',
                'script'    : '/js/uploader/uploadify.php',
                'cancelImg' : '/js/uploader/cancel.png',
                'fileExt'     : '*.jpg;*.gif;*.png',
                'fileDesc'    : 'Web Image Files (.JPG, .GIF, .PNG)',
                'folder'    : '/{/literal}{$tmpDir}{literal}',
                'auto'      : true,

                onProgress: function (event, queueID, fileObj,data)
                {
                    jQuery( ".progressbar" ).progressbar({
                        value: data.percentage,
                         complete: function(event, ui) { 
                             jQuery(this).progressbar({ disable:true });
                             jQuery(this).children('div').html(data.percentage + "%");
                             jQuery(this).children('div').css('text-align', 'center');
                         },
                         change: function(event, ui) { 
                             jQuery(this).children('div').html(data.percentage + "%");
                             jQuery(this).children('div').css('text-align', 'center');
                         }
                    });
                },
                onSelect: function (event, queueID, fileObj)
                {
                        jQuery('#mainimage').fileUploadStart(queueID);                
                },
                onComplete: function(event, queueID, fileObj, responce, data )
                {
                    jQuery("#buttonpanel").fadeIn("slow");
                    $('image').value = responce;
                    var oImg = document.createElement("img");
                    oImg.setAttribute('src', '{/literal}{$tmpDir}{literal}/'+responce);
                    oImg.setAttribute('style', 'border:2px solid #B7DDF2; padding:10px;');
                    $('box_image').innerHTML="";
                    $('box_image').appendChild(oImg);                                
                }
            });
            
        jQuery('#mainflash').uploadify(
            {
                'uploader'  : '/js/uploader/uploadify.swf',
                'script'    : '/js/uploader/uploadify.php',
                'cancelImg' : '/js/uploader/cancel.png',
                'fileExt'     : '*.swf;',
                'fileDesc'    : 'Web Flash Files (.swf)',
                'folder'    : '/{/literal}{$tmpDir}{literal}',
                'auto'      : true,

                onProgress: function (event, queueID, fileObj,data)
                {
                    jQuery( ".progressbar" ).progressbar({
                        value: data.percentage,
                         complete: function(event, ui) { 
                             jQuery(this).progressbar({ disable:true });
                             jQuery(this).children('div').html(data.percentage + "%");
                             jQuery(this).children('div').css('text-align', 'center');
                         },
                         change: function(event, ui) { 
                             jQuery(this).children('div').html(data.percentage + "%");
                             jQuery(this).children('div').css('text-align', 'center');
                         }
                    });
                },
                onSelect: function (event, queueID, fileObj)
                {
                        jQuery('#mainflash').fileUploadStart(queueID);                
                },
                onComplete: function(event, queueID, fileObj, responce, data )
                {
                    jQuery("#buttonpanel").fadeIn("slow");
                    $('flash').value = responce;                    
                }
            });

    });
    
    Object.extend(window, {
        doGenerateUrl: function(res)           
        {
            var ajax = new Ajax.Updater(
            "buffer",
            "{/literal}{$fvConfig->get('dir_web_root')}transliterate/generateurl{literal}",
            {
                parameters : {name: $("title_ru").value},
                asynchronous:true,
                onComplete: function () 
                {

                    $(res).value = $("buffer").innerHTML;
                }
            }
            );
        }
        ,doGenerateUrl: function(res)           
        {
            var ajax = new Ajax.Updater(
            "buffer",
            "{/literal}{$fvConfig->get('dir_web_root')}transliterate/generateurl{literal}",
            {
                parameters : {name: $("name").value},
                asynchronous:true,
                onComplete: function () 
                {
                    $(res).value = $("buffer").innerHTML;
                }
            }
            );
        }
    
    });
{/literal}
</script> 