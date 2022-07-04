function changeSorting(el,val,text)
{
  //console.log(val);
  $('input[name="sorting"]').val(val);
  $('.sorting-form .bx-filter-select-text').text(text);
  $(el).closest('.popup-window').hide();

  var _catalogItems = $('.catalog-items');

  if(val == 'priceup')
  {
    _catalogItems.find('.item').sort(function(a, b) {
      return +a.dataset.price - +b.dataset.price;
    }).appendTo(_catalogItems);
  }
  if(val == 'pricedown')
  {
    _catalogItems.find('.item').sort(function(a, b) {
      return +b.dataset.price - +a.dataset.price;
    }).appendTo(_catalogItems);
  }
  if(val == 'popular')
  {
    _catalogItems.find('.item').sort(function(a, b) {
      return +a.dataset.sort - +b.dataset.sort;
    }).appendTo(_catalogItems);
  }
}
(function($) {
	$.fn.filterCatalogItems = function(){
		//console.log(this);
    var _filters = {},
        _catalogItems = $('.catalog-items');
    $('.catalog-filter select').each(function(){
      if($(this).val().length > 0 && parseInt($(this).val()) !== 0 && $(this).attr('name') !== 'sorting')
      {
        _filters[$(this).attr('name')] = $(this).val();
      }
    });
    $('.catalog-filter select').each(function(){
      if($(this).attr('name') == 'sorting')
      {
        if($(this).val() == 'priceup')
        {
          _catalogItems.find('.item').sort(function(a, b) {
            return +a.dataset.price - +b.dataset.price;
          }).appendTo(_catalogItems);
        }
        if($(this).val() == 'pricedown')
        {
          _catalogItems.find('.item').sort(function(a, b) {
            return +b.dataset.price - +a.dataset.price;
          }).appendTo(_catalogItems);
        }
        if($(this).val() == 'popular')
        {
          _catalogItems.find('.item').sort(function(a, b) {
            return +a.dataset.sort - +b.dataset.sort;
          }).appendTo(_catalogItems);
        }
      }
    });
    //console.log(_filters);
    $.each(this, function(i, element){
      var _find = true;
      $.each(_filters, function(name, value){
        //console.log(name, value);
        if($(element).data('filter-' + name) == value)
        {
          return true;
        }
        _find = false;
      });
      if(_find == true)
      {
        $(element).show(500);
      }
      else
      {
        $(element).hide(500);
      }
    });
	}
  $(function() {
    $(document).ready(function() {
    	if($('.catalog-filter').length && $('.catalog-items').length && $('.catalog-items .item').length)
    	{
    		/*$('.catalog-filter select').on('change', function(){
    			$('.catalog-items .item').filterCatalogItems();
    		});*/
    	}
      $('.add-to-compare-list').on('click',function(){
        var _link = $(this);
        $.getJSON( $(this).attr('href') , {ajax_action: 'Y'} , function(result){
          if (typeof result == 'object')
          {
            _link.text('Добавлен к сравнению');
            BX.onCustomEvent('OnCompareChange');
          }
        });
        return false;
      });
  	});
  });
})(jQuery);