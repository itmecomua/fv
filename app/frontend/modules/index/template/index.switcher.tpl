{foreach from=$Langs item=lang key=key}
    <a href="{$fvConfig->get('dir_web_root')}{$key}{$url}" {if $Lang->getCurLang() == $key}class="active"{/if}>{$lang.name}</a>
{/foreach}
