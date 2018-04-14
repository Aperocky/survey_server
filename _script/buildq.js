$(function(ready){
  $('#qtypediv').change(function(){
    var multichoice = $('input[name=qtype]:checked').val();
    if(multichoice == "2"){
      $('.multichoice').hide();
    } else {
      $('.multichoice').show();
    }
  });
});
