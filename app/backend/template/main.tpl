<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
<title>{$currentPage->getTitle()}</title>
<meta http-equiv="Content-type" content="text/html; charset={$fvConfig->get('charset')}" />
<meta name="keywords" content="{$currentPage->getKeyWords()}" />
<meta name="description" content="{$currentPage->getDescription()}" />

{foreach item=Css from=$fvConfig->get('includes.css')}
    <link rel="stylesheet" type="text/css" href="{$Css}" />
{/foreach}
{foreach item=Js from=$fvConfig->get('includes.js')}
    <SCRIPT type="text/javascript" src="{$Js}"></SCRIPT>
{/foreach}
</head>
<body id="documentBody">
<div id="scrolobble">
    <div id="header">
        {show_module module="menu" view="mainMenu"}

        <div style="clear: both;"></div>
        <div id="actionmessage"></div>

	    {if $fvConfig->get('access.enable')}
	        {assign var=LoggedManager value=$currentPage->getLoggedUser()}
	        <div id="hello" class="text">{if $LoggedManager}добро пожаловать <B>{$LoggedManager->get('full_name')}</B> [<a href="{$fvConfig->get('access.do_logout')|urlto}">выйти</a>]{/if}</div>
	    {/if}
    </div>

    <div id="content">
        {$currentPage->getPageContent()}
    </div>
    <img id="contentblocker" src="{$fvConfig->get('dir_web_root')}img/x-gray.png" style="display: none;">
</div>
</body>

</html>