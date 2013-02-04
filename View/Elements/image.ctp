<div class="uploader-file">
	<?php
	echo $this->Html->useTag('hidden', $name, array('value' => $value));
	echo $this->Html->link(
		$this->Html->image('upload/' . $value),
		'/img/upload/' . $value,
		array('class' => 'view', 'target' => '_blank', 'escape' => false)
	);
	echo $this->Html->link(__d('fine_uploader', '<i class="icon-remove-circle icon-white"></i> Remove'), '#', array('class' => 'remove btn btn-danger', 'escape' => false));
	?>
</div>