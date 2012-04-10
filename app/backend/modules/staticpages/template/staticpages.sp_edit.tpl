<FORM method="post" action="/backend/staticpages/save/" id="sp_form">
<div class="form">
    <H1>Статическая страница</H1>
    <div class="operation"><a href="{$fvConfig->get('dir_web_root')}staticpages/" onclick="go('{$fvConfig->get('dir_web_root')}staticpages/'); return false;" class="left">вернутся к списку</a><div style="clear: both;"></div></div>
<div>
    <table class="form">                        
        <tr>
            <td>
                <label for="name">Имя</label> 
            </td>
            <td>
                <input type="text" id="name" name="sp[name]" value="{$StaticPage->getName()}" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="title">Тайтл</label> 
            </td>
            <td>
                <input type="text" id="name" name="sp[title]" value="{$StaticPage->getTitle()}" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="content">Содержимое</label> 
            </td>
            <td>
                {fckeditor name="sp[content]"
                           id="_content"
                           height="400px"
                           width="100%"
                           text=$StaticPage->content}
            </td>
        </tr>
        <tr>
            <td>
                <label for="tech_url">URL</label> 
            </td>
            <td>
                <input type="text" id="tech_url" name="sp[tech_url]" value="{$StaticPage->tech_url}" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" name="sp[is_system]" value="1" id="is_system" {if $StaticPage->is_system}checked="true"{/if}><label for="is_system" class="checkbox">системная страница (страница, которая не будет отображаться в общем списке)</label> <br />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label>
                    Отображать заголовок страницы ?
                </label>
                <div id="show_title" >            
                    Да
                    <input  type="radio" 
                        name="sp[show_title]" 
                        value="1"
                        {if $StaticPage->show_title}checked="true"{/if} 
                        />
                    Нет
                    <input  type="radio" 
                        name="sp[show_title]" 
                        value="0"
                        {if !$StaticPage->show_title}checked="true"{/if} 
                        />
                </div>
            </td>
        </tr>
        
    </table>
    <div class="buttonpanel">
        <input type="submit" name="save" value="Сохранить" class="button"  onclick="$('redirect').value = '';">
        <input type="submit" name="save_and_return" value="Сохранить и выйти" class="button" onclick="$('redirect').value = '1';">
    </div>
    <input type="hidden" name="id" id="id" value="{$StaticPage->getPk()}" />
    <input type="hidden" id="redirect" name="redirect" value="" />
</div>

<div style="clear: both;" />
</div>
</FORM>