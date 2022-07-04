$(document).ready(function() {
	$('.open-mini-popup').each(function(){
		var _btn = $(this), _target = _btn.attr('href'), _modal = $(_target), _close = _modal.find('.close');
		_btn.on('click', function(){
			$('.mini-popup').removeClass('open');
			_modal.addClass('open');
			return false;
		});
		_close.on('click', function(){
			$('.mini-popup').removeClass('open');
			return false;
		});
	});
	$('.news-block-slider .bxslider').bxSlider({
		mode: 'vertical',
		pagerCustom: '.news-block-controls',
		controls: false,
		auto: true,
		pause: 5000,
		onSlideBefore: function($slideElement, oldIndex, newIndex){
			$('.news-block-controls a[data-slide-index='+oldIndex+']').parent().removeClass('active');
			$('.news-block-controls a[data-slide-index='+newIndex+']').parent().addClass('active');
		}
	});

	$('.secondary-slider .bxslider').bxSlider({
		pager: false,
		controls: true,
		auto: true
	});
	
	ResizeAction();
	
	Submenu();
	
	$(window).resize(function(){
		ResizeAction();
	});
	
	$('.addresses-list h4 a').click(function(event){
		
		event.preventDefault();
		$('.addresses-list h4 a').not($(this)).removeClass('active');
		$('.addresses-submenu').animate({	height: '0', marginBottom: '0'	}, 250, function() {			  });
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			//$(this).parents('li').find('.addresses-submenu').addClass('active');
			var actual_height=$(this).parents('li').find('.addresses-submenu ul').height()+'px';
			$(this).parents('li').find('.addresses-submenu').animate({	height: actual_height, marginBottom: '10px'	}, 250, function() {			  });
		}
		
	});
	
	$('.tabs-controls a').click(function(event){
		event.preventDefault();
		$(this).parents('ul').find('li').not($(this).parent()).removeClass('active');
		$(this).parent().addClass('active');
		$('.tab-element.active').not($(this).attr('href')).removeClass('active');
		$($(this).attr('href')).addClass('active');
	
	});
	
	$('.collapse').on('show', function(e){
		$.scrollTo($(this));
		//$('.collapse').animate({scrollTop: 500px}, 500);
	})
});	

function ResizeAction(){
	if($('.container-fluid').width()<481){
		$ci=$('.content-image');
		$('.content-image').detach();
		$ci.insertAfter('.content-text');
	} else if($('.container-fluid').width()>=481){
		$ci=$('.content-image');
		$('.content-image').detach();
		$ci.insertBefore('.content-text');
	}
}

function Submenu(){

	$('body').append('<div class="popup-overlay"></div>');
	
	$('nav .selected').click(function(){
		$('.submenu-holder').toggleClass('active');
		$('.popup-overlay').toggleClass('active');
		$(window).scrollTop($('nav').position().top);
	});
	
	$('.popup-overlay').click(function(){
		$('.submenu-holder').removeClass('active');
		$('.popup-overlay').removeClass('active');
	});	
	
	$('.submenu-holder-2>a').click(function(event){
		event.preventDefault();
		if($(this).parent().find('>ul').hasClass('active'))
			$(this).parent().find('ul').removeClass('active');
		else $(this).parent().find('>ul').addClass('active');
	});
	
}
