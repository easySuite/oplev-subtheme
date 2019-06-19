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
var sliderImg = $('.ding_nodelist.ding_nodelist-carousel .ding_nodelist-items.slick-slider .slick-list .slick-slide .event-image a img');

function switchImgSliderStyles(element) {
  var notMobile = ($(window).width() >=  768);
  element.css({
    'background-position': notMobile ? 'initial' : 'center',
    'background-size': notMobile ? 'initial' : 'cover'
  });
}

sliderImg.load(function() {
  var img = $(this);
  var div = $("<div />").css({
    background: "url(" + img.attr("src") + ") no-repeat",
    width: img.width() + "px",
    height: img.height() + "px",
    'max-width': img.naturalWidth ? img.naturalWidth : '100%',
    'max-height': img.naturalHeight ? img.naturalHeight : '100%'
  });

  div.html(img.attr("alt"));
  div.addClass("replaced-event-image");

  img.replaceWith(div);
  switchImgSliderStyles(div);
});

$(window).resize(function() {
  var replacedDiv = $('.replaced-event-image');
  var notMobile = ($(window).width() >=  768);

  console.log(replacedDiv.css('max-width'), replacedDiv.css('max-height'));
  replacedDiv.css({
    width: notMobile ? replacedDiv.css('max-width') : replacedDiv.parent().width() + "px",
    height: notMobile ? replacedDiv.css('max-height') : replacedDiv.parent().height() + "px"
  });

  switchImgSliderStyles(replacedDiv);
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