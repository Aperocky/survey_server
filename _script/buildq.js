$(function(ready){
  // Hide multiple choices boxes when it's a Fill in blank
  $('#qtypediv').change(function(){
    var multichoice = $('input[name=qtype]:checked').val();
    if(multichoice == "2"){
      $('.multichoice').hide();
    } else {
      $('.multichoice').show();
    }
  });
  // Hide extra multiple choice boxes
  $('#numquestion').change(function(){
    var numchoice = $('#numquestion option:selected').val();
    var classname = 'input';
    if(numchoice == "2"){
      for(var i=3; i<=6; i++){
        name = '.input' + i.toString();
        $(name).hide();
      }
    } else if (numchoice == "3"){
      for(var i=4; i<=6; i++){
        name = '.input' + i.toString();
        $(name).hide();
      }
      $('.input3').show();
    } else if (numchoice == "4"){
      for(var i=5; i<=6; i++){
        name = '.input' + i.toString();
        $(name).hide();
      }
      $('.input3').show();
      $('.input4').show();
    } else if (numchoice == "5"){
      $('.input6').hide();
      $('.input3').show();
      $('.input4').show();
      $('.input5').show();
    } else if (numchoice == "6"){
      $('.input6').show();
      $('.input3').show();
      $('.input4').show();
      $('.input5').show();
    } else {
      // console.log("I should happen");
      $('.choiceoption').hide();
    }
  })
});
