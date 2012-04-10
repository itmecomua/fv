<div style="clear: both;">&nbsp;</div>
<div class="form">
    <form method="post" action="{$fvConfig->get('dir_web_root')}{$module}/savephoto">
    <fieldset>
        <legend>{if $inst->isNew()}Добавление фотографий {else}Редактирование фотографий "{$inst->getName()}" {/if}</legend>
        <div class="toggle_content"> 
            <div id="photos-container">
            {foreach from=$inst->getPhoto() item=iPhoto}
                <fieldset class="photo" id="_photo_{$iPhoto->getPk()}" style="width: 40%; float: left; margin: 15px;">
                    <div class="table_body">
                        <label>Название</label>
                        <input type="text" name="photo[{$iPhoto->getPk()}][name]" value="{$iPhoto->getName()}" style="width: 200px;" />
                        <br />
                        <label for="is_main{$iPhoto->getPk()}">Главное</label>
                            <input id="is_main{$iPhoto->getPk()}" type="radio" {if $iPhoto->isMain()}checked="checked"{/if} onclick="javascript: doSetMain({$iPhoto->getPk()})" name="dosetmain" value=""/>
                        <br />
                        <label>Вес</label>
                        <select name="photo[{$iPhoto->getPk()}][weight]" style="width: 80px;">
                            {html_options output=$wt values=$wt selected=$iPhoto->weight}
                        </select>
                        <br />
                        <label>Тип</label>
                        <select name="photo[{$iPhoto->getPk()}][type_id]" style="width: 80px;">
                            {html_options options=$iPhoto->getManager()->getListMediaType() selected=$iPhoto->getTypeId()}
                        </select>
                        <br />
                        <label>&nbsp;</label>
                        <img src="{$iPhoto->getImageSrc()}" style="height: 100px;" />                
                    </div>                                
                    <div class="operation">  
                        <a style="float: right;" href="javascript:void(0)" class="delete" onclick="javascript:doDelete({$iPhoto->getPk()});">удалить</a>
                    </div>
                    <input type='hidden' name='photo[{$iPhoto->getPk()}][image]' value='{$iPhoto->image}'>
                    
                </fieldset>
            {/foreach}
            </div>
            <div id="file-upload"></div>
        </div>
    </fieldset>
        <div class="buttonpanel"> 
            <input type="button" onclick="javascript:handlerForm.init(this.form, '.result-edit-media-form').ajax(getEditPhoto);" class="ui-button ui-corner-all ui-state-default" value="Сохранить">    
            <span>*настройки веса и главного изображения доступны после сохранения</span>
            <input type="hidden" name="id" id="id" value="{$inst->getPk()}"/>            
        </div>          
    </div> 
 </div>       
</form>
<div class="result-edit-media-form" style="display: none;"></div>

<style>
{literal}
    div#photos-container{
        overflow: hidden;
    }
    div.photo{
        float: left;
        margin: 5px;
        border-radius: 4px;
        padding: 5px;
        border: 1px solid #777;
    }
    div.photo>a{
        display: block;
        font-size: 11px;
        text-decoration: none;
        border-bottom: 1px dotted #777;  
        color: #777;
        width: 41px;
        font-family: Georgia;
        margin: 0 auto;
    }
    div.photo>img{
        max-width: 150px;
    }
{/literal}
</style>

<script language="JavaScript">
    <!--
    {literal}
    jQuery(function($){
    $("div#file-upload").uploadify(
        {
            'uploader'  : '/js/uploader/uploadify.swf',
            'script'    : '/js/uploader/uploadify.php',
            'cancelImg' : '/js/uploader/cancel.png',
            'folder'    : '/{/literal}{$tmpDir}{literal}',
            'auto'      : true,                                             
            'multi'     : true,
            'sizeLimit' : 55000000,
            'fileDesc'  : 'Image File *.jpg;*.jpeg;*.gif;*.png;',
            'fileExt'   : '*.jpg;*.jpeg;*.gif;*.png;',
            onSelect: function (event, queueID, fileObj)
            {
                showActionMessage("Пожалуйста, подождите","info");
            },
            onComplete: function( event, queueID, fileObj, responce, data )
            {   
                $("#contentblocker").fadeOut();
                
                var div = $("<div class='photo'></div>");
                $("<img src='/upload/tmp/"+responce+"'>").appendTo( div );
                $("<a href='javascript:void(0);' class='delete' onclick='javascript:jQuery(this).parent().remove();'>удалить</a>").data('new','new').appendTo( div );
                var tempId = "temp"+ parseInt(Math.random()*10000);
                
                $("<input type='hidden' name='photo[][image]' value='"+responce+"'>").appendTo( div );
                
                div.appendTo( $("div#photos-container") );
                showActionMessage("Изображение загружен","success");
            },
            onError: function( event, queueID, fileObj, responce, data )
            { 
                $(".contentblocker").fadeOut();
                showActionMessage("Нам очень жаль, но произошла ошибка","error");
            }

        });  
        
    });
    doSetMain =  function (id) 
    {
        window.blockScreen();
        var data = {id : id, hid : '{/literal}{$inst->getPk()}{literal}'};      
        new Ajax.Updater(
        "result", 
        "{/literal}{$fvConfig->get('dir_web_root')}{$module}/dosetmain{literal}", 
        {
            parameters: data,                    
            onComplete: function(transport){window.completeRequest(transport);window.parseForms();},
        });
    };
    
    doDelete =  function (id) 
    {
        if(!confirm('Вы действительно желаете удалить изображение?'))
            return false;
        window.blockScreen();
        var data = {id : id};      
        new Ajax.Updater(
        "result", 
        "{/literal}{$fvConfig->get('dir_web_root')}{$module}/dodeleteimage{literal}", 
        {
            parameters: data,                    
            onComplete: function(transport){
                    window.completeRequest(transport);
                    jQuery("#_photo_"+id).fadeOut('fast', function(){
                       jQuery(this).remove();
                    });
            },
        });
    };
    {/literal}
    //-->
</script>