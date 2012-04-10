<table>
  <tr>
    <td id="leftpanel" valign="top">
        <div id="leftpaneldiv">
            <a href="javascript:void(0);" id="hidePanel"><img id="collapse" src="{$fvConfig->get('dir_web_root')}img/collapse.gif" width="15" height="15"></a>
            <div class="header" id="leftPanelHeader">Список страниц</div>
            <div id="leftpaneldata" style="height:600px; overflow-y:scroll;">
                {foreach item=onePage from=$Pages}
                    <div style="float: right">
        <A
           href="{$fvConfig->get('dir_web_root')}pages/?id={$onePage->getPk()}"
        onclick="go('{$fvConfig->get('dir_web_root')}pages/?id={$onePage->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16"></a>{if $onePage->page_name ne 'default'}<a
           href="javascript: void(0);"
        onclick="if (confirm('Вы действительно желаете удалить страницу. Все дочерние страницы перенесутся в корень.')) go('{$fvConfig->get('dir_web_root')}pages/delete/?id={$onePage->getPk()}'); return false;"
        ><img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16"></a>{/if}
                    </div><div>{if $onePage->page_parent_id}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if}{$onePage->page_name}</div><div style="clear: both;">
                {/foreach}

                <div class="operation">
                    <a href="{$fvConfig->get('dir_web_root')}pages/" onclick="go('{$fvConfig->get('dir_web_root')}pages/'); return false;" class="add">добавить</a>
                </div>
            </div>
        </div>
    </td>
    <td class="spacer">&nbsp;</td>
    <td id="datapanel">
<FORM method="post" action="/backend/pages/save/">
<div class="form">
    <H1>{if $Page->isNew()}Добавление страницы{else}Редактирование страницы '{$Page->page_name}'{/if}</H1>
    {if $Page->page_name eq 'default'}
    <p>Эта страница используется как базовая для задания основных параметров, таких как:</p>
    <ul class="num">
        <li>Основные мета-теги (если те не указаны в новых страницах.)</li>
        <li>Основные модули, которые будут присутствовать на новой странице</li>
    </ul>

    {else}
    <fieldset>
        <legend>Общая информация</legend>
        <table class="form">
        <tr><td style="width: 1px;">
        <label for="page_name">Название</label></td><td> <input type="text" id="page_name" name="p[page_name]" value="{$Page->page_name|escape}"/>
        </td></tr><tr><td>
        <label for="page_url">Урл страницы</label></td><td><input type="text" id="page_url" name="p[page_url]" value="{$Page->page_url|escape}" /> <br />        
        </td></tr><tr><td>
        <label for="page_parent_id">Родительская страница</label></td><td>
        {html_options name=p[page_parent_id] id=page_parent_id options=$PageManager->htmlSelect('page_name', "", "page_parent_id = 0 and page_name <> 'default' AND id <> ?", '', '', $Page->getPk()) selected=$Page->page_parent_id}
        </td></tr>
        </table>
    </fieldset>
    {/if}
    
    <fieldset>
        <legend>Javascript/CSS</legend>
        <table class="form" id="jscss">
        <tr>
            <td style="width: 50%;">
                <fieldset>
                    <legend>Javascript <a href='javascript:void(0);' onclick='javascript:window.addJS();' title='добавить javascript'><img src='/backend/img/add.png' /></a></legend>
                    <div id='jsbody'>{foreach from=$JS item=ex}<div><input style='width:80%;' type='text' name='p[js][]' value='{$ex|escape}'><a href='javascript:void(0);' onclick='javascript:jQuery(this).parent().html("");jQuery(this).parent().hide();' title='удалить'><img src='/backend/img/delete.png' /></a></div>{/foreach}</div>
                </fieldset>
            </td>
            <td style="width: 50%;">
                <fieldset>
                    <legend>CSS <a href='javascript:void(0);' onclick='javascript:window.addCSS();' title='добавить css'><img src='/backend/img/add.png' /></a></legend>
                    <div id='cssbody'>{foreach from=$CSS item=ex}<div><input style='width:80%;' type='text' name='p[css][]' value='{$ex|escape}'><a href='javascript:void(0);' onclick='javascript:jQuery(this).parent().html("");jQuery(this).parent().hide();' title='удалить'><img src='/backend/img/delete.png' /></a></div>{/foreach}</div>
                </fieldset>
            </td>
        </tr>
        </table>
    </fieldset>
    <fieldset>
        <legend>Мета-информация</legend>
        <table class="form">
        <tr>
            <td><label for="title">title</label></td>
            <td><input type="text" name="meta[title]" id="title" value="{$Page->getMeta()->getTitle()|escape}"></td>
        </tr>
        <tr>
            <td><label for="description">description</label></td>
            <td><textarea name="meta[description]" id="description">{$Page->getMeta()->getDescription()|escape}</textarea></td>
        </tr>
        <tr>
            <td><label for="keywords">keywords</label></td>
            <td><textarea name="meta[keywords]" id="keywords">{$Page->getMeta()->getKeywords()|escape}</textarea></td>
        </tr>
     
        
        </td>        
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

    <div class="operation">
        <a href="javascript: void(0);" class="content_edit" id="content_edit"
            onclick=""
        >управление содержимым страницы</a>
        <br clear="all">
    </div>

    <div class="buttonpanel">
        <input type="submit" name="save" value="Сохранить" class="button">
    </div>
    <input type="hidden" name="id" id="id" value="{$Page->getPk()}" />
    <input type="hidden" name="p[page_content]" id="page_content" value="{if !$Page->isNew()}{$Page->getPageContent()|escape}{/if}" />
</div>
</FORM>
    </td>
  </tr>
</table>
<div id='jscont' style='display:none;'><div><input style='width:80%;' type='text' name='p[js][]'><a href='javascript:void(0);' onclick='javascript:jQuery(this).parent().html("");jQuery(this).parent().hide();' title='удалить'><img src='/backend/img/delete.png' /></a></div></div>
<div id='csscont' style='display:none;'><div><input style='width:80%;' type='text' name='p[css][]'><a href='javascript:void(0);' onclick='javascript:jQuery(this).parent().html("");jQuery(this).parent().hide();' title='удалить'><img src='/backend/img/delete.png' /></a></div></div>
<div style="display: none" id="headers_content">
    <div class="popup_content">
        <a href="javascript: void(0)">SERVER_NAME</a> - заголовок статической страницы<br />
    </div>
</div>

{literal}
<script>
    Object.extend(window, {
        insertArea: '',
        
        addJS: function()
        {
            var elem = document.createElement("div");
            elem.innerHTML = $("jscont").innerHTML;
            jQuery("#jsbody").append(elem);
        },
        
        addCSS: function()
        {
            var elem = document.createElement("div");
            elem.innerHTML = $("csscont").innerHTML;
            jQuery("#cssbody").append(elem);
        }
    });

    function moveLeftPanel (e) {
        if ($('leftpanel').getDimensions().width > 100) {
            $('leftpanel').morph('width: 20px;');
            $('leftPanelHeader').update("");
            $('leftpaneldata').hide();
            $('collapse').src = '{/literal}{$fvConfig->get('dir_web_root')}img/expand.gif{literal}';
        } else {
            $('leftpanel').morph('width: 300px;');
            $('collapse').src = '{/literal}{$fvConfig->get('dir_web_root')}img/collapse.gif{literal}';
            setTimeout("$('leftPanelHeader').update('Список страниц')", 1000);
            setTimeout("$('leftpaneldata').show()", 1000);
        }
    }

    var helpwindow = new PopUpWindow({
        width: 400,
        height: 300,
        center: true,
        title: "заголовки",
        name: 'headers',
        buttons: ['cancel'],
        contentData: $('headers_content').innerHTML,
        zIndex: 120
    });

    $$("table#metatags tr td a").each(function (link){
        link.observe("click", function (evt) {
            helpwindow.show();
        });
    })

    $$('div.popup_content a').each(function (link) {
        link.observe('click', function (evt) {
            elem = Event.element(evt);
            helpwindow.close();
            if ($(window.insertArea)) {
                $(window.insertArea).value = $F(window.insertArea) + '%' + elem.innerHTML + '%';
            }
        })
    });

    var wnd = new PopUpWindow({
        width: 800,
        height: 600,
        center: true,
        url: '/backend/',
        title: "управление содержимым",
        name: 'content_edit',
        zIndex: 100,
        onShow: function (params) {            
            new Ajax.Updater('content_edit_content', '{/literal}{$fvConfig->get('dir_web_root')}pages/contentedit/{literal}', {
                parameters: 
                {
                    _xmlContent: $F("page_content") || '{/literal}{$Page->getPageContent()|replace:"\n":""}{literal}'.replace("&lt;?xml version=&quot;1.0&quot;?&gt;", "&lt;?xml version=&quot;1.0&quot;?&gt;\n")
                },
                evalScripts: true
            });
        },
        onOk: function (params) {
            $('page_content').value = $F('_xmlContent');
        }
    });

    $('leftpaneldiv').setStyle({
        height: (document.viewport.getHeight() - $('header').getDimensions().height - 25) + "px"
    });

    $('hidePanel').observe('click', moveLeftPanel);
    $('content_edit').observe('click', wnd.show.bind(wnd));
</script>
{/literal}