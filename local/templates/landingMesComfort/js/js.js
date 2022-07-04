/*
CSS Browser Selector 0.6.3
Originally written by Rafael Lima (http://rafael.adm.br)
http://rafael.adm.br/css_browser_selector
License: http://creativecommons.org/licenses/by/2.5/
Co-maintained by:
https://github.com/verbatim/css_browser_selector
*/
showLog=true;function log(m){if(window.console&&showLog)console.log(m)}
function css_browser_selector(u){var uaInfo={},screens=[320,480,640,768,1024,1152,1280,1440,1680,1920,2560],allScreens=screens.length,ua=u.toLowerCase(),is=function(t){return RegExp(t,"i").test(ua)},version=function(p,n){n=n.replace(".","_");var i=n.indexOf("_"),ver="";while(i>0){ver+=" "+p+n.substring(0,i);i=n.indexOf("_",i+1)}ver+=" "+p+n;return ver},g="gecko",w="webkit",c="chrome",f="firefox",s="safari",o="opera",m="mobile",a="android",bb="blackberry",lang="lang_",dv="device_",html=document.documentElement,
b=[!/opera|webtv/i.test(ua)&&/msie\s(\d+)/.test(ua)||/trident\/.*rv:([0-9]{1,}[\.0-9]{0,})/.test(ua)?"ie ie"+(/trident\/4\.0/.test(ua)?"8":RegExp.$1=="11.0"?"11":RegExp.$1):is("firefox/")?g+" "+f+(/firefox\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua)?" "+f+RegExp.$2+" "+f+RegExp.$2+"_"+RegExp.$4:""):is("gecko/")?g:is("opera")?o+(/version\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua)?" "+o+RegExp.$2+" "+o+RegExp.$2+"_"+RegExp.$4:/opera(\s|\/)(\d+)\.(\d+)/.test(ua)?" "+o+RegExp.$2+" "+o+RegExp.$2+"_"+RegExp.$3:""):is("konqueror")?
"konqueror":is("blackberry")?bb+(/Version\/(\d+)(\.(\d+)+)/i.test(ua)?" "+bb+RegExp.$1+" "+bb+RegExp.$1+RegExp.$2.replace(".","_"):/Blackberry ?(([0-9]+)([a-z]?))[\/|;]/gi.test(ua)?" "+bb+RegExp.$2+(RegExp.$3?" "+bb+RegExp.$2+RegExp.$3:""):""):is("android")?a+(/Version\/(\d+)(\.(\d+))+/i.test(ua)?" "+a+RegExp.$1+" "+a+RegExp.$1+RegExp.$2.replace(".","_"):"")+(/Android (.+); (.+) Build/i.test(ua)?" "+dv+RegExp.$2.replace(/ /g,"_").replace(/-/g,"_"):""):is("chrome")?w+" "+c+(/chrome\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua)?
" "+c+RegExp.$2+(RegExp.$4>0?" "+c+RegExp.$2+"_"+RegExp.$4:""):""):is("iron")?w+" iron":is("applewebkit/")?w+" "+s+(/version\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua)?" "+s+RegExp.$2+" "+s+RegExp.$2+RegExp.$3.replace(".","_"):/ Safari\/(\d+)/i.test(ua)?RegExp.$1=="419"||(RegExp.$1=="417"||(RegExp.$1=="416"||RegExp.$1=="412"))?" "+s+"2_0":RegExp.$1=="312"?" "+s+"1_3":RegExp.$1=="125"?" "+s+"1_2":RegExp.$1=="85"?" "+s+"1_0":"":""):is("mozilla/")?g:"",is("android|mobi|mobile|j2me|iphone|ipod|ipad|blackberry|playbook|kindle|silk")?
m:"",is("j2me")?"j2me":is("ipad|ipod|iphone")?(/CPU( iPhone)? OS (\d+[_|\.]\d+([_|\.]\d+)*)/i.test(ua)?"ios"+version("ios",RegExp.$2):"")+" "+(/(ip(ad|od|hone))/gi.test(ua)?RegExp.$1:""):is("playbook")?"playbook":is("kindle|silk")?"kindle":is("playbook")?"playbook":is("mac")?"mac"+(/mac os x ((\d+)[.|_](\d+))/.test(ua)?" mac"+RegExp.$2+" mac"+RegExp.$1.replace(".","_"):""):is("win")?"win"+(is("windows nt 6.2")?" win8":is("windows nt 6.1")?" win7":is("windows nt 6.0")?" vista":is("windows nt 5.2")||
is("windows nt 5.1")?" win_xp":is("windows nt 5.0")?" win_2k":is("windows nt 4.0")||is("WinNT4.0")?" win_nt":""):is("freebsd")?"freebsd":is("x11|linux")?"linux":"",/[; |\[](([a-z]{2})(\-[a-z]{2})?)[)|;|\]]/i.test(ua)?(lang+RegExp.$2).replace("-","_")+(RegExp.$3!=""?(" "+lang+RegExp.$1).replace("-","_"):""):"",is("ipad|iphone|ipod")&&!is("safari")?"ipad_app":""];function screenSize(){var w=window.outerWidth||html.clientWidth;var h=window.outerHeight||html.clientHeight;uaInfo.orientation=
w<h?"portrait":"landscape";html.className=html.className.replace(/ ?orientation_\w+/g,"").replace(/ [min|max|cl]+[w|h]_\d+/g,"");for(var i=allScreens-1;i>=0;i--)if(w>=screens[i]){uaInfo.maxw=screens[i];break}widthClasses="";for(var info in uaInfo)widthClasses+=" "+info+"_"+uaInfo[info];html.className=html.className+widthClasses;return widthClasses}window.onresize=screenSize;screenSize();function retina(){var r=window.devicePixelRatio>1;if(r)html.className+=" retina";else html.className+=" non-retina"}
retina();var cssbs=b.join(" ")+" js ";html.className=(cssbs+html.className.replace(/\b(no[-|_]?)?js\b/g,"")).replace(/^ /,"").replace(/ +/g," ");return cssbs}css_browser_selector(navigator.userAgent);

$(function(){
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || document.documentElement.clientWidth < 767;

    if(document.createElement("input").placeholder == undefined){
        $('[placeholder]').each(function(){
            $(this).val($(this).attr('placeholder'))
        }).focus(function(){
            if($(this).val() == $(this).attr('placeholder'))
                $(this).val('')
        }).blur(function(){
            if(!$(this).val())
                $(this).val($(this).attr('placeholder'))
        });
    }

    $('.slider a[rel]').fancybox({
		padding: isMobile ? 10 : 20,
		margin: isMobile ? 0 : 10,
		type: 'image',
		helpers: {
			overlay: {
				locked: false
			}
		}
	});

	$('a.open-popup').click(function(e){
		e.preventDefault();
		$.fancybox.open($(this).attr('href'), {
			padding: isMobile ? 10 : 20,
			margin: isMobile ? 0 : 10,
			type: 'inline',
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
	});
	
	$(window).scroll(function(){
		$(this).scrollTop() > 0 ? $('header').addClass('_fly') : $('header').removeClass('_fly');
	})
	.scroll();

	$('a.b-section').click(function(e){
		e.preventDefault();
		var position = $('a[name=' + $(this).attr('href').replace('#', '') + ']').offset().top;
		$('html, body').animate({scrollTop: position}, 750);
	});

    $('.btn-to-actions').click(function(e){
        e.preventDefault();
        var position = $('#block-actions').offset().top - 130;
        $('html, body').animate({scrollTop: position}, 750);
    });

    $('a.btn-order').click(function(e){
        e.preventDefault();
        $.fancybox.open('#order-popup', {
            padding: isMobile ? 10 : 20,
            margin: isMobile ? 0 : 10,
            type: 'inline',
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });
        $('#order-popup select[name=service]').val($(this).data('service'));
    });
	
	$('.slider').each(function(){
		var columns = Number($(this).attr('data-column')) || 1;
		var pan = Number($(this).attr('data-pan')) || 0, deltapan = 0;
		if(isMobile){
			columns = 1;
		}
		var sld = $(this).find('.slider__sld');
		var items = sld.find('.slider__item');
		var slides = Math.ceil(items.size()/columns);
		function tR(){
			items.width(sld.width()/columns - pan + pan/columns);
		}
		tR();
		$(window).resize(tR);

		if(items.size()>columns){
			
				var str = '<div class="bulls">';
				for(var i=0;i<slides;i++){
					str += '<a href="#'+ i +'" class="'+ (i==0?"active":"") +'"></a>';
				}
				str += '</div>';
				str += '<a href="#prev" class="prev icon-larr"></a><a href="#next" class="next icon-rarr"></a>';
			
				$(str).appendTo($(this));
			
				var bulls = $(this).find('.bulls');

				$(this).find('a.next').click(function(e){
					e.preventDefault();
					bulls.find('a.active').next().click();
				});
			
				$(this).find('a.prev').click(function(e){
					e.preventDefault();
					bulls.find('a.active').prev().click();
				});
			
				bulls.find('a').click(function(e){
					e.preventDefault();
					if(!$(this).hasClass('active')){
						$(this).addClass('active').siblings().removeClass('active');

						sld.animate({scrollLeft: (sld.width() + pan)*$(this).index()}, isMobile ? 300 : 750);
					}
				});
		}
	});
	
	window.thanks = function(){
		$.fancybox.open('#thanks-popup', {
			padding: isMobile ? 10 : 20,
			margin: isMobile ? 0 : 10,
			type: 'inline',
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
	};

    window.thanks2 = function(){
        $.fancybox.open('#thanks-popup2', {
            padding: isMobile ? 10 : 20,
            margin: isMobile ? 0 : 10,
            type: 'inline',
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });
    };

    orderInit();
    reviewInit();

    $(window).load(function(){
        if (window.location.hash && $(window.location.hash).length) {
            $("html, body").animate({ scrollTop: $(window.location.hash).offset().top }, 100);
        }

        if (window.location.hash && window.location.hash == '#order') {
            $.fancybox.open('#order-popup', {
                padding: isMobile ? 10 : 20,
                margin: isMobile ? 0 : 10,
                type: 'inline',
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            });
        }
    });
	
	function changeCity(value){
		$('.city-select').each(function(){
			this.value = value;
		});

		if (!value || value == 'другой' || value == 'Выбрать город') {
			$('.city-status').html('Выезд платный. <br>Оператор свяжется с вами в течение <span style="white-space:nowrap;">2-х часов</span>');
		} else {
			$('.city-status').html('<div>Выезд бесплатный.</div>');
		}
	}
	
	$('.city-select').change(function(){
		changeCity($(this).val());
		if (/Другой/gi.test($(this).val())) {
			$('.city-select').css('font-weight', 'bold');
		} else {
			$('.city-select').css('font-weight', 'normal');
		}
	});

});

function orderInit()
{
    $(".order-form").validate({
        submitHandler: function(form) {
            $(form).find('input[type=submit]').attr('disabled', 'disabled');
            $(form).find('input[type=submit]').val('Отправляем...');

            $('.order-form').ajaxSubmit({
                url: 'order/index.php?'+$('.order-form').serialize(),
                type: 'get',
                success: function(data) {
                    console.log(JSON.parse(data));
                    data=JSON.parse(data);
                    if (data) {

                        $(form).find('input[type=submit]').val('Отправить');
                        $(form).find('input[type=submit]').removeAttr('disabled');
                        if (data.type == "error") {
                            alert(data.data);
                        }
                        else if(data.type == "captcha_error"){
                            $(form).find(".captcha-error--js").show();
                        }
                        if (data.type == "ok") {
                            $('.order-form').resetForm();
                            thanks();
														yaCounter38950510.reachGoal('zakaz'); 
                            //$('.order-form .alert-ok').html('Заявка отправлена.');
                            //setTimeout(function(){
                            //    $('.order-form .alert-ok').html('');
                            //}, 7000);
                        }
                    }
                }
            });
        },
        rules: {
            name: "required",
            phone: "required",
            agreement: "required",
            email: {
                email: true
            }
        },
        messages: {
            name: "Это поле обязательно для заполнения",
            phone: "Это поле обязательно для заполнения",
            agreement: "Для продолжения, вам необходимо дать согласие на обработку персональных данных",
            email: {
                //required: "Это поле обязательно для заполнения",
                email: "Проверьте, пожалуйста, правильность ввода E-mail"
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "agreement") {
                var target_label = element.closest(".tsaf-agreement--js").find(".tsaf-agreement__label--js");
                error.insertAfter(target_label);
            } else {
                error.insertAfter(element);
            }
        }
    });

    $(".order-form2").validate({
        submitHandler: function(form) {
            $(form).find('input[type=submit]').attr('disabled', 'disabled');
            $(form).find('input[type=submit]').val('Отправляем...');
            $('.order-form2').ajaxSubmit({

                url: 'order/index.php?'+ $(".order-form2").serialize(),
                type: 'get',
                success: function(data) {
                    console.log(data);
                    data=JSON.parse(data);
                    if (data) {
                        $(form).find('input[type=submit]').val('Отправить');
                        $(form).find('input[type=submit]').removeAttr('disabled');
                        if (data.type == "error") {
                            alert(data.data);
                        }
                        else if(data.type == "captcha_error"){
                            $(form).find(".captcha-error--js").show();
                        }
                        if (data.type == "ok") {
                            $('.order-form2').resetForm();
                            thanks();
                        }
                    }
                }
            });
        },
        rules: {
            name: "required",
            phone: "required",
            email: {
                email: true
            }
        },
        messages: {
            name: "Это поле обязательно для заполнения",
            phone: "Это поле обязательно для заполнения",
            email: {
                //required: "Это поле обязательно для заполнения",
                email: "Проверьте, пожалуйста, правильность ввода E-mail"
            }
        }
    });
}


function reviewInit()
{
    $(".review-form").validate({
        submitHandler: function(form) {
            $(form).find('input[type=submit]').attr('disabled', 'disabled');
            $(form).find('input[type=submit]').val('Отправляем...');
            $('.review-form').ajaxSubmit({
                url: 'order/report.php?'+$(".review-form").serialize(),
                type: 'get',
                success: function(data) {
                    console.log(data);
                    data=JSON.parse(data);
                    if (data) {
                        $(form).find('input[type=submit]').val('Отправить');
                        $(form).find('input[type=submit]').removeAttr('disabled');
                        if (data.type == "error") {
                            alert(data.data);
                        }
                        else if(data.type == "captcha_error"){
                            $(form).find(".captcha-error--js").show();
                        }
                        if (data.type == "ok") {
                            $('.order-form').resetForm();
                            thanks2();
                            //$('.order-form .alert-ok').html('Заявка отправлена.');
                            //setTimeout(function(){
                            //    $('.order-form .alert-ok').html('');
                            //}, 7000);
                        }
                    }
                }
            });
        },
        rules: {
            name: "required",
            phone: "required",
            msg: "required"
        },
        messages: {
            name: "Это поле обязательно для заполнения",
            phone: "Это поле обязательно для заполнения",
            msg: "Это поле обязательно для заполнения"
        }
    });
}