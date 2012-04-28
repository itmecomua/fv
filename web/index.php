/*
<?php
/*
define("FV_ROOT", realpath(dirname(dirname(__FILE__))) );
require_once( FV_ROOT . '/framework/fvSite.php' );
fvSite::start( require_once(FV_ROOT . '/config/main.php') );
*/
?>
*/

1/ Include the JavaScript SDK on your page once, ideally right after the opening <body> tag.

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


2/ Add an XML namespace to the <html> tag of your document. This is necessary for XFBML to work in earlier versions of Internet Explorer.

<html xmlns:fb="http://ogp.me/ns/fb#">


3/ Place the code for your plugin wherever you want the plugin to appear on your page.

<fb:comments href="http://extour.com.ua/" num_posts="5" width="470"></fb:comments>