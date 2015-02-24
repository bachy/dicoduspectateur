$(document).ready(function() {

	$('.definition-list li.definition a').each(function() {
		var $link = $(this);
		var $dialog = $('<div></div>')
			.load($link.attr('href'))
			.dialog({
				autoOpen: false,
				closeText: "x",
				position: { my: "left top", at: "left top", of: $link },
				width: 600
			});

		$link.click(function() {
			$dialog.dialog('open');

			return false;
		});

		$('body').bind('click', function(e) {
        	if($dialog.dialog('isOpen') && !$(e.target).is('.ui-dialog, a') && !$(e.target).closest('.ui-dialog').length) {
            	$dialog.dialog('close');
        	}
        });
	});


});