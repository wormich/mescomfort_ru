function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

$(function () {
    $('#cities option[value="Санкт-Петербург"]').hide();
    $("#mainslider").owlCarousel({
        items: 1,
        dots: true,
        autoplay: true,
        loop: true,
        onChanged: function (event) {
            ind = event.item.index;
            if (ind > 0) ind = ind - Math.ceil(event.item.count / 2);
            if (ind >= event.item.count) ind = 0;
            $('#slider_nav').find('.slider-navigation-item').removeClass('active').eq(ind).addClass('active');
        }
    });
    $("#info_slider").owlCarousel({
        items: 1,
        dots: true
    });
    $('.slider-navigation-item').on('click', function () {
        if (!$(this).hasClass('active')) {
            $('#slider_nav').find('.slider-navigation-item').removeClass('active');
            $(this).addClass('active');
            $('#mainslider').find('.owl-dot').eq($(this).index()).trigger('click');
        }
    })

    $('.main-news_link').each(function () {
        alink = $(this).find('a');
        atext = alink.text();
        alength = atext.length;
        aw = $(this).width();
        aw = Math.ceil((aw / 8) * 2) - 15;
        if (alength > aw) alink.text(atext.substr(0, aw) + ' ...');
    })

    mnh = 0;
    $('.main-news_item').each(function () {
        vh = $(this).height();
        if (vh > mnh) {
            mnh = vh;
        }
    })
    $('.main-news_item').height(mnh);
    $(window).resize(function () {
        mnh = 0;
        $('.main-news_item').height('auto');
        $('.main-news_item').each(function () {
            vh = $(this).height();
            if (vh > mnh) {
                mnh = vh;
            }
        })
        $('.main-news_item').height(mnh);
    })

    $('.mobile-menu_item.hasitems').on('click', function () {
        $(this).toggleClass('active');
        $(this).next('.mobile-submenu').slideToggle();

    })

    $('.mobile-head_menu-trigger').click(function () {
        $(this).toggleClass('active');
        $('#mobile_menu').toggleClass('active');
    })

    $('#callphone').mask('+7(999)999 99 99');

    var validator = $("#ordercall").validate({
        rules: {
            name: {required: true},
            phone: {required: true},
            code: {required: true}
        },
        messages: {
            name: {required: 'Обязательное поле'},
            phone: {required: 'Обязательное поле'},
            code: {required: 'Обязательное поле'}
        }
    });

    $('.modal-btn-open').click(function () {
        if ($('#office-modal').hasClass('hidden')) {
            if (window.innerWidth <= 1100) $('.modal-btn-open').css('opacity', '0');
            $('#office-modal').removeClass('hidden');
            $('body').addClass('noscroll');
            checkToggle();
        } else {
            $('.modal-btn-open').css('opacity', '1');
            $('#office-modal').addClass('hidden');
            $('body').removeClass('noscroll');
        }

        if ($('body').hasClass('noshow')) {
            var q = $('.icons label');
            for (var i = 0; i < q.length; i++) {
                $(q[i]).html($(q[i]).attr('title'));
            }
            $('.icons').addClass('filtered');
        } else {
            $('.icons label').html('');
            $('.icons').removeClass('filtered');
        }
    });
    $('.modal-btn-close').click(function () {
        $('.modal-btn-open').css('opacity', '1');
        $('#office-modal').addClass('hidden');
        $('body').removeClass('noscroll');

    });

    $('.modal-link').click(function (e) {
        e.preventDefault();
        $('#popap-modal').removeClass('hidden');

        if ($('body').hasClass('noshow')) {
            var q = $('.icons label');
            for (var i = 0; i < q.length; i++) {
                $(q[i]).html($(q[i]).attr('title'));
            }
            $('.icons').addClass('filtered');
        } else {
            $('.icons label').html('');
            $('.icons').removeClass('filtered');
        }
    });
    $('#popap-modal .modal-btn-close').click(function () {
        $('.modal-link').css('opacity', '1');
        $('#popap-modal').addClass('hidden');
        $('body').removeClass('noscroll');

    });

    var target = 'city';

    $('.tab-link').on('click', function () {
        target = $(this).attr('data-target');

        if (!$(this).hasClass('current')) {
            $('#tab-nav').find('.tab-link').removeClass('current');
            $(this).addClass('current');

            $('.tab-content').addClass('hidden');
            $('.' + target).removeClass('hidden');
        }

        checkWidth(target);

        if (target == 'region') {
            officeMap.setCenter([59.93772, 30.313622], 7);
        }

        if (target == 'city') {
            officeMap.setCenter([59.93772, 30.313622], 12);
        }
    });

    function checkWidth(target) {
        if (window.innerWidth <= 670) {
            if ($('#filter').hasClass('active')) {
                $('.filtered').removeClass('hidden');
                $('#map-container').addClass('hidden');
                if (target == 'city') {
                    $('.bordered').removeClass('hidden');
                }
            } else {
                $('.filtered').addClass('hidden');
                $('#map-container').removeClass('hidden');
                if (target == 'city') {
                    $('.bordered').addClass('hidden');
                }
            }
        } else {
            $('.filtered').removeClass('hidden');
            $('#map-container').removeClass('hidden');
            $('#filter').removeClass('active');
        }
    }

    checkWidth(target);

    $(window).resize(function () {
        checkWidth(target);
        if ((window.innerWidth > 1100) && !($('#office-modal').hasClass('hidden'))) $('.modal-btn-open').css('opacity', '1');
        if ((window.innerWidth <= 1100) && !($('#office-modal').hasClass('hidden'))) $('.modal-btn-open').css('opacity', '0');
    });

    $('#filter').click(function () {
        $(this).toggleClass('active');
        checkWidth(target);
    });

    $('.option').on('click', function () {
        if (!$(this).hasClass('active')) {
            $('#options').find('.option').removeClass('active');
            $(this).addClass('active');
            $('#officeList').toggleClass('hidden');
        }
    });
    $('.impaired').click(function () {
        var val = $('.blind-version-block').height();
        $('#office-modal').css('padding-top', val);
    });
    $('.reset').click(function () {
        $('#office-modal').css('padding-top', '0');
        $('.icons label').html('');
        $('.icons').removeClass('filtered');
    });

    $('.blind-version-block .img-block').click(function () {
        checkToggle();
    });
    $('.blind-version-block .color-block .ico').click(function () {
        checkToggle();
    });

    function checkToggle() {
        setTimeout(function () {
            if ($('body').hasClass('noshow')) {
                var q = $('.icons label');
                for (var i = 0; i < q.length; i++) {
                    $(q[i]).html($(q[i]).attr('title'));
                }
                $('.icons').addClass('filtered');
            } else {
                $('.icons > label').html('');
                $('.icons').removeClass('filtered');
            }
        }, 100)
    };

    $('#check5').click(function () {
        if (this.checked) {
            $('#check1').prop('disabled', true);
            $('#check3').prop('disabled', true);
        } else {
            $('#check1').prop('disabled', false);
            $('#check3').prop('disabled', false);
        }
    });
    $('.addresses-list h4 a').click(function (event) {

        event.preventDefault();
        $('.addresses-list h4 a').not($(this)).removeClass('active');
        $('.addresses-submenu').animate({height: '0', marginBottom: '0'}, 250, function () {
        });
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            //$(this).parents('li').find('.addresses-submenu').addClass('active');
            var actual_height = $(this).parents('li').find('.addresses-submenu ul').height() + 30 + 'px';
            $(this).parents('li').find('.addresses-submenu').animate({height: actual_height}, 250, function () {
            });
        }

    });

    $('.tabs-controls a').click(function (event) {
        event.preventDefault();
        $(this).parents('ul').find('li').not($(this).parent()).removeClass('active');
        $(this).parent().addClass('active');
        $('.tab-element.active').not($(this).attr('href')).removeClass('active');
        $($(this).attr('href')).addClass('active');

    });


    $(".setCatDisplay").click(function () {
        $(".setCatDisplay").removeClass("currentview");
        $(this).addClass("currentview");
        if (this.id == "asGrid") {
            $("#CatalogItems").removeClass("itemList");
            $("#CatalogItems").addClass("itemGrid");
            $.cookie('itemDisplay', 'asGrid');
        } else {
            $("#CatalogItems").removeClass("itemGrid");
            $("#CatalogItems").addClass("itemList");
            $.cookie('itemDisplay', 'asList');
        }
        return false;
    });

    $('.collapse').on('show', function (e) {
        $.scrollTo($(this));
        //$('.collapse').animate({scrollTop: 500px}, 500);
    })

    $('.open-mini-popup').each(function () {
        var _btn = $(this), _target = _btn.attr('href'), _modal = $(_target), _close = _modal.find('.close');
        _btn.on('click', function () {
            $('.mini-popup').removeClass('open');
            _modal.addClass('open');
            return false;
        });
        _close.on('click', function () {
            $('.mini-popup').removeClass('open');
            return false;
        });
    });

    $('.faq-box').find('h3').on('click', function () {
        $(this).next().slideToggle();
    })

    $(window).scroll(function () {
        bh = $(window).height();
        if ($('body,html').scrollTop() > (bh * .5)) $('.totop').fadeIn();
        else $('.totop').fadeOut();
    })


})


$(window).load(function (e) {
    if ($.cookie('itemDisplay') && $(".setCatDisplay").length != 0) {
        $(".setCatDisplay").removeClass("currentview");
        if ($.cookie('itemDisplay') == "asGrid") {
            $("#CatalogItems").removeClass("itemList");
            $("#CatalogItems").addClass("itemGrid");
            $("#asGrid").addClass("currentview");
        } else {
            $("#CatalogItems").removeClass("itemGrid");
            $("#CatalogItems").addClass("itemList");
            $("#asList").addClass("currentview");
        }
        return false;
    }
});
$(document).ready(function () {

    $('.js-request-form').on('submit', function (e) {
        e.preventDefault();

        $.post(window.location, $('.js-request-form').serializeArray(), function (res) {

            if (res.status === true) {
                $('.js-request-form .alert-success').fadeIn();

                $('.js-request-form input:visible').val('');
            } else {
                $('.js-request-form .alert-danger').fadeIn();
            }

            setTimeout(function () {
                $('.alert').fadeOut();
            }, 3000);
        });
    });

    $('.js-emr_services_item').on('click', function (e) {
        e.preventDefault();

        $('#service-modal').removeClass('hidden');

        $('#service-modal h1').html($(this).data('name'));
        $('#service-modal .modal-content-text').html($(this).data('text'));
    });

    $('.serice-btn-close').click(function () {
        $('#service-modal').addClass('hidden');
        $('body').removeClass('noscroll');

    });
});
