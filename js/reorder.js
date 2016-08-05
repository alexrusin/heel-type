(function($) {
	$(document).ready(function() {
		var sortList = $('ul#custom-type-list');
		var animation = $('#loading-animation');
		var pageTitle = $('.reorder-title');
		sortList.sortable({
			update: function(event, ui) {
				animation.show();


				$.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'save_order',
						order: sortList.sortable('toArray').toString(),
						security: HEEL_REORDER.security
					},
					success: function(response) {
						$('div#message').remove();
						animation.hide();
						pageTitle.after('<div id="message" class="updated"><p>Heel order has been saved</p></div>');
					},
					error: function(error) {
						$('div#message').remove();
						animation.hide();
						pageTitle.after('<div id="message" class="error"><p>There was an error saving heels order, or you do not have proper permissions</p></div>');
					}
				});

			}

		});

	});

})(jQuery);