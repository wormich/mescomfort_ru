(function($){
  $(window).load(function(){

    $(".table_compare, .catalog-items-scroll").mCustomScrollbar({
      axis:"x", //set both axis scrollbars
      theme:"my-theme",
      advanced:{autoExpandHorizontalScroll:true}, //auto-expand content to accommodate floated elements
      // change mouse-wheel axis on-the-fly 
      callbacks:{
        onOverflowY:function(){
          var opt=$(this).data("mCS").opt;
          if(opt.mouseWheel.axis!=="y") opt.mouseWheel.axis="y";
        },
        onOverflowX:function(){
          var opt=$(this).data("mCS").opt;
          if(opt.mouseWheel.axis!=="x") opt.mouseWheel.axis="x";
        },
      }
    });

  });
})(jQuery);