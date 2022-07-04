ymaps.ready(init);
var officeMap, clusterer;
var targetTab = 'city';
var dataRegion;

function init() {
	
	if($(window).width() > 1100) {
		var wiclu = 450;
	} else {
		var wiclu = 250;
	}
	
    officeMap = new ymaps.Map("officeMap", {
        center: [59.93772, 30.313622],
        zoom: 12,
        controls: ['zoomControl']
    });

    checkRegion();

    $('.tab-link').on('click', function () {
        checkRegion();
        $('#check1').prop('disabled', false);
        $('#check3').prop('disabled', false);

        $('#check1').prop('checked', true);
        $('#check2').prop('checked', true);
        $('#check3').prop('checked', true);
        $('#check4').prop('checked', false);
        $('#check5').prop('checked', false);

        $('#eye').prop('checked', false);
        $('#ear').prop('checked', false);
        $('#stick').prop('checked', false);
        $('#disable').prop('checked', false);

	$('.select-office').val('');

    });

    function checkRegion() {
        targetTab = $('.tab-link.current').data('target');
        dataRegion = [];
        var j = 0;

        for (var i = 0; i < data.length; i++) {
            if ((targetTab == 'region')) {
                if (data[i].city != "Санкт-Петербург") {
                    dataRegion[j] = data[i];
                    j++;
                }
            }
            else {
		if (data[i].city == "Санкт-Петербург") {
                    dataRegion[j] = data[i];
                    j++;
                }

            }
        }

        createPlacemarks(dataRegion);
    }
	
    function createPlacemarks(dataRegion) {
        officeMap.geoObjects.removeAll();
        $('#officeTable').html('');

        clusterer = new ymaps.Clusterer({
            clusterDisableClickZoom: true,
            preset: 'islands#blueCircleIcon',
			clusterBalloonContentLayoutWidth: wiclu,
        });

        for (var i = 0; i < dataRegion.length; i++) {
            createItem(dataRegion[i]);
        }
        officeMap.geoObjects.add(clusterer);

        $('.select-office').on('change', function () {
            setTimeout(function(){
                if (officeMap.geoObjects.getBounds() != null) officeMap.setBounds(officeMap.geoObjects.getBounds(), { checkZoomRange: true });
            }, 100);
    	});

    }

    function createItem(item) {
	info = '';
        visa = '';

        for (var i = 0; i < item.items.length; i++) {

			if (item.items[i].name == 'Платежный терминал') {
				var divClass = 'paymentTerminal';
			} else if (item.items[i].name == 'Центр приема платежей') {
				var divClass = 'paymentCenter';
			} else {
				var divClass = 'customerHall';
			}

            if (item.items[i].name == 'Платежный терминал' && item.visaTerminal) {
                visa = '<div class="visaIcon"></div>';
            }
            else if (item.items[i].name == 'Центр приема платежей' && item.visaCenter) {
                visa = '<div class="visaIcon"></div>';
            }
            else {
		visa = '';
	    }
            info += '<div class="' + divClass + '">' + item.items[i].name + visa + ' ' + '<small><span>Режим работы: </span>' + item.items[i].hours + '</small>' + '</div>';
        }
		

		if(info.length == 0) {

			info = '<div>Нет информации о работе отделения</div>'

		}

	str = '';
        if (item.eye) str += '<div class="eyeIcon"></div>';
        if (item.ear) str += '<div class="earIcon"></div>';
        if (item.stick) str += '<div class="stickIcon"></div>';
        if (item.disable) str += '<div class="disableIcon"></div>';

        office = new ymaps.Placemark(

            item.center,

            {
				
                clusterCaption: item.adress,

                balloonContent: '<div class="balloon"><div class="row row-balloon"><div class="balloonAdress">' + item.adress + '</div>' + '<div class="officeIcons">' + str + '</div></div>' + '<div class="balloonInfo">' + info + '</div></div>',
				
				
                
		city: item.city,

                region: item.region,

                subway: item.subway,

                items: item.items,

                visaTerminal: item.visaTerminal,

                visaCenter: item.visaCenter,

                allWeek: item.allWeek,

                allDay: item.allDay,

                eye: item.eye,

                ear: item.ear,

                stick: item.stick,

                disable: item.disable



            },

            {

                iconLayout: 'default#image',

                iconImageHref: '/bitrix/templates/pes/css/new/img/ymapico.png',

                iconImageSize: [36, 34],

                iconImageOffset: [-10, -15],

            });

        officeItem = $('<div class="officeItem"></div>');

		var region = '';

		if (targetTab == 'city') region = '<div class="officeRegion">' + '<div>' + item.region + ' район </div>' + '<small class="' + item.line + '">' + item.subway + '</small>' + '</div>';

		officeItemInner = $(

            '<div class="row"><div class="officeAdress">' + item.adress + '</div>' +

            '<div class="officeIcons">' + str + '</div></div>' +

            '<div class="officeInfo">' + info + '</div>' + region

        );

        setupControls(office, info, officeItem, clusterer);

        officeItemInner.appendTo(officeItem);

        officeItem.appendTo($('#officeTable'));



        officeMap.geoObjects.add(office);

        clusterer.add(office);

    }



    function setupControls(office, info, officeItem, clusterer) {
        var selected;
        var selectData;
        var checkRes;
        var selectRes;
		var sRegion = ''; 
		var sSubway = ''; 
		var sCity = '';

        $('.select-office').on('change', function () {
            selected = $(this).val();

            if (this.id == 'cities') {
                sCity = selected;
                selectRes = sCity == office.properties._data.city || sCity == '';
            } else {
                if (this.id == 'subways') {
                    sSubway = selected;
                    if (sSubway != '' && sSubway == office.properties._data.subway) {
                        sRegion = office.properties._data.region;
                        $('#regions').val(sRegion);
                    }
                }

                if (this.id == 'regions') {
                    sRegion = selected;
                    sSubway = '';
                    $('#subways').val(sSubway);
                }

                selectRes = ((sRegion == office.properties._data.region || sRegion == '') &&
                    (sSubway == office.properties._data.subway || sSubway == ''));
            }	    

            if (checkRes === undefined) checkRes = true;

            checkAll(office, info, officeItem, clusterer, checkRes, selectRes);
        });

        $('.checkbox').on('change', function () {
            //check name
            arr = [];
            $('.check-name').each(function(){
                if ($(this).is(':checked')) {
                    arr[arr.length] = $(this).val();
                }
            });
            arr2 = [];
            var item2 = office.properties._data.items;
            for (i = 0; i < item2.length; i++) {
                arr2[arr2.length] = item2[i].name;
            }
            res1 = false;
            for (i = 0; i < arr.length; i++) {
                if (arr2.indexOf(arr[i]) != -1) res1 = res1 || true;
                else res1 = res1 || false;
            }
            if (!arr[0] && !arr[1] && !arr[2]) res1 = true;

            //check hours
            arr = [];
            $('.check-hours').each(function(){
                arr[arr.length] = $(this).is(':checked');
            });
            res2 =
                office.properties._data.allWeek && arr[0] ||
                office.properties._data.allDay && arr[1];
            if (arr[0] && arr[1]) res2 = office.properties._data.allWeek && office.properties._data.allDay; //18.04.2018
            if (!arr[0] && !arr[1]) res2 = true;

            //check icons
            icons = [];
            iconsTrue = [];
            prop = [office.properties._data.eye, office.properties._data.ear, office.properties._data.stick, office.properties._data.disable];
            j = 0;
            i = 0;
            $('.check-icons').each(function(){
                icons[icons.length] = $(this).is(':checked');
                if ($(this).is(':checked')) {
                    iconsTrue[j] = i;
                    j++;
                }
                i++;
            });
            res3 = true;
            if (iconsTrue.length == 0) res3 = true;
            else {
                for (var i = 0; i < iconsTrue.length; i++) {
                    res3 *= prop[iconsTrue[i]];
                }
            }

            checkRes = res1 && res2 && res3;

            if (selectRes === undefined) selectRes = true;

            checkAll(office, info, officeItem, clusterer, checkRes, selectRes);
        });
        function checkAll(office, info, officeItem, clusterer, checkRes, selectRes) {
            if (selectRes && checkRes) {
				var b_content = office.properties.get('balloonContent').split('<div class="balloonInfo">');
				var office_b_items = b_content[1].split('</div>');
				for (i = 0; i < office_b_items.length; i++) {
						if(office_b_items[i].search('paymentTerminal') != -1){
							var paymentTerminal = office_b_items[i];
						}
						if(office_b_items[i].search('paymentCenter') != -1){
							var paymentCenter = office_b_items[i];
						}
						if(office_b_items[i].search('customerHall') != -1){
							var customerHall = office_b_items[i];
						}
				}
				if(paymentCenter === undefined) {
					paymentCenter = '<div class="paymentCenter">';
				}
				if(paymentTerminal === undefined) {
					paymentTerminal = '<div class="paymentTerminal">';
				}
				if(customerHall === undefined) {
					customerHall = '<div class="customerHall">';
				}
if($('#check1').is(':checked') && $('#check2').is(':checked') == false && $('#check3').is(':checked') == false) {
				office.properties.set('balloonContent', b_content[0] + '<div class="balloonInfo">' + paymentCenter + '</div>' + paymentTerminal + '</div>' + customerHall + '</div></div></div>');	
}
if($('#check2').is(':checked') && $('#check1').is(':checked') == false && $('#check3').is(':checked') == false) {
				office.properties.set('balloonContent', b_content[0] + '<div class="balloonInfo">' + paymentTerminal + '</div>' + paymentCenter + '</div>' + customerHall + '</div></div></div>');	
}
if($('#check3').is(':checked') && $('#check2').is(':checked') == false && $('#check1').is(':checked') == false) {
				office.properties.set('balloonContent', b_content[0] + '<div class="balloonInfo">' + customerHall + '</div>' + paymentCenter + '</div>' + paymentTerminal + '</div></div></div>');	
}
office.options.set('visible', true);
officeItem.show();
clusterer.add(office);
            }
            else {
                office.options.set('visible', false);
                officeItem.hide();
                clusterer.remove(office);
            }
        }
		
    }
}

$(document).ready(function() {
	$('.check-name').change(function() {
		if($('#check1').is(':checked') && $('#check2').is(':checked') == false && $('#check3').is(':checked') == false) {

			$('.officeInfo').each(function() {
				var paymentCenter = $(this).find($('.paymentCenter')).html();
				var paymentTerminal = $(this).find($('.paymentTerminal')).html();
				var customerHall = $(this).find($('.customerHall')).html();

				if(paymentCenter === undefined) {
					paymentCenter = "";
				}
				if(paymentTerminal === undefined) {
					paymentTerminal = "";
				}
				if(customerHall === undefined) {
					customerHall = "";
				}

				$(this).html('<div class="paymentCenter">' + paymentCenter + '</div>' + '<div class="paymentTerminal">' + paymentTerminal + '</div>' + '<div class="customerHall">' + customerHall + '</div>');	
			});

		}

		if($('#check1').is(':checked') == false && $('#check2').is(':checked')  && $('#check3').is(':checked') == false) {
			$('.officeInfo').each(function() {
				var paymentCenter = $(this).find($('.paymentCenter')).html();
				var paymentTerminal = $(this).find($('.paymentTerminal')).html();
				var customerHall = $(this).find($('.customerHall')).html();

				if(paymentCenter === undefined) {
					paymentCenter = "";
				}
				if(paymentTerminal === undefined) {
					paymentTerminal = "";
				}
				if(customerHall === undefined) {
					customerHall = "";
				}

				$(this).html('<div class="paymentTerminal">' + paymentTerminal + '</div>' + '<div class="paymentCenter">' + paymentCenter + '</div>' + '<div class="customerHall">' + customerHall + '</div>');
			});
		}

		if($('#check1').is(':checked') == false && $('#check2').is(':checked') == false && $('#check3').is(':checked')) {
			$('.officeInfo').each(function() {
				var paymentCenter = $(this).find($('.paymentCenter')).html();
				var paymentTerminal = $(this).find($('.paymentTerminal')).html();
				var customerHall = $(this).find($('.customerHall')).html();

				if(paymentCenter === undefined) {
					paymentCenter = "";
				}
				if(paymentTerminal === undefined) {
					paymentTerminal = "";
				}
				if(customerHall === undefined) {
					customerHall = "";
				}

				$(this).html('<div class="customerHall">' + customerHall + '</div>' + '<div class="paymentCenter">' + paymentCenter + '</div>' + '<div class="paymentTerminal">' + paymentTerminal + '</div>');
			});
		}
	});
});