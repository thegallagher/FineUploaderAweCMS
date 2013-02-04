jQuery(function ($) {
	$('#<?php echo $id; ?>Button')
		.fineUploader(<?php echo json_encode($scriptOptions) ?>)
		.on('complete', function(event, id, filename, responseJSON) {
			if (responseJSON.preview) {
				$('#<?php echo $id; ?>Preview').append(responseJSON.preview);
			}
		});
	
	$('#<?php echo $id; ?>Preview').on('click', 'a.remove', function() {
		$(this).parent().remove();
		return false;
	});
});