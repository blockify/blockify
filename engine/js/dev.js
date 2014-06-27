/*
 *  Blockify
 */
(function($) {

  var targetClass = 'blockify-dev-block';

  $('.' + targetClass).each(function(index, el) {
    var self = $(el).removeClass(targetClass);
    var data = self.attr('data-block');
    if( data ) {
      self.data( 'blockify', JSON.parse(self.attr('data-block')) )
    }
    self.removeAttr('data-block')
  });

})(jQuery);
