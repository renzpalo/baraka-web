
// Navigation Bar

$(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.main-nav').addClass('fixed-top');
    } else {
        $('.main-nav').removeClass('fixed-top');
    }
});

// Featured Products

$(document).ready(function() {

  $("#myCarousel").on("slide.bs.carousel", function(e) {
    var $e = $(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 3;
    var totalItems = $(".carousel-item").length;

    if (idx >= totalItems - (itemsPerSlide - 1)) {
      var it = itemsPerSlide - (totalItems - idx);
      for (var i = 0; i < it; i++) {
        // append slides to end
        if (e.direction == "left") {
          $(".carousel-item")
            .eq(i)
            .appendTo(".carousel-inner");
        } else {
          $(".carousel-item")
            .eq(0)
            .appendTo($(this).find(".carousel-inner"));
        }
      }
    }
  });

  $('#productImages').carousel({
    interval: 100000
  })





});

$(document).ready(function () {
  $('#myCarousel').find('.carousel-item').first().addClass('active');
});

// CheckBox

$(document).ready(function() {
  // alert("Hello! I am an alert box!!");

  $('#cbTableAll').click(function(event) {
    if (this.checked) {
      $('.cbTableItem').each(function() {
        this.checked = true;
      });
    } else {
      // Uncheck
      $('.cbTableItem').each(function() {
        this.checked = false;
      });
    }
  });

});



