<ul>
{foreach from=$ListH item=ex name="depend"}
         <li>
            <span>
                <div class="item" >
                <input type="hidden" name="d[{$ex->getPk()}]" value="{$smarty.foreach.depend.iteration}" />
                    <em class="marker {if $ex->hasChild()}open{/if}"></em>{$ex->getName()}                
                <div style="float: right;">
                    <a href="{$fvConfig->get('dir_web_root')}{$module}/edit/?id={$ex->getPk()}" onclick="go('{$fvConfig->get('dir_web_root')}{$module}/edit/?id={$ex->getPk()}'); return false;">
                        <img src="{$fvConfig->get('dir_web_root')}img/edit_icon.png" title="редактировать" width="16" height="16">
                    </a>
                    <a href="javascript: void(0);" onclick="if(confirm('Вы действительно желаете удалить элемент?')) go('{$fvConfig->get('dir_web_root')}{$module}/delete/?id={$ex->getPk()}'); return false;">
                        <img src="{$fvConfig->get('dir_web_root')}img/delete_icon.png" title="удалить" width="16" height="16">
                    </a>            
                </div>
                </div>
            </span>
            {if $ex->hasChild()}
                {show_module module=$module view="index" type="h" parent_id=$ex->getPk() offset=$offsetPlus} 
            {/if}
         </li>             
{/foreach}
</ul>