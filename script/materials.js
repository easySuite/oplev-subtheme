(function ($) {
  "use strict";

  $(document).ready(function () {
    $('.field-type-ting-reference').each(function() {
      var classes = $(this).attr('class').split(' ');
      var delay = 0;
      // Find pane's ID to get its delay settings.
      $(classes).each(function(i, item){
        if (item.match(/pane\-\d+/)) {
          delay = parseInt(Drupal.settings.materials[item]);
        }
      });

      if ($(window).width() >=  768) {
        $(this).find('.field-items:first').slick({
          autoplay: false,
          responsive: true,
          dots: true,
          infinite: false,
          slidesToScroll: 1,
          slidesToShow: 4,
          customPaging: function(slick, index) {
            return '<a>' + (index + 1) + '</a>';
          }
        });
      }
      else {
        $(this).find('.field-items:first').slick({
          autoplay: false,
          responsive: true,
          dots: true,
          infinite: false,
          slidesToScroll: 1,
          slidesToShow: 1,
          customPaging: function(slick, index) {
            return '<a>' + (index + 1) + '</a>';
          }
        });
      }
    });
    

//-------------------------------------------
function switchImgSliderStyles(imgElement) {
  var notMobile = ($(window).width() >=  768);
  imgElement.css({
    width: imgElement.parent().width() + "px",
    height: imgElement.parent().height() + "px",
    background: "url(" + imgElement.attr("src") + ") center center / cover no-repeat",
    'box-sizing': 'border-box',
    'padding-left': imgElement.parent().width()
  });
}

function parseSliderImages() {
  var sliderImg = $('.ding_nodelist.ding_nodelist-carousel .ding_nodelist-items.slick-slider .slick-list .slick-slide .event-image a img');
  sliderImg.each(function(i, elem) {
    var img = $(elem);
    switchImgSliderStyles(img);
  });
}

parseSliderImages();
$(window).resize(function() {
  setTimeout(() => {
    parseSliderImages();
  }, 50);
});
//--------------------------------------------- 

    var $header = $('.secondary-menu-wrapper');
    var $element = $('.secondary-menu li');
    var $body = $('.navbar-collapse .main-menu');

    if ($header.css('position') === 'relative') {
      $element.addClass('secondary-menu').appendTo($body);
    }
  });
})(jQuery);