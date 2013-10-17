// jQuery div toggle
(function($) {
	$.fn.showHide = function(options) {

		//default vars for the plugin
		var defaults = {
			speed : 800,
			easing : '',
			changeText : 0,
			showText : 'Show',
			hideText : 'Hide'

		};
		var options = $.extend(defaults, options);

		$(this).click(function() {
			// optionally add the class .toggleDiv to each div you want to automatically close
			//$('.toggleDiv').fadeOut(options.speed, options.easing);
			$('.toggleDiv').animate({
				width : '10',
				opacity : .5
			}, 'slow');
			// this var stores which button you've clicked
			var toggleClick = $(this);
			// this reads the rel attribute of the button to determine which div id to toggle
			var toggleDiv = $(this).attr('title');
			// here we toggle show/hide the correct div at the right speed and using which easing effect
			//$(toggleDiv).fadeToggle(options.speed, options.easing, function() {
			$(toggleDiv).animate({
				height : 'toggle',
				opacity : 'toggle'
			}, 'slow', function() {
				// this only fires once the animation is completed
				if (options.changeText == 1) {
					$(toggleDiv).is(":visible") ? toggleClick.text(options.hideText) : toggleClick.text(options.showText);
				}
			});

			return false;

		});

	};
})(jQuery);

$(document).ready(function() {

	if (!$.cookie("CW_LeftToggleStatus")) {
		$.cookie("CW_LeftToggleStatus", 1, {
			expires : 14
		});
	};

	var panel = $("#side_left"), flip = $(".flip_left"), state = $.cookie("CW_LeftToggleStatus");

	flip.click(function() {
		panel.slideToggle("slow", function() {
			$.cookie("CW_LeftToggleStatus", (state == 1 ? "0" : "1"), {
				expires : 14
			});
		});
	});

	if ((state == 0 && panel.is(':visible'))) {
		panel.hide();
	}

	// Rechtes Panel
	if (!$.cookie("CW_RightToggleStatus")) {
		$.cookie("CW_RightToggleStatus", 1, {
			expires : 14
		});
	};
	var panelr = $("#side_right"), flipr = $(".flip_right"), stater = $.cookie("CW_RightToggleStatus");

	flipr.click(function() {
		panelr.slideToggle("slow", function() {
			$.cookie("CW_RightToggleStatus", (stater == 1 ? "0" : "1"), {
				expires : 14
			});
		});
	});

	if ((stater == 0 && panelr.is(':visible'))) {
		panelr.hide();
	}

});

// jQuery div toggle ---END---
