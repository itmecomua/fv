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

<body>
    <div id="content">
        {$currentPage->getPageContent()}
    </div>
    <img id="contentblocker" src="{$fvConfig->get('dir_web_root')}img/x-gray.png" style="display: none; left: 0px; top: 0px; ">
</body>

</hrml>