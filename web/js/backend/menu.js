var canRedirect = true;

var current_obj = null;
var old_object = null;

function go (url) {
    if (canRedirect) location.href = url;
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
                        
        
        Element.setOpacity($(elemID + "_popup"), 0);
        $(elemID + "_popup").show();
        
        $(elemID + "_popup").setStyle({
            'top': (Element.cumulativeOffset(Event.element(evt)).top + Element.getDimensions(Event.element(evt)).height - 1) + "px",
            'left': (Element.cumulativeOffset(Event.element(evt)).left) + "px"
        });
        
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
    
    if (obj.id) {
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
                                obj.style.padding = '4px 4px 4px 25px';
                        else obj.style.padding = '4px 4px 4px 4px';
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

Event.observe(window, 'load', function () {
    body = $$("body");
    if (body.length > 0) {
        Event.observe (body[0], "click", hideMenu);
    }
});