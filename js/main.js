$(document).ready(function(){

  $(".nav-item").on("click", function(){
    var id = '#' + $(this).data("id");
    $(".nav-item").removeClass("active");
    $(this).addClass("active");
    $(".section-wrap").removeClass("active");
    $(id).addClass("active");
  });

});
