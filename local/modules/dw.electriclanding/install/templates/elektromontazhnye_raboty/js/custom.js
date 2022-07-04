jQuery.cookie = function (key, value, options) {

    // key and value given, set cookie...
    if (arguments.length > 1 && (value === null || typeof value !== "object")) {
        options = jQuery.extend({}, options);

        if (value === null) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
$(document).ready(function() {
  $("a[rel=example_group]").fancybox({
    'transitionIn': 'none',
    'transitionOut': 'none',
    'titlePosition': 'over',
    'titleFormat': function(title, currentArray, currentIndex, currentOpts) {
        return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
    }
  });
  $('#jobs_form').validate({
    errorLabelContainer: "#errors_overlay",
    highlight: function(element, errorClass) {
        $(element).addClass('error_valid');
    },
    showErrors: function(errorMap, errorList) {
        this.defaultShowErrors();
        $('#errors_overlay').css('display', 'none');
        return true;
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('error_valid');
    }
  });
  
  $('#virtual_form').validate({
    errorLabelContainer: "#errors_overlay",
    highlight: function(element, errorClass) {
        $(element).addClass('error_valid');
    },
    showErrors: function(errorMap, errorList) {
        this.defaultShowErrors();
        $('#errors_overlay').css('display', 'none');
        return true;
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('error_valid');
    }
  });

  $('.personal_data_js').click(function() {
    var sub = $('#jobs_form_sub');
    sub.toggleClass('opas');
  });
  $('#jobs_form_sub').click(function() {
    if ($(this).hasClass('opas')) {
        return false;
    }
    else {
        return true;
    }
  });
    
  $('#virtual_form').find('input[type=submit]').on('click', function() {
  	var allow = true;
  	$('#virtual_form').find('.required').each(function() {
  		if($(this).children('option:selected').attr('class') == 'err' || $(this).val().length == 0) {
  			$(this).addClass('error_valid');
    		allow = false;
    	}
  	});
  	if(allow == false) return false;
  	
  });

  $(".setCatDisplay").click(function(){
    $(".setCatDisplay").removeClass("currentview"); 
    $(this).addClass("currentview");
    if(this.id=="asGrid"){
      $("#CatalogItems").removeClass("itemList"); 
      $("#CatalogItems").addClass("itemGrid"); 
      $.cookie('itemDisplay','asGrid');
    }else{
      $("#CatalogItems").removeClass("itemGrid"); 
      $("#CatalogItems").addClass("itemList"); 
      $.cookie('itemDisplay','asList');
    }
    return false;
  });
});
$(window).load(function(e) {
  if($.cookie('itemDisplay') && $(".setCatDisplay").length!=0){
    $(".setCatDisplay").removeClass("currentview"); 
    if($.cookie('itemDisplay')=="asGrid"){
      $("#CatalogItems").removeClass("itemList"); 
      $("#CatalogItems").addClass("itemGrid"); 
      $("#asGrid").addClass("currentview");
    }else{
      $("#CatalogItems").removeClass("itemGrid"); 
      $("#CatalogItems").addClass("itemList"); 
      $("#asList").addClass("currentview");
    }
    return false;
  }
});