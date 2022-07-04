    $(document).ready(function(){
        /*проверяем наличия кук*/
        if($.cookie('version_class') !== undefined && $.cookie('version_class') != ''){
            $('body').attr('class', $.cookie('version_class')); /*если есть грузим и показываем панель*/
            $('.blind-version-block').show();
            $('.impaired').hide();
            $('.blind-version-block .ico').removeClass('sel');
            setTimeout(function() {
                // показ изображения
                if ( $('body').hasClass('noshow') ) {
                    $('.img-block .toggle').addClass('noshow');
                    $('.img-on').hide();
                    $('.img-off').show();
                } else {
                    $('.img-block .toggle').removeClass('noshow');
                    $('.img-on').show();
                    $('.img-off').hide();
                }
                // селекты панели
                cl = $('body').attr('class').split(' ');
                for (var i = cl.length - 1; i >= 0; i--) {
                    $('[data-size= \"'+cl[i]+'\"]').addClass('sel');
                }
            },100)
        } else {
            $('.blind-version-block').hide();
            $('.impaired').show();
        }


        
        $('.blind-version-block .reset').click(function(){
            $('body').removeClass('blackwhite whiteblack blue times noshow s14 s16 s18 w1 w2 w3 arial showimg')
            $('.wrapper').removeClass('noshow')
            $('.blind-version-block').hide();
            $('.impaired').show();
            $.cookie('version_class', '', { path: '/' }); /*очистка кук*/
        })
        
        $('.impaired').click(function(){
            $('body').addClass('whiteblack s14 w1 arial showimg');             
            $('.blind-version-block').show();
            $('.impaired').hide(); 
            saveCook();
            return false 
        })
        
        $('.blind-version-block .ico').click(function(){
            $(this).parent().find('.ico').removeClass('sel');
            $(this).addClass('sel')
        })
        
        $('.blind-version-block .img-block').click(function(){ // изображения
            $(this).find('.toggle').toggleClass('noshow');
            $(this).find('.toggle b').toggle();
            $('body').toggleClass('noshow');
            $('body').toggleClass('showimg');
            saveCook();
        })
        
        $('.blind-version-block .color-block .ico').on('click',function(){  // цвет
            sclass = $(this).data('size'); 
            cl = $('body').attr('class').split(' ');
            $('body').attr('class', sclass+' '+cl[1]+' '+cl[2]+' '+cl[3]+' '+cl[4] );
            saveCook();             
        })
        
        $('.blind-version-block .size-block .ico').on('click',function(){ // размер
            sclass = $(this).data('size');
            cl = $('body').attr('class').split(' '); 
            $('body').attr('class', cl[0]+' '+sclass+' '+cl[2]+' '+cl[3]+' '+cl[4] );
            saveCook();                             
        })
        
        $('.blind-version-block .interval-block .ico').on('click',function(){ // интервал
            sclass = $(this).data('size');
            cl = $('body').attr('class').split(' '); 
            $('body').attr('class', cl[0]+' '+cl[1]+' '+sclass+' '+cl[3]+' '+cl[4] );
            saveCook();
        })
        
        $('.blind-version-block .font-block .ico').on('click',function(){ // шрифт
            sclass = $(this).data('size'); 
            cl = $('body').attr('class').split(' '); 
            $('body').attr('class', cl[0]+' '+cl[1]+' '+cl[2]+' '+sclass+' '+cl[4] );
            saveCook();
        })

        /* функция записи кук, вызывать при изменении атрибута "class" у body */
        var saveCook = function() {
            var version_class = $('body').attr('class');
            $.cookie('version_class', version_class, { expires: 30, path: '/' });
        }   
    })