function centerWindow(windowWidth, windowHeight) {
    var x = 0;
    var y = 0;
    
/*    x = 
    (((document.viewport.getWidth() - windowWidth) / 2) > 0) 
        ? ((document.viewport.getWidth()-windowWidth)/2) 
        : 0;
    y = (((document.viewport.getHeight()-windowHeight)/2) > 0) ? ((document.viewport.getHeight()-windowHeight)/2) : 0;*/
    x = 
    (((window.screen.width - windowWidth) / 2) > 0) 
        ? ((window.screen.width-windowWidth)/2) 
        : 0;
    y = (((window.screen.height-windowHeight)/2) > 0) ? ((window.screen.height-windowHeight)/2) : 0;

    var str = "location=no,resizable=no,scrollbars=yes,status=no,toolbar=no,width=" + windowWidth +", height=" + windowHeight + ", top=" + y + ", left=" + x;
    return str;
}


function pupUpWindow(filename, width, height) {
	var wnd_h = window.open(filename, 'PopUpWindow', centerWindow(width,height));
	wnd_h.focus();
}

function pupUpImageWindow(filename, width, height, title) {
	var wnd_h = window.open('', 'PopUpWindow', centerWindow(width + 20,height + 20)+',status=no,scrollbars=no');
	
	if (wnd_h) {
		wnd_h.document.open('text/html');
		wnd_h.document.writeln('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">');
		wnd_h.document.writeln('<HTML><HEAD><TITLE>' + title + '</TITLE></HEAD><BODY><CENTER>');
		wnd_h.document.writeln('<IMG src="' + filename + '" width="' + width + '" height="' + height + '" alt="' + title + '">');
		wnd_h.document.writeln('</CENTER></BODY></HTML>');
		wnd_h.document.close();
	
		wnd_h.focus();
	}
	else alert('Извините произошла ошибка сервера. Попробуйте позднее');
}


// getPageScroll()
// Returns array with x,y page scroll values.
// Core code from - quirksmode.org
//
function getPageScroll(){

	var yScroll;

	if (self.pageYOffset) {
		yScroll = self.pageYOffset;
	} else if (document.documentElement && document.documentElement.scrollTop){	 // Explorer 6 Strict
		yScroll = document.documentElement.scrollTop;
	} else if (document.body) {// all other Explorers
		yScroll = document.body.scrollTop;
	}

	arrayPageScroll = new Array('',yScroll) 
	return arrayPageScroll;
}

//
// getPageSize()
// Returns array with page width, height and window width, height
// Core code from - quirksmode.org
// Edit for Firefox by pHaez
//
function getPageSize(){
	
	var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}


	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
}

function closeEnlarge() {
	Element.setOpacity($('shadePanel'), 0);
	$('shadePanel').style.display = "none";
	$('loadingPanel').style.display = "none";
	$('imagePanel').style.display = "none";
	
	isImageEnlarged = false;
}

/*Event.observe(document, 'keypress', function(event)
{
    
    if(event.keyCode == Event.KEY_ESC)
        closeEnlarge();
});*/

function switchImage() {
   $('loadingPanel').style.display = "none";
   $('imagePanel').style.display = "block";
}

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

var isImageEnlarged = false;

function onoverImage(obj, width, height) {
    
    if (!isImageEnlarged) {
        
//        alert(findPos(obj)[1] - ((height -obj.height) / 2))
        
       	$('imagePanel').style.top = findPos(obj)[1] - ((height -obj.height) / 2);
    	$('imagePanel').style.left = findPos(obj)[0] - ((width - obj.width) / 2);
    	$('imagePanel').style.width = width;
    	$('imagePanel').style.height = height;
    	
    	//$('imagePanel').style.display = "block";

//        <div align="right" style="font-size: 10px;text-decoration: underline; margin-bottom: 5px;" onclick="closeEnlarge()">закрыть</div>
    	
    	$('imagePanel').innerHTML = '<IMG src="' + obj.src + '" width="' + width + '" height="' + height + '" onmouseout="closeEnlarge()">';
    	
        isImageEnlarged = true;
//	$('imagePanel').style.display = "block";
    
        new Effect.Grow('imagePanel', {direction: 'center', duration: 1.0});
        
    }    
}

/*enlargeImage (image_name, width, height) {
	var div = document.createElement('div');
	
	var arrayPageSize = getPageSize();
	var arrayPageScroll = getPageScroll();
	
	Element.setOpacity($('shadePanel'), 0);
	$('shadePanel').style.display = "block";
    $('shadePanel').style.height = arrayPageSize[1];
    
	$('loadingPanel').style.display = "block";
	$('loadingPanel').style.top = (arrayPageScroll[1] + ((arrayPageSize[3] - 22) / 2) + 'px');
	$('loadingPanel').style.left = (((arrayPageSize[0] - 126) / 2) + 'px');

	Element.setOpacity($('loadingPanel'), 1);
	Element.setOpacity($('imagePanel'), 1);
	
	new Effect.Opacity("shadePanel", {duration:1, from:0, to:0.6});
	
	$('imagePanel').style.top = (arrayPageScroll[1] + ((arrayPageSize[3] - 20 - width) / 2) + 'px');
	$('imagePanel').style.left = (((arrayPageSize[0] - 20 - height) / 2) + 'px');
	
	$('imagePanel').innerHTML = '<div align="right" style="font-size: 10px;text-decoration: underline; margin-bottom: 5px;" onclick="closeEnlarge()">закрыть</div><IMG src="' + image_name + '" width="' + width + '" height="' + height + '" onload="switchImage();"  onclick="closeEnlarge()">';
	
}*/
