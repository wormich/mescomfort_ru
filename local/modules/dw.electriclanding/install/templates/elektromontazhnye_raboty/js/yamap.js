function init_yamap() {
    var city = 'Санкт-Петербург';
    var myGeocoder = ymaps.geocode(city);
    myGeocoder.then(
            function(res) {
                //var zoom=parseInt($('div#zoom').text());
                var zoom = 12;
                var arCoord = res.geoObjects.get(0).geometry.getCoordinates();
                // map.geoObjects.add(res.geoObjects);
                var myMap = new ymaps.Map("map", {
                    center: [parseFloat(arCoord[0]), parseFloat(arCoord[1])],
                    zoom: 12,
                    flying: true,
                    width: '100%'
                }, {
                });

                myMap.controls.add('zoomControl');
                myMap.controls.add('scaleLine');
                myMap.behaviors.disable('scrollZoom');
                // myMap.disableScrollZoom();
                /* function init(){
                 myMap = new ymaps.Map ("map", {
                 center: [55.76,37.64],
                 zoom: 10
                 });
                 }*/
                function setPlacemark(addr, street, content, pos, coord_cus) {
                    
                    var myGeocoder = ymaps.geocode(addr);
                    myGeocoder.then(
                            function(res) {
                               var arCoord = Array();
                                if(coord_cus)
                                {
                                    arCoord[0]=coord_cus[0];
                                    arCoord[1]=coord_cus[1];
                                    
                                    
                                }else{
                                    arCoord = res.geoObjects.get(0).geometry.getCoordinates()
                                    }
                                    
                                    
                                myPlacemark = new ymaps.Placemark([parseFloat(arCoord[0]), parseFloat(arCoord[1])], {
                                    balloonContent: '<h3>' + street + '</h3>' + content,
                                    hintContent: '<div class="map-hint"><h3 style="color: #000">' + street + '</h3><span>' + content+'</span></div>',
                                    iconContent: pos
                                }, {
                                    iconImageHref: '/templates/elektrosbit/img/ico_map.png',
                                    iconImageSize: [36, 34]
                                });
                                myMap.hint.show({
                                    //maxWidth: 20
                                });

                                myMap.geoObjects.add(myPlacemark);

                            });


                }
                
                function setPlacemarkCoords(coords, street, content, pos) {
                    
                    myPlacemark = new ymaps.Placemark([parseFloat(coords[0]), parseFloat(coords[1])], {
                                    balloonContent: '<div class="map-descr"><h3>' + street + '</h3>' + content+'</div>',
                                    hintContent: '<div class="map-hint"><h3>' + street + '</h3>' + content + '</div>',
                                    iconContent: pos
                                }, {
                                    iconImageHref: '/templates/elektrosbit/img/ico_map.png',
                                    iconImageSize: [36, 34]
                                });
                                myMap.hint.show({
                                    //maxWidth: 30
                                });

                                myMap.geoObjects.add(myPlacemark);
                                
                }

                $("div.show_yamap").click(function() {
                    var street = $(this).text();
                    var coord_go = $(this).siblings('.addr');
                    
                    var addr = 'Россия ';
                    if ($(this).hasClass('other_city')) {
                        addr += 'Ленинградская область, ' + street;
                    }
                    else {
                        addr += ' Санкт-Петербург ' + street;
                    }
                    
                    var content = $(this).parent().find('.content_js').html();
                    var pos = $(this).attr('rel');
                    
                    
                    var shirota = $(this).parent().find('.shirota').text();
                    var dolgota = $(this).parent().find('.dolgota').text();
                    
                    
                  
                    if(shirota && dolgota){

                        if(shirota == '59.939095' && dolgota == '30.315868'){
                            setPlacemark(addr, street, content, '');
                            }
                            else {
                                //setPlacemark(addr, street, content, '',Array($(coord_go).attr('shirota'),$(coord_go).attr('dolgota')));
                                setPlacemarkCoords(Array(shirota,dolgota), street, content, '');
                                //console.log('with coords');
                            }
                        
                        
                        
                    }else{
                        setPlacemark(addr, street, content, '');
                        
                    }
                    
                    return false;
                });
                $(".mark_of_map").click(function() {
                    var street = $(this).children('div.addr').text();
                    var coord_go = $(this).children('div.addr');
                    var shirota = $(this).find('.shirota').text();
                    var dolgota = $(this).find('.dolgota').text();
                    /*  var param1 = street.toLowerCase().indexOf('г.') + 1;
                     var param2 = street.toLowerCase().indexOf('город') + 1;
                     var param4 = street.toLowerCase().indexOf('село') + 1;
                     var param5 = street.toLowerCase().indexOf('поселок') + 1;
                     var param6 = street.toLowerCase().indexOf('пгт') + 1;*/
                    var addr = 'Россия ';
                    if ($(this).hasClass('other_city')) {
                        addr += 'Ленинградская область, ' + street;
                    }
                    else {
                        addr += ' Санкт-Петербург ' + street;
                    }


                    // var shop=$(this).parent().children('h3.mark_of_map').text();
                    var content = $(this).find('.content_js').html(); //клик в списке

                    var myGeocoder = ymaps.geocode(addr);
                    myGeocoder.then(
                            function(res) {
                                var arCoord = Array();
                                
                                if(shirota && dolgota)
                                {
                                    arCoord[0]=shirota;
                                    arCoord[1]=dolgota;
                                }else{
                                    arCoord = res.geoObjects.get(0).geometry.getCoordinates();
                                }
                                
                                myMap.setCenter([parseFloat(arCoord[0]), parseFloat(arCoord[1])], 16, {
                                    checkZoomRange: true
                                });
                                 myMap.setZoom(16, {
                                 flying: true,
                                 duration: 1000
                                 });
                                /*  myMap.panTo(
                                 // Координаты нового центра карты
                                 [parseFloat(arCoord[0]), parseFloat(arCoord[1])], {
                                 
                                 callback: function() {
                                 myMap.setZoom(16, {
                                 flying: true,
                                 duration: 1000
                                 });
                                 }
                                 //flying: true,
                                 // delay: 400,
                                 //  duration: 1000
                                 });*/

                                myMap.balloon.open(
                                        // Позиция балуна
                                                [parseFloat(arCoord[0]), parseFloat(arCoord[1])], {
                                            // Свойства балуна
                                            contentBody: '<div class="map-descr"><h3>' + street + '</h3>' + content + '</div>'
                                        }, {
                                            // Опции балуна. В данном примере указываем, что балун не должен иметь кнопку закрытия.
                                            closeButton: true
                                        });

                                    });



                                    return false;
                                });
                                function SetTownMap(myMap, coord) {
                                    var minCoord1 = 10000000000000;
                                    var maxCoord1 = 0;
                                    var minCoord0 = 10000000000000;
                                    var maxCoord0 = 0;

                                    $(this).removeClass("selected");

                                    arCoord = coord.slice();

                                    if (arCoord[1] <= minCoord1)
                                        minCoord1 = arCoord[1];
                                    if (arCoord[1] >= maxCoord1)
                                        maxCoord1 = arCoord[1];

                                    if (arCoord[0] <= minCoord0)
                                        minCoord0 = arCoord[0];
                                    if (arCoord[0] >= maxCoord0)
                                        maxCoord0 = arCoord[0];

                                    // Создаем метку и задаем изображение для ее иконки
                                    myPlacemark = new ymaps.Placemark([parseFloat(arCoord[0]), parseFloat(arCoord[1])], {
                                        balloonContent: $(this).text()
                                    }, {
                                        iconImageHref: '/images/baloon.png', // картинка иконки
                                        iconImageSize: [80, 38], // размеры картинки
                                        iconImageOffset: [-40, -40] // смещение картинки
                                    });
                                    // Добавление метки на карту
                                    myMap.geoObjects.add(myPlacemark);

                                    myMap.setCenter([(parseFloat(minCoord0) + parseFloat(maxCoord0)) / 2, (parseFloat(minCoord1) + parseFloat(maxCoord1)) / 2]);
                                }

                                $("div.show_yamap").click();
                            },
                                    function(err) {
                                        // обработка ошибки
                                    }
                            );




                        }