
$(function(){
    $('.modalButton2').click(function(){
    	$('#modal').modal('show')
    		.find('#modalContent')
    		.load($(this).attr('value'));

    });
});
