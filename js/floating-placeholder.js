$(document).on('focus', 'input.floating_placeholder, select.floating_placeholder, textarea.floating_placeholder ', function (e) {
  var placeholder = $(this).attr('placeholder');
  var placeholder_dom = '<span class="floating_placeholder" style="display: none;">' + placeholder + '<span>';

  if ($(this).prev('span.floating_placeholder').length != 1) {
    $(this).before(placeholder_dom);
    $(this).prev().css('color', '#535FFC');
    $(this).prev().fadeIn();
  }
  else {
    //$(this).before(placeholder_dom);
    $(this).prev().css('color', '#535FFC');
    $(this).prev().fadeIn();

  }


});

$(document).on('blur', 'input, select, textarea', function (e) {
  $(this).prev('span.floating_placeholder').css('color', '#B4B4B4');

  //$(this).prev('i').css('color', ''); 

});