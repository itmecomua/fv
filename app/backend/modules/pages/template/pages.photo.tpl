    <div class="toggle_content"> 
        
        <div id="photos-container">
            {if $Page->image}
            <div class="photo" id="{$Page->getPk()}">                
                <img src="{$Page->getImagePath()}" />
                <a href="javascript:void(0)" class="delete">удалить</a>
                <input type='hidden' name='m[image]' value='{$Page->image}'>
            </div>
            {/if}
        </div>
        <div id="file-upload"></div>
    </div>
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
        $("div#file-upload").fileUpload(
        {
            'uploader': '/js/uploader/uploader.swf',
            'script': '/upload.php',
            'folder': '/upload/tmp/',
            'cancelImg': '/js/uploader/cancel.png',
            'auto': true,
            'buttonImg': '/img/button_upload.png',
            'rollover': true,
            'width': 70,
            'height': 20,
            'multi': true,
            'sizeLimit': 55000000,
            'buttonText': 'View',
            'fileDesc': 'Image File *.jpg;*.jpeg;*.gif;*.png;',
            'fileExt': '*.jpg;*.jpeg;*.gif;*.png;',
            'displayData': '',
            onSelect: function (event, queueID, fileObj)
            {
                window.blockScreen();
                showActionMessage("Пожалуйста, подождите","info");
            },
            onComplete: function( event, queueID, fileObj, responce, data )
            {   
                $("#contentblocker").fadeOut();
                $("#photos-container").html('');
                var div = $("<div class='photo'></div>");
                $("<img src='/upload/tmp/"+responce+"'>").appendTo( div );
                $("<a href='javascript:void(0);' class='delete'>удалить</a>").data('new','new').appendTo( div );
                var tempId = "temp"+ parseInt(Math.random()*10000);
                
                $("<input type='hidden' name='p[image]' value='"+responce+"'>").appendTo( div );
                
                div.appendTo( $("div#photos-container") );
                showActionMessage("Изображение сохранено","success");
            },
            onError: function( event, queueID, fileObj, responce, data )
            { 
                $(".contentblocker").fadeOut();
                showActionMessage("Нам очень жаль, но произошла ошибка","error");
            }

        });  
        
        $("a.delete").live("click", function(){
            $("#photos-container").html('');
        });
    });
    {/literal}
    //-->
</script>