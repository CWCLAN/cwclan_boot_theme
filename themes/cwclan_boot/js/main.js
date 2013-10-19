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