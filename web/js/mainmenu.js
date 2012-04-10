var canRedirect = true;

var current_obj = null;
var old_object = null;

function go (url) {
        if (canRedirect) location.href = url;
}

function getElementPosition(elemId) {
        
        elem = $(elemId);
        
        if (!elem) return {"left": 0, "top": 0, "width": 0, "height": 0};
        
        var w = elem.offsetWidth;
        var h = elem.offsetHeight;
                
        var l = 0;
        var t = 0;
        
        while (elem) {
                l += elem.offsetLeft;
                t += elem.offsetTop;
                elem = elem.offsetParent;
        }
        
        return {"left":l, "top":t, "width": w, "height":h};
}

function getElementComputedStyle(elem, prop) {
        elem = $(elem);
        
        // external stylesheet for Mozilla, Opera 7+ and Safari 1.3+
        if (document.defaultView && document.defaultView.getComputedStyle) {
                if (prop.match(/[A-Z]/)) prop = prop.replace(/([A-Z])/g, "-$1").toLowerCase();
                return document.defaultView.getComputedStyle(elem, "").getPropertyValue(prop);
        }
  
  // external stylesheet for Explorer and Opera 9
       if (elem.currentStyle) {
                var i;
                while ((i=prop.indexOf("-"))!=-1) prop = prop.substr(0, i) + prop.substr(i+1,1).toUpperCase() + prop.substr(i+2);
                return elem.currentStyle[prop];
        }
}

function doPupUp (evt) {
        
        elemID = Event.element(evt).id;
        
        if (!$(elemID + "_popup")) return;
        
        if ($(elemID + "_popup").style.display == 'block') {
                $(elemID + "_popup").style.display = 'none';
                current_obj = null;
                
                old_object.style.backgroundColor = 'transparent';
                old_object.style.border = '1px solid #DFDCD7';
        } else {
                
                if (current_obj)        
                        current_obj.style.display = 'none';
                        
                $(elemID + "_popup").style.top = (getElementPosition(Event.element(evt)).top + getElementPosition(Event.element(evt)).height - 1);
                $(elemID + "_popup").style.left = getElementPosition(Event.element(evt)).left
                
                Element.setOpacity($(elemID + "_popup"), 0);
                $(elemID + "_popup").style.display = 'block';
                
                new Effect.Opacity(elemID + "_popup", {duration:0.4, from:0, to:1.0});
                
                old_object.style.backgroundColor = 'transparent';
                old_object.style.border = '1px solid #DFDCD7';
                
                Event.element(evt).style.backgroundColor = '#FFFFFF';
                Event.element(evt).style.borderTop = '1px solid #000000';
                Event.element(evt).style.borderLeft = '1px solid #000000';
                Event.element(evt).style.borderRight = '1px solid #000000';
                Event.element(evt).style.borderBottom = '1px solid #FFFFFF';
                
                current_obj = $(elemID + "_popup");
        }
        
        Event.stop (evt);
}

function mouseOver (e) {
    obj = Event.element(e);
    
    alert(obj);
    
    if (obj.id) {
        alert(obj.id)
        
        obj.style.backgroundColor = '#B6BDD2';
        obj.style.border = '1px solid #0A246A';
        
        if (obj.id.match('_child')) {
                if (obj.className.match('noimage'))
                        obj.style.padding = '3px 3px 3px 24px';
                else obj.style.padding = '3px 3px 3px 3px';
        }
        
        if (current_obj && !current_obj.id.match(obj.id)) 
            doPupUp(e);
                
        if (!obj.id.match('_child'))
            old_object = obj;
        
        if (current_obj) {
            if (current_obj.id == obj.id) {
            
            } else if (current_obj.id.match(obj.id)) {
                obj.style.backgroundColor = '#FFFFFF';
                obj.style.borderTop = '1px solid #000000';
                obj.style.borderLeft = '1px solid #000000';
                obj.style.borderRight = '1px solid #000000';
                obj.style.borderBottom = '1px solid #FFFFFF';
            }
        }
        Event.stop (e);
    }
}

function mouseOut (e) {
        obj = Event.element(e);
        
        if ((current_obj && !current_obj.id.match(obj.id)) || !current_obj) {
                obj.style.backgroundColor = 'transparent';
                if (!obj.id.match('_child'))
                        obj.style.border = '1px solid #DFDCD7';
                else {
                        if (obj.className.match('noimage'))
                                obj.style.padding = '4 4 4 25';
                        else obj.style.padding = '4 4 4 4';
                        obj.style.border = '0px solid #FFFFFF';
                }
        }

        Event.stop (e);
}

function hideMenu (e) {
        if (current_obj)        
                current_obj.style.display = 'none';
                
        current_obj = null;
        
        if (old_object) {
                old_object.style.backgroundColor = 'transparent';
                old_object.style.border = '1px solid #DFDCD7';
        }
        
        old_object = null
}

el = document.getElementsByTagName('BODY');
el = el [0];

if (el) {
    Event.observe (el, "click", hideMenu, false)
}

