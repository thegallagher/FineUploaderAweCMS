<?php echo json_encode(array(
	'success' => $success,
	'preview' => $this->element('FineUploader.' . $type, $previewOptions),
));