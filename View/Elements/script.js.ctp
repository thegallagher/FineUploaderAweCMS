jQuery(function ($) {
	var template = Awecms.getTemplate('#fineuploader-<?php echo $type; ?>');
	var value = $('#<?php echo $id; ?>Input').val();
	if (value) {
		var data = {
			'file': $('#<?php echo $id; ?>Input').val()
		};
		$('#<?php echo $id; ?>Preview').html(template(data));
	}

	$('#<?php echo $id; ?>Button')
		.fineUploader(<?php echo json_encode($scriptOptions) ?>)
		.on('complete', function(event, id, filename, responseJSON) {
			if (responseJSON.success) {
				$('#<?php echo $id; ?>Input').val(responseJSON.file);
				$('#<?php echo $id; ?>Preview').html(template(responseJSON));
			}
		});
	
	$('#<?php echo $id; ?>Preview').on('click', 'a.remove', function() {
		$('#<?php echo $id; ?>Input').val('');
		$(this).parents('.uploader-file').first().remove();
		return false;
	});
});