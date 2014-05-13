(function($) {
	$(window).resize(function() {
		if ($(window).width() < 500) {
			$(".wgo-player-main").each(function() {
				var style = $(this).attr("style").replace("float: right", "float: _right").replace("float: left", "float: _left");
				$(this).attr("style", style);
			});
		} else {
			$(".wgo-player-main").each(function() {
				var style = $(this).attr("style").replace("float: _right", "float: right").replace("float: _left", "float: left");
				$(this).attr("style", style);
			});
		}
	});
})(jQuery);
