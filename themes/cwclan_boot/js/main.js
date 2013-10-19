$(document).ready(function() {
        var Wheight = $('body').prop('scrollHeight');        
        $('#left-panel').css( "height" , Wheight + "px");        
        $('#right-panel').css( "height" , Wheight + "px");
        
	var panel = $("#left-panel"), flip = $(".flip_left");

	flip.click(function() {
		panel.fadeToggle("slow", function() {			
		});
	});	

	// Rechtes Panel	
	var panelr = $("#right-panel"), flipr = $(".flip_right");

	flipr.click(function() {
		panelr.fadeToggle("slow", function() {			
		});
	});
});