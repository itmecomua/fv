<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<HTML>

<head>
<title>{$currentPage->getTitle()}</title>
<META http-equiv="Content-type" content="text/html; charset={$fvConfig->get('charset')}" />
<meta name="keywords" content="{$currentPage->getKeyWords()}" />
<meta name="description" content="{$currentPage->getDescription()}" />

{foreach item=Css from=$fvConfig->get('includes.css')}
<link rel="stylesheet" type="text/css" href="{$Css}" />
{/foreach}

    
{foreach item=Js from=$fvConfig->get('includes.js')}
<SCRIPT type="text/javascript" src="{$Js}"></SCRIPT>
{/foreach}

<BODY>
    {$currentPage->getPageContent()}
</BODY>

</HTML>