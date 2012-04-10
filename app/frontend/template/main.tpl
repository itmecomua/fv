<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset={$fvConfig->get('charset')}" />
        <meta http-equiv="Content-language" content="ru" />
        <title>{$currentPage->getTitle()}</title>
        <meta name="keywords" content="{$currentPage->getKeyWords()}" />
        <meta name="description" content="{$currentPage->getDescription()}"/>
        {foreach item=Css from=$fvConfig->get('includes.css')}
            <link rel="stylesheet" type="text/css" href="{$Css}" />
        {/foreach}
        {$currentPage->getCss()}
        {foreach item=Js from=$fvConfig->get('includes.js')}
            <script type="text/javascript" src="{$Js}"></script>
        {/foreach}
        {$currentPage->getJS()}        
        <script type="text/javascript" src="/js/jquery.nivo.slider.pack.js"></script>
        <link   type="text/css"        rel="stylesheet"  href="/css/nivo_slider/nivo_slider.css" />
        <link   type="text/css"        rel="stylesheet"  href="/highslide/highslide.css" />
        <script type="text/javascript" src="/highslide/highslide-with-gallery.js"></script>
        <script type="text/javascript" src="/highslide/highslide.config.js" charset="utf-8"></script>
        <script type="text/javascript" src="/js/imageflow.js"></script>
        <link   type="text/css"        rel="stylesheet" href="/css/imageflow.css"  />
        <link rel="icon" href="http://kompas.it-me.com.ua/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="http://kompas.it-me.com.ua/favicon.ico" type="image/x-icon"/>
        {$codeManager->getCodeHeader()}
</head>
<body>  
<div class="SC_wrapper">
	<div class="SC_header">
        <div class="common_wrp">
        <div class="logo_wrp"><a href="/"></a></div>
        <div class="contacts_wrp">
            {show_module    module='contacts'        view='index'}
        </div>
        <div class="compas_wrp"></div>
        <div class="reklama_wrp">
            {show_module    module='advertise' view='crb'}
        </div>
        </div>
        {show_module module='menu' view='main'}
	</div>
	<div class="SC_middle">
		<div class="SC_container">
			<div class="SC_content">
				<div class="content">
                {*
                    {show_module    module='priceofday' view='index'}
                    {show_module    module='recommend'  view='index'}
                    {show_module    module='mix'        view='index'}
                *}                    
                    {$currentPage->getPageContentPart('page_content')}
				</div>
			</div>
        </div>
		<div class="SC_left_column">
			<div class="left_inside">
                {* show_module    module='news' view='latest' *}
                {* show_module module='advertise' view='headerleft' *}
                {* show_module module='advertise' view='headerright' *}
                {* show_block     file="online_consult.tpl" *}
                {* show_block     file="online_reserv.tpl" *}
                {* show_block     file="fast_search.tpl" *}
                {* show_module    module='meteo' view='panel' *}
                {* show_module    module='codedictionary' view='zone1' *}
                {* show_module    module='menu' view='additional' *}
                {$currentPage->getPageContentPart('leftcol')}
                {show_block     file="list_na.tpl"}
                {show_block     file="facebook.tpl"}
                {show_module    module='news' view='latest'}
                
                
                
			</div>
		</div>
{* right column start
		<div class="SC_right_column">
			<div class="right_inside">

			</div>
		</div>
*}
	</div>
</div>
<div class="SC_footer">
    <div class="footer_in">
    
        {show_module    module='contacts'        view='upsidedown'}

    </div>
</div>
{*
    <div id="imageFlowSemafor"></div>
    {$currentPage->getPageContentPart('footer')}
    {$codeManager->getCodeFooter()}
    {$currentPage->getPageContentPart('closestbody')}
*}    
</body>
</html>