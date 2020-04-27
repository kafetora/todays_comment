jQuery(document).ready(function($){

  $('.btn-reset').click(function(e){
    e.preventDefault();
    $('#todays-comment-text').html('');
  });

});