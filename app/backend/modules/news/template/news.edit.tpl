<h1>Новости.</h1>
<FORM method="post" action="/backend/{$module}/save/">
    <div class="form" style="padding-top:10px;">
        <h4>{if $ex->getPk()}Редактирование{else}Добавление{/if} новости</h4>
        {if $ex->getPk()}<h4>Добавлена {$ex->create_time|date_format:"%d.%m.%Y %H:%M"}. Просмотров: {$ex->shows}</h4>{/if}

        <div class="operation" width="100%">
            <a href="javascript:void(0);" onclick="go('{$fvConfig->get('dir_web_root')}{$module}'); return false;" class="left">вернуться к списку</a><div style="clear: both;"></div>
        </div>
        <fieldset>
            <legend>Инфмормация</legend>
            <label>Название</label>
            <input type="text" name="m[name]" id="name" value="{$ex->getName()|escape}" />
            <br />
            <label>Заголовок</label>
            <input type="text" name="m[heading]" id="heading" value="{$ex->getHeading()|escape}" />
            <br />
            <label>Описание</label>            
            {fckeditor name="m[text]" id="text" text=$ex->getText() width="100%" height="350"}            
        </fieldset>
        <fieldset>
            <legend>Общие параметры</legend>
            <label for="url">URL:</label>
            <input maxlength="255" type="text" id="url" name="m[url]" style="margin-bottom: 0px;" value="{$ex->url|escape}">
            <br />
            <a href='javascript:void(0);' onclick="javascript:window.doGenerateUrl('url');">
                <p style='font-size: 10px; color:#dddddd; margin-left: 210px; margin-bottom: 3px;'>сгенерировать URL по названию.</p>
            </a>

            <label for='ves'>Вес</label>
            <select name='m[weight]' id='weight' style="width: 405px; margin-bottom: 0px;">{html_options output=$weights values=$weights selected=$ex->weight}</select>
            <br/>
            <p style='font-size: 10px; color:#dddddd; margin-left: 210px; margin-bottom: 5px; margin-bottom: 5px;'>*учавствует при сортировке вывода. Чем меньше вес, тем раньше выводится.</p>  


            <label for='is_active'>Отображается</label>
            <input type="checkbox" name="m[is_active]" id="is_active" {if $ex->is_active}checked="checked"{/if}><br /><br />
            <label for='is_promo'>Промо</label>
            <input type="checkbox" name="m[is_promo]" id="is_promo" {if $ex->is_promo}checked="checked"{/if}><br /><br />
        </fieldset>                               
        <fieldset>
            <legend>Мета информация</legend>
            <table class="form">
                <tr>
                    <td><label for="title">title</label></td>
                    <td><input type="text" name="meta[title]" id="title" value="{$ex->getMeta()->getTitle()|escape}"></td>
                </tr>
                <tr>
                    <td><label for="description">description</label></td>
                    <td><textarea name="meta[description]" id="description">{$ex->getMeta()->getDescription()|escape}</textarea></td>
                </tr>
                <tr>
                    <td><label for="keywords">keywords</label></td>
                    <td><textarea name="meta[keywords]" id="keywords">{$ex->getMeta()->getKeywords()|escape}</textarea></td>
                </tr>
            </table> 
        </fieldset> 
        <fieldset>
        <legend>Теги</legend>
        <table class="form">
            <tr>
                <td style="width: 1px;">
                {foreach from=$metaManager->getListTag() key=_tag item=_name}
                    %{$_tag}% : {$_name}<br />
                {/foreach}        
                </td>        
            </tr>        
        </table>                                                     
        </fieldset>
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
            <br />
            <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value= '';">
            <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
            <input type="hidden" name="id" id="id" value="{$ex->getPk()}"/>
            <input type="hidden" id="redirect" name="redirect" value="" />
        </div>          
    </div>    
    <div style="clear: both;" />
    </div>
</FORM>


<div id='buffer' style='display:none;'></div>
<script> 
    {literal}
    Object.extend(window, {
        doGenerateUrl: function(res)           
        {
            var ajax = new Ajax.Updater(
            "buffer",
            "{/literal}{$fvConfig->get('dir_web_root')}index/generateurl{literal}",
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
{/literal}
</script> 


