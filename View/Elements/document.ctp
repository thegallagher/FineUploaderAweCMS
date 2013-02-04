<div class="uploader-file">
	<?php
	echo $this->Html->useTag('hidden', $name, array('value' => $value));
	echo $this->Html->link($value, '/files/upload/' . $value, array('class' => 'view', 'target' => '_blank'));
	echo ' ';
	echo $this->Html->link(__d('fine_uploader', '<i class="icon-remove-circle icon-white"></i> Remove'), '#', array('class' => 'remove btn btn-danger', 'escape' => false));
	?>
</div>