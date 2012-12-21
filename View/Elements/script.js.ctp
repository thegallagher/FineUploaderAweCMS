jQuery(function ($) {
	$('#<?php echo $id; ?>Button').fineUploader(<?php echo json_encode($scriptOptions) ?>)
	.on('complete', function(event, id, filename, responseJSON) {
		$('#<?php echo $id; ?>').val(filename);
		<?php if (isset($type)) : ?>
			if (responseJSON.preview) {
				<?php if ($type == 'image') : ?>
					$('#<?php echo $id; ?>Preview')
						.attr('href', responseJSON.preview)
						.find('img').attr('src', responseJSON.preview);
				<?php elseif ($type == 'file') : ?>
					$('#<?php echo $id; ?>Preview')
						.attr('href', responseJSON.preview)
						.html(filename);
				<?php endif; ?>
			}
		<?php endif; ?>
	});
});