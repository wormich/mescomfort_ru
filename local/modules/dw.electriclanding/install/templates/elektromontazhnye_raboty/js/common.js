function Observer() {
    this.handlers = [];  // observers
}
 
Observer.prototype = {
 
    subscribe: function(fn) {
        this.handlers.push(fn);
    },
 
    unsubscribe: function(fn) {
        this.handlers = this.handlers.filter(
            function(item) {
                if (item !== fn) {
                    return item;
                }
            }
        );
    },
 
    fire: function(o, thisObj) {
        var scope = thisObj || window;
        this.handlers.forEach(function(item) {
            item.call(scope, o);
        });
    }
};

var aisModal = (function () {
    var _isOpen,
        _isRendered;

    var _rawHtml =  
        '<div id="MyPayWidget" class="mypay-widget">' +
            '<div class="adaptation-block">'+
                '<div class="adaptation-block2">'+
                    '<div class="mypay-widget__modal">' +
                        '<div class="adaptation-button">'+
                            '<button class="mypay-widget__modal-close">×</button>' +
                        '</div>' +
                        '<iframe id="MyPayWidgetFrame" src="" name="widgetFrame"></iframe>' +
                    '</div>' +
                    '<div class="mypay-widget__loader-overlay">' +
                        '<div class="mypay-widget__loader-pic"></div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    var modalElem,
        widgetKeeperElem,
        closeModalElem,
        loaderOverlayElem,
        backButton;

    var frameWindowRef, frame;
    var previousFrameLocation;

    var _locationObserver = new Observer();
    var _sizeModifierObserver = new Observer();


    function _updateStyles(modalWidth, modalHeight) {
        var sheet = _getModalStyleSheet('ais-widget');
        var cssRules = sheet.cssRules || sheet.rules;

        for (var idxRule in cssRules) {
            if (cssRules[idxRule].selectorText === '.mypay-widget__modal') {
                if (modalWidth) cssRules[idxRule].style.width = modalWidth;
                if (modalHeight) cssRules[idxRule].style.height = modalHeight;
            }

            if (cssRules[idxRule].selectorText === '.mypay-widget__modal--compact') {
                console.log('компакт', cssRules[idxRule].style.width);
            }
        }
    }

    function _render(frameUrl, modalWidth, modalHeight) {
        if (modalWidth || modalHeight) {
            _updateStyles(modalWidth, modalHeight);    
        }
        // вставляем разметку с модалкой
        document.body.insertAdjacentHTML('beforeEnd', _rawHtml);
         // инициализеруем переменные, хранящие основные элементы модалки
        widgetKeeperElem = document.getElementById('MyPayWidget');
        modalElem = widgetKeeperElem.querySelector('.mypay-widget__modal');
        frame = document.getElementById('MyPayWidgetFrame');
        frame.src = frameUrl;
        frameWindowRef = frame.contentWindow;
        closeModalElem = widgetKeeperElem.querySelector('.mypay-widget__modal-close');
        loaderOverlayElem = widgetKeeperElem.querySelector('.mypay-widget__loader-overlay');
        // ----

        _initBehavior();
        _isRendered = true;
    }

    // получение списка css-правил из css-файла
    function _getModalStyleSheet(unique_title) {
        for(var i = 0; i < document.styleSheets.length; i++) {
            var sheet = document.styleSheets[i];
            if (sheet.title == unique_title) {
                return sheet;
            }
        }
    }
    
    function _open() {
        _startLoading();
        widgetKeeperElem.classList.add('mypay-widget--open');
        modalElem.removeAttribute('style');

        _isOpen = true;
    }

    function _close() {
        widgetKeeperElem.classList.remove('mypay-widget--open');
        _isOpen = false;
    }

    
    function _finishLoad() {
        closeModalElem.classList.remove('mypay-widget__hidden');
        modalElem.classList.remove('mypay-widget__hidden');
        loaderOverlayElem.classList.add('mypay-widget__hidden');
    }

    function _startLoading() {
        closeModalElem.classList.add('mypay-widget__hidden');
        modalElem.classList.add('mypay-widget__hidden');
        loaderOverlayElem.classList.remove('mypay-widget__hidden');
    }

    function _outsidePagesButton() {
        backButton = document.createElement('div');
        backButton.innerText = '← Назад';
        backButton.classList.add('mypay-widget__back-to-start');
        modalElem.appendChild(backButton);

        backButton.addEventListener('click', function (e) {
            aisModal.setWidgetUrl(previousFrameLocation);
            backButton.remove();
        });
    };

    function _resize(payload) {
        if (payload.message === 'ACTIVATE_COMPACT') {
            modalElem.classList.add('mypay-widget__modal--compact');
        } else {
            modalElem.classList.remove('mypay-widget__modal--compact');
        }

        if (payload.message === 'ACTIVATE_ULTRA_COMPACT') {
            modalElem.classList.add('mypay-widget__modal--ultra-compact');
        } else {
            modalElem.classList.remove('mypay-widget__modal--ultra-compact');
        }

        if (payload.message === 'DEACTIVATE_COMPACT') {
            modalElem.classList.remove('mypay-widget__modal--compact');
        }

        if (payload.message === 'DEACTIVATE_ULTRA_COMPACT') {
            modalElem.classList.remove('mypay-widget__modal--ultra-compact');
        }

        if (payload.message === 'UPDATE_HEIGHT') {
            modalElem.style.height = payload.data.height + "px"; // ставит в атрибут
        } 
        
        if (payload.message === 'UPDATE_SIZE') {
            modalElem.style.height = payload.data.height + "px"; // ставит в атрибут
            if (payload.data.width)
                modalElem.style.width = payload.data.width + "px"; // ставит в атрибут
            console.log(modalElem.style.width);
        } else {
            modalElem.removeAttribute('style');
        }
    }

    function _crossAsToBack(fromUrl) {
        widgetKeeperElem.querySelector('.mypay-widget__modal-close').classList.add('back-to-start');
        widgetKeeperElem.querySelector('.mypay-widget__modal-close').setAttribute('data-fromurl', fromUrl);
    }

    function _crossOnlyClose() {
        widgetKeeperElem.querySelector('.mypay-widget__modal-close').classList.remove('back-to-start');
    }

    // задаем поведение модалки
    function _initBehavior() {
        widgetKeeperElem.querySelector('.mypay-widget__modal-close').addEventListener('click', function (e) {
            if (e.target.classList.contains('back-to-start')) {
                //frame.src = widgetKeeperElem.querySelector('.mypay-widget__modal-close').getAttribute('data-fromurl');
                frame.contentWindow.postMessage({message: 'GO_BACK'},'*');
                _crossOnlyClose();
            } else {
                _close();
            } 
        });
        frame.onload = function (e) {
            _finishLoad();
            // to FSM
            // var frameLocation = frame.getAttribute('src'); // а это на getWidgetUrl
            
            // if (!~frameLocation.indexOf(config.originUrl)) {
            //     _outsidePagesButton(); // в случае выноса в FSM заменить на aisModal.outsideLocation.fire()
            // }
            // -----
        }

        _locationObserver.subscribe(function(payload) {

            if (payload.hasOwnProperty('state')) {
                if (payload.state === 'agreement' && payload.hasOwnProperty('from')) {
                    _crossAsToBack(payload.from);
                } else {
                    _crossOnlyClose();
                }
            }
            else { // если не было передано парметра state, то виджет будет считать что страница внешняя и добавит кнопку "Назад"
                // которая будет возвращать в самое начало
                _outsidePagesButton(payload);
            }
        });
        _sizeModifierObserver.subscribe(_resize)
    }

    function _setWidgetUrl(address) {
        previousFrameLocation = frame.getAttribute('src');
        frame.setAttribute('src', address);
    }

    function _getWidgetUrl() {
        return frame.getAttribute('src');
    }

    return {
        close: _close,
        open: _open,
        render: _render,
        setWidgetUrl: _setWidgetUrl,
        getWidgetUrl: _getWidgetUrl,
        widgetLocation: _locationObserver,
        sizeModifier: _sizeModifierObserver,
        addBackButton: _outsidePagesButton,
        isOpen: _isOpen,
        isRendered: _isRendered
    }
})();


var MyPayWidget = (function () {
    var _config = {
        token: '',
        originUrl: 'https://91.231.141.141:8081/',
        //groupId: '',
        //serviceCode: '',
     //   width: '425px',
      //  height: '380px',
        widgetId: 0
       // css: '',
       // typeAgreement: ''
    };
    var config;
    var lastWidgetUrl;

    var _updateOptions = function (prev, update) {
        var result ={
            token: ((update && update['token']) ? update['token'] : null) || prev['token'],
            originUrl: ((update && update['originUrl']) ? update['originUrl'] : null) || prev['originUrl'],
            widgetId: ((update && update['widgetId']) ? update['widgetId'] : null) || prev['widgetId'],
            //groupId: ((update && update['groupId']) ? update['groupId'] : null) || prev['groupId'],
            //serviceCode: ((update && update['serviceCode']) ? update['serviceCode'] : null) || prev['serviceCode'],
           // width: ((update && update['width']) ? update['width'] : null) || prev['width'],
           // height: ((update && update['height']) ? update['height'] : null) || prev['height'],
           // css: ((update && update['css']) ? update['css'] : null) || prev['css'],
            //typeAgreement: ((update && update['typeAgreement']) ? update['typeAgreement'] : null) || prev['typeAgreement']
        };

        if (result.hasOwnProperty('originUrl') && result.originUrl) {
            result['indexUrl'] = result['originUrl'] + 'MyPayWidget/index.aspx';
            result['apiUrl'] = result['originUrl'] + 'gate.aspx';
        }

        return result;
    }
/*
    var _createWidgetUrl = function(updatedOpts) {
        var configLocal = _updateOptions(config, updatedOpts);

        return configLocal.indexUrl + '?token=' + encodeURIComponent(configLocal.token) +
            '&apiUrl=' + encodeURIComponent(configLocal.apiUrl) +
            '&originUrl=' + encodeURIComponent(configLocal.originUrl) +
            '&groupId=' + encodeURIComponent(configLocal.groupId) + 
            '&serviceCode=' + encodeURIComponent(configLocal.serviceCode) + 
            '&parentUrl=' + encodeURIComponent(window.location.origin) +
            '&typeAgreement=' + encodeURIComponent(configLocal.typeAgreement);
    }
        var _openModal = function (updatedOpts) {
        if (updatedOpts) { 
            lastWidgetUrl = _createWidgetUrl(updatedOpts);     
            aisModal.setWidgetUrl(lastWidgetUrl);
        }

        aisModal.open();

        return false;
    };
*/
    var _createWidgetUrl = function(widgetId) {
        var configLocal = config;
        return configLocal.indexUrl + '?token=' + encodeURIComponent(configLocal.token) +
            '&apiUrl=' + encodeURIComponent(configLocal.apiUrl) +
            '&originUrl=' + encodeURIComponent(configLocal.originUrl) +
            '&parentUrl=' + encodeURIComponent(window.location.origin) +
            '&widgetId=' + encodeURIComponent(widgetId) 
    }

    var _openModal = function (widgetId) {
        if (widgetId) { 
            lastWidgetUrl = _createWidgetUrl(widgetId);     
            aisModal.setWidgetUrl(lastWidgetUrl);
        }
        aisModal.open();
		return false;
    };


    window.addEventListener('message', function (e) {

        if (e.data.message) {
            switch(e.data.message) {
                case 'OUTSIDE_PAGE':
                    aisModal.sizeModifier.fire({
                        message: 'ACTIVATE_COMPACT'
                    }); 
                    break;
                case 'GET_CONTENT_HEIGHT':
                    aisModal.sizeModifier.fire({
                        message: 'UPDATE_HEIGHT',
                        data: {
                           height: e.data.height
                        }
                    });
                    break;
                 case 'GET_CONTENT_SIZE':
                    aisModal.sizeModifier.fire({
                        message: 'UPDATE_SIZE',
                        data: {
                           height: e.data.height,
                           width: e.data.width
                        }
                    });
                    break;
                case 'CLOSE_MODAL':
                    aisModal.close();
                    break;
                case 'ACTIVE_ROUTE':
                    switch (e.data.state) {
                        case 'account-form':
                            aisModal.widgetLocation.fire({
                                state: 'account-form',
                            });
                          /*  aisModal.sizeModifier.fire({
                                message: 'ACTIVATE_COMPACT'
                            });*/
                            break;
                        case 'agreement':
                            // когда сообщаем модалке об agreement
                            // обязателен параметр from, чтобы потом кнопка закрытия знала куда вернуть виджет
                            aisModal.widgetLocation.fire({
                                state: 'agreement',
                                from: lastWidgetUrl
                            });
                            aisModal.sizeModifier.fire({
                                message: 'DEACTIVATE_COMPACT'
                            });
                            break;
                        case 'service-pay':
                            aisModal.widgetLocation.fire({
                                state: 'service-pay'
                            });
                            aisModal.sizeModifier.fire({
                                message: 'DEACTIVATE_COMPACT'
                            });
                            break;
                        case 'finish-pay':
                            aisModal.widgetLocation.fire({
                                state: 'finish-pay'
                            });
                            aisModal.sizeModifier.fire({
                                message: 'ACTIVATE_ULTRA_COMPACT'
                            });
                            break;
                    }
            }
        }
    });

    var _init = function (opts) {
        var _opts = opts || {};
        config = _updateOptions(_config, _opts);

        if (config.indexUrl && config.token) {
            aisModal.render(config.indexUrl, config.width, config.height);
        } else {
            console.error("Ошибка при инициализации виджета MyPay! Неправильные опции.");
        }
    };

    return {
        init: _init,
        openModal: _openModal,
        closeModal: aisModal.close
    };
})();




// var AisWidgetFSM = (function () {
//     var states = {
//         'isRendered',
//         'isOpen',
//     }
// })();




/* ****Polyfills**** */

// .remove() DOMNode
(function() {
  var arr = [window.Element, window.CharacterData, window.DocumentType];
  var args = [];

  arr.forEach(function (item) {
    if (item) {
      args.push(item.prototype);
    }
  });

  (function (arr) {
    arr.forEach(function (item) {
      if (item.hasOwnProperty('remove')) {
        return;
      }
      Object.defineProperty(item, 'remove', {
        configurable: true,
        enumerable: true,
        writable: true,
        value: function remove() {
          this.parentNode.removeChild(this);
        }
      });
    });
  })(args);
})();