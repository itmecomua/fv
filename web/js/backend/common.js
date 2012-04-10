var cssFix = function(){
  var u = navigator.userAgent.toLowerCase(),
  addClass = function(el,val){
    if(!el.className) {
      el.className = val;
    } else {
      var newCl = el.className;
      newCl+=(" "+val);
      el.className = newCl;
    }
  },
  is = function(t){return (u.indexOf(t)!=-1)};
  addClass(document.getElementsByTagName('html')[0],[
    (!(/opera|webtv/i.test(u))&&/msie (d)/.test(u))?('ie ie'+RegExp.$1)
      :is('firefox/2')?'gecko ff2'
      :is('firefox/3')?'gecko ff3'
      :is('gecko/')?'gecko'
      :is('opera/9')?'opera opera9':/opera (d)/.test(u)?'opera opera'+RegExp.$1
      :is('konqueror')?'konqueror'
      :is('applewebkit/')?'webkit safari'
      :is('mozilla/')?'gecko':'',
    (is('x11')||is('linux'))?' linux'
      :is('mac')?' mac'
      :is('win')?' win':''
  ].join(" "));
}();

Event.observe(window, 'load', function () {
    Object.extend(window, {
        _currentURL: '',
        go: function (url, keepflash) {
            if (keepflash != true && $('actionmessage')) {
                $('actionmessage').hide();
            }
            if ($("content")) {
                document.location.href = _currentURL + "#" + url;
                window.historyVar.getNewHistoryState(url);
            } else {
                if (window.location.href != _currentURL + "#" + url) {
                    window.location.href = _currentURL + "#" + url;
                    window.location.reload();
                }
            }
        },
        getContent: function (url) {
            window.blockScreen();
            new Ajax.Updater("content", url, {
                evalScripts: true,
                onComplete: function (transport) {
                    window.completeRequest(transport);
                    window.parseForms();
                }
            });
        },
        blockScreen : function () {
            if (!$('contentblocker')) return;
            
            $('contentblocker').show();
            $('contentblocker').setStyle({
                'width' : document.viewport.getWidth() + "px",
                'height' : document.viewport.getHeight() + "px"
            });
         
        }
        ,unblockScreen: function() {
            $('contentblocker').hide()
        }
        ,showActionMessage: function (message, type) {
            if (!$('actionmessage')) return;
            
            $('actionmessage').setOpacity(0.0);
            $('actionmessage').show();
            $('actionmessage').innerHTML = message;
            $('actionmessage').className = type;
            
            new Effect.Opacity('actionmessage', {duration:2, from:0, to:1.0});
        },
        parseForms: function () {
            $$("form").each(function(form) {
                if (form.readAttribute('enctype') != 'multipart/form-data') {
                    Event.observe(form, 'submit', window.sendForm.bindAsEventListener(form, form.readAttribute("action")));
                } 
            });
        },
        sendForm: function (e, url) {
            if (e) Event.stop(e);
            window.blockScreen();
            
            try {
                if (FCKeditorAPI && FCKeditorAPI.Instances) {
                    for (var name in FCKeditorAPI.Instances) {
                        var oEditor = FCKeditorAPI.Instances[name];
                        if (oEditor.GetParentForm && oEditor.GetParentForm() == this) {
                            oEditor.UpdateLinkedField();
                        }
                    }
                }
            } catch (ex) {
                
            }
            
            new Ajax.Request(url, {
                parameters: this.serialize(),
                method: 'post',
                
                onComplete: function (transport, json) {
                    window.completeRequest(transport);
                    if (transport.getHeader('filtered')) {
                        $('content').update(transport.responseText);
                        return;
                    }
                    if (transport.getHeader('Id')) {
                        eval("$('id').value = " + transport.getHeader('Id'));
                    }
                    $$("form .error").each(function (el) { el.className = ""; });
                    
                    for (var i in json) {
                        $(i).className = "error";
                        $(i).title = json[i];
                    }
                }
            }); 
            return false;
        },
        completeRequest: function (transport) {
            if ($('contentblocker')) $('contentblocker').hide();
            if (transport.getHeader('actionmessage')) {
                eval("var actionMessage = " + transport.getHeader('actionmessage'));
                window.showActionMessage(actionMessage.message, actionMessage.type);
            }

            if (transport.getHeader('redirectDirect')) {
                window.location.href = transport.getHeader('redirectDirect');
            }
            if (transport.getHeader('redirect')) {
                eval("go('" + transport.getHeader('redirect') + "', true);");
            }
            
            window.startLoginInterval();
        },
        FCKeditor_OnComplete: function ( editorInstance ) {
            
        },
        historyHandler: function() {
            var stateVar = "nothin'", displayDiv = document.getElementById("content");
    
            this.getNewHistoryState = function(currentState) {
                var newVal = currentState;
                unFocus.History.addHistory(newVal);
            };
    
            this.historyListener = function(historyHash) {
                stateVar = historyHash;
                // при каждом изменении истории мы будем
                // выдавать соответствующий контент
                window.getContent((stateVar)?stateVar:document.location.pathname);
            };
            unFocus.History.addEventListener('historyChange', this.historyListener);
    
            this.historyListener(unFocus.History.getCurrent());
        },
        historyVar: null,
        loginWnd: null,
        loginInterval: null,
        loginTimeOut: null,
        init: function () {
        
            this.loginInterval = 30 * 60 * 1000;
            
            this.loginWnd = new PopUpWindow({
                width: 500,
                height: 260,
                center: true,
                url: '/backend/',
                title: "вход в систему",
                name: 'login_form',
                btnOkText: 'войти в систему',
                zIndex: 1000,
                onShow: function (params) {
                    new Ajax.Updater('login_form_content', '/backend/login/loginform/', {
                        evalScripts: true
                    });
                },
                onOk: function (params) {
                    var login = '';
                    var password = '';     
                    $$('#login_form_content input').each(function (elem) {
                        if (elem.name == 'login') login = elem.value;
                        if (elem.name == 'password') password = elem.value;
                    });
                    
                    new Ajax.Request('/backend/login/login/', {
                        parameters: { login: login, password: password },
                        onSuccess: function (transport, json) {
                            if (transport.getHeader('loginsuccess')) {
                                window.loginWnd.close();
                                window.startLoginInterval();
                                //window.completeRequest(transport);
                            } else {
                                if (transport.getHeader('actionmessage')) {
                                    eval("var actionMessage = " + transport.getHeader('actionmessage'));
                                    alert(actionMessage.message);
                                }
                            }
                        }
                    });
                
                    return true;
                },
                onCancel: function (params) {
                    window.location.href = '/backend/login/logout/'; 
                }
            });
        
            historyVar = new window.historyHandler();
            this._currentURL = document.location.href.split("#").slice(0, 1).join('');
            var url = document.location.href.split("#").slice(1).join('');
            if($('actionmessage')) $('actionmessage').hide();
            if (url) this.go(url);
        },
        startLoginInterval: function () {
            clearTimeout(this.loginTimeOut);
            
            if (!($('login') && $('password') && !$('id')))
                this.loginTimeOut = setTimeout(this.showLoginWindow, this.loginInterval);
        },
        showLoginWindow: function () {
            clearTimeout(this.loginTimeOut);
            this.loginWnd.show();
        }
    });
    window.init();
}, false);

var Pager = Class.create();

Pager.prototype =  {
    initialize: function (containerId) {
        $$("#" + containerId + " a").each(function (link) {
            link.onclick = window.go.bind(link, link.readAttribute("href"));
            link.writeAttribute("href", "javascript: void(0)");
        });
    }
};

var PopUpWindow = Class.create();

PopUpWindow.prototype = {
    buttonOk: 'ok',
    buttonCancel: 'cancel',
    initialize: function (params) {
        this.width = (params.width)?params.width:0;
        this.height = (params.height)?params.height:0;
        
        this.showType = (params.height)?params.showType:'div';
        this.url = (params.height)?params.url:'';
        this.content = (params.height)?params.content:'';
        this.center = (params.center)?params.center:'';
        
        if (!this.center) {
            this.top = (params.top)?params.top:0;
            this.left = (params.left)?params.left:0;
        }
        
        this.onShow = params.onShow;
        this.onClose = params.onClose;
        this.onOk = params.onOk;
        this.onCancel = params.onCancel;
        
        this.name = (params.name)?params.name:'popupWindow';
        
        if ( $( this.name + "_container" ) == null )
            this.container = document.createElement('DIV');
        else
        {
             this.container = $( this.name + "_container" ); 
             this.container.innerHTML = '';
        }
        
        this.container.id = this.name + "_container";
        
        if ($("documentBody")) {
            $("documentBody").appendChild(Element.extend(this.container));
        } else {
            body = $$("body");
            if (body.length > 0) {
                body[0].appendChild(Element.extend(this.container));
            }
        }
        
        this.titlePanel = document.createElement('DIV');
        this.titlePanel.id = this.name + "_title";
        this.titlePanel.className = "popupTitle";
        
        this.contentPanel = document.createElement("DIV");
        this.contentPanel.id = this.name + "_content";
        this.contentPanel.className = "contentPanel";
        
        this.buttonPanel = document.createElement("DIV");
        this.buttonPanel.id = this.name + "_buttons";
        this.buttonPanel.className = "buttonPanel";

        if (!params.buttons || params.buttons.join(',').match(this.buttonCancel)) {
            buttonCancel = document.createElement("INPUT");
            buttonCancel.className = "button";
            buttonCancel.type = "submit";
            buttonCancel.value = (params.btnCancelText)?params.btnCancelText:"Отменить";
            this.buttonPanel.appendChild(Element.extend(buttonCancel));
            buttonCancel.observe('click', this.doCancel.bindAsEventListener(this));
        }

        if (!params.buttons || params.buttons.join(',').match(this.buttonOk)) {
            buttonOk = document.createElement("INPUT");
            buttonOk.className = "button";
            buttonOk.type = "submit";
            buttonOk.value = (params.btnOkText)?params.btnOkText:"Сохранить";
            this.buttonPanel.appendChild(Element.extend(buttonOk));
            buttonOk.observe('click', this.doOk.bindAsEventListener(this));
        }
        
        this.container.appendChild(Element.extend(this.titlePanel));
        this.container.appendChild(Element.extend(this.contentPanel));
        this.container.appendChild(Element.extend(this.buttonPanel));
        
        this.titlePanel.update((params.title)?params.title:'&nbsp;');
        
        this.contentPanel.setStyle({
            height: (this.height - 96) + "px"
        });
        
        if (params.contentData) {
            this.contentPanel.update(params.contentData);
        }
        
        this.zIndex = (params.zIndex)?params.zIndex:1001;
        
        this.container.className = "popupContainer";
        this.container.setStyle({
            display: 'none',
            width: this.width + "px",
            height: this.height + "px",
            'z-index': this.zIndex
        });
        
        body = $$("body");
        if (body.length > 0) {
            this.documentBody = body[0];
        }
        
        this.windowblocker = document.createElement('IMG');
        this.documentBody.appendChild(Element.extend(this.windowblocker));
        this.windowblocker.className = "windowblocker";
        this.windowblocker.src = '/backend/img/x-gray.png';
        this.windowblocker.setStyle({
            'display': 'none',
            'position': 'absolute',
            left: '0px',
            top: '0px'
        });
        
        this.closefunc = this.onKeyPressed.bindAsEventListener(this);
    },
    
    centerWindow: function () {
        var x = 0;
        var y = 0;
        
        x = 
        (((document.viewport.getWidth() + document.viewport.getScrollOffsets().left - this.width) / 2) > 0) 
            ? ((document.viewport.getWidth() + document.viewport.getScrollOffsets().left - this.width)/2) 
            : 0;
        y = (((document.viewport.getHeight() + document.viewport.getScrollOffsets().top - this.height)/2) > 0) 
            ? ((document.viewport.getHeight() + document.viewport.getScrollOffsets().top - this.height)/2) 
            : 0;
        return {top: parseInt(y), left: parseInt(x) }
    },
    
    doOk: function (evt) {
        if (this.onOk) {
            if (!this.onOk(this.showParams)) this.close();
         } else this.close();
    },
    
    doCancel: function (evt) {
        if (this.onCancel) {
            if (!this.onCancel(this.showParams)) this.close();
        } else this.close();
    },
    
    onKeyPressed: function (evt) {
        if (evt.keyCode == Event.KEY_ESC) {
            this.close();
        }
        Event.stop(evt);
    },
    
    close: function () {
        this.windowblocker.hide();
        this.container.hide();
        Event.stopObserving(this.documentBody, "keyup", this.closefunc);
        if (this.onClose) this.onClose();
    },
    
    show: function (params) {
        this.showParams = params;
    
        if (this.center) {
            this.top = this.centerWindow().top;
            this.left = this.centerWindow().left;
        }
        
        this.windowblocker.show();
        this.windowblocker.setStyle({
            'width' : document.viewport.getWidth() + "px",
            'height' : document.viewport.getHeight() + "px",
            'z-index': (this.zIndex - 1)
        });
    
        this.container.setStyle({
            top: this.top + "px",
            left: this.left + "px"
        });

        Event.observe(this.documentBody, "keyup", this.closefunc);
        
        
        this.container.setOpacity(0);
        this.container.show();
        
        new Effect.Opacity(this.container.id, {duration:0.4, from:0, to:1.0})
        
        if (this.onShow) this.onShow(params);
    }
};

var Tabs = Class.create();

Tabs.prototype = {
    container: null,
    dataUrl: null,
    additionalParams: null,
    currentTab: null,

    initialize: function (tabsId, dataUrl, additionalParams) {
        
        this.container = $(tabsId);
        if (!this.container) throw "objectInitError";
        
        if (additionalParams)
            this.additionalParams = additionalParams;
        else this.additionalParams = {};
        
        this.dataUrl = dataUrl;
        
        $$("#"+tabsId+" ul.tabs li").each(function (elem) {
            elem.observe('click', this.click.bind(this));
        }.bind(this));    
        
    },
    
    click: function (e) {
        this.getData.bind(this, Event.element(e).id).call();
    },
    
    reload: function () {
        if (this.currentTab) {
            this.getData.bind(this, this.currentTab).call();
        }
    },
    
    getData: function (selectedTabId) {
        $$("#"+this.container.id+" ul.tabs li").each(function (elem) {
            elem.removeClassName('selected');
        }.bind(this));
        
        $(selectedTabId).addClassName('selected');
        
        window.blockScreen();
        
        this.currentTab = selectedTabId;
        
        new Ajax.Updater("tabcontent", this.dataUrl, {
            parameters: Object.extend(this.additionalParams, {tabId: selectedTabId}),
            evalScripts: true,
            onComplete: function (transport) {
                window.completeRequest(transport);
                window.parseForms();
            }
        });
    }
};

var UploadPanel = Class.create();

UploadPanel.prototype = {
    baseId: null,
    deleteUrl: null,
    
    initialize: function (baseId, deleteUrl) {
        this.baseId = baseId;
        this.deleteUrl = deleteUrl;
        
        $(this.baseId + "_form").writeAttribute({
            target: this.baseId + "_frame"
        });
        
        $(this.baseId + "_delete").observe('click', this.deleteImage.bind(this));
    },
    
    deleteImage: function () {
        if (confirm('Вы действительно хотите удалить изображение?')) {
            window.blockScreen();
            new Ajax.Request(this.deleteUrl,{
                parameters: {id: $F('id'), image: $F('image')},
                onSuccess: function (transport, json) {
                    window.completeRequest(transport);
                    if (json && json.image) {
                        $('image').value = json.image.split('/').slice(-1).join('');
                        if ($(this.baseId + '_img')) {
                            $(this.baseId + '_img').src = json.image;
                            $(this.baseId + '_img').width = json.width;
                            $(this.baseId + '_img').height = json.height;
                        }
                    } else {
                        $(this.baseId + '_preview').hide();
                        $('image').value = '';
                    }
                }.bind(this)
            });
        }
    }

};

Object.extend(window, {
    c_wnd: null,
    getCountryWindow: function () {
        if (window.c_wnd) return window.c_wnd;
        window.c_wnd = new PopUpWindow({
            width: 650,
            height: 400,
            center: true,
            url: '/backend/',
            title: "список стран",
            name: 'countries',
            zIndex: 501,
            onShow: function (params) {
                $('countries_content').setStyle({
                    'overflow': 'auto'
                });
                $('countries_content').innerHTML = "<img src='/backend/img/loading.gif' width='16' height='16' style='margin: 150px 0px 0px 300px'>";
                new Ajax.Updater('countries_content', "/backend/ajaxcall/showcountries/", {
                    parameters: {countryList: params},
                    evalScripts: true,
                    onComplete: function (transport, json) {
                        
                    }
                });
            },
            onOk: function (params) {
                var list = '';
                var c_list = '';
                $$('#countries_content input').each(function (item) {
                    if (item.checked) {
                        c_list += (c_list.length?', ':'') + $('l_' + item.id).innerHTML;
                        list += (list.length?',':'') + item.value;
                    } 
                });
                $('countries').value = list;
                $('selectedCountriesList').innerHTML = c_list?c_list:"не выбрано";
            }
        });
        
        return window.c_wnd;
    }
});
Object.extend(window, {
    r_wnd: null,
    getSubRubricsWindow: function () {
    
        if (window.r_wnd) return window.r_wnd;
        window.r_wnd = new PopUpWindow({
            width: 650,
            height: 400,
            center: true,
            url: '/backend/',
            title: "Список подрубрик",
            name: 'sub_rubric_id',
            zIndex: 501,
            onShow: function (params) {
                $('sub_rubric_id_content').setStyle({
                    'overflow': 'auto'
                });
                $('sub_rubric_id_content').innerHTML = "<img src='/backend/img/loading.gif' width='16' height='16' style='margin: 150px 0px 0px 300px'>";
                //alert("getSubRubricsWindow"); 
                new Ajax.Updater('sub_rubric_id_content', "/backend/ajaxcall/showsubrubric/", {
                    parameters: {"rubric_id": $("rubric_id").value, "rubsList": params}, 
                    evalScripts: true,
                    onComplete: function (transport, json) {
                        
                    }
                });
            },
            onOk: function (params) {
                var list = '';
                var c_list = '';
                $$('#sub_rubric_id_content input').each(function (item) {
                    if (item.checked) {
                        c_list += (c_list.length?', ':'') + $('l_' + item.id).innerHTML;
                        list += (list.length?',':'') + item.value;
                    } 
                });
                $('sub_rubric_id').value = list;
                $('selectedRubricsList').innerHTML = c_list?c_list:"не выбрано";
            }
        });
        
        return window.r_wnd;
    }
}); 