<?php

App::uses('AppHelper', 'View/Helper');

class FineUploaderHelper extends AppHelper {

	public $helpers = array('Html', 'Form');
	protected static $_initilized = false;
	
	public $defaults = array(
		'scriptOptions' => array(
			'request' => array(
				'endpoint' => array('plugin' => 'fine_uploader', 'controller' => 'files', 'action' => 'upload', 'ext' => 'json'),
				'inputName' => 'file'
			)
		)
	);
	
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		$settings = Hash::normalize($settings);
		$this->settings = Hash::merge($this->defaults, $settings);
		
		if (is_array($this->settings['scriptOptions']['request']['endpoint'])) {
			$this->settings['scriptOptions']['request']['endpoint'] = $this->Html->url($this->settings['scriptOptions']['request']['endpoint']);
		}
		
		if (!isset($this->settings['css'])) {
			$this->settings['css'] = 'FineUploader.fineuploader';
		}
	}

	public function beforeRender($viewFile) {
		if (!self::$_initilized) {
			$this->Html->script('FineUploader.jquery.fineuploader-3.1.1.min', array('inline' => false));
			if ($this->settings['css']) {
				$this->Html->css($this->settings['css'], null, array('inline' => false));
			}
			self::$_initilized = true;
		
			if (isset($this->settings['template'])) {
				$this->settings['scriptOptions']['template'] = $this->_View->element($this->settings['template']);
			}
			
			if (isset($this->settings['fileTemplate'])) {
				$this->settings['scriptOptions']['fileTemplate'] = $this->_View->element($this->settings['fileTemplate']);
			}
		}
	}

	public function input($field, $options = array()) {
		//$this->setEntity();
		$options = $this->_init($field, $options);
		$options = $this->_initInputField($field, $options);
		
		if (!isset($options['div'])) {
			$options['div'] = 'input uploader';
		}
		
		if (isset($options['scriptOptions'])) {
			$options['scriptOptions'] = Hash::normalize($options['scriptOptions']);
			$options['scriptOptions'] = Hash::merge($this->settings['scriptOptions'], $options['scriptOptions']);
		} else {
			$options['scriptOptions'] = $this->settings['scriptOptions'];
		}
		
		$isMultiple = isset($options['multiple']) && $options['multiple'];
		$options['scriptOptions']['multiple'] = $isMultiple;
		
		if ($isMultiple) {
			$options['scriptOptions']['request']['params']['name'] = $options['name'] . '[]';
		} else {
			$options['scriptOptions']['request']['params']['name'] = $options['name'];
		}
		$script = $this->Html->scriptBlock($this->_View->element('FineUploader.script.js', $options), array('inline' => $this->_View->request->is('ajax')));
		unset($options['scriptOptions']);
		
		$previews = '';
		$element = 'FineUploader.' . $options['type'];
		if ($isMultiple) {
			$options['value'] = (array) $options['value'];
			foreach ($options['value'] as $value) {
				$previews .= $this->_View->element($element, array('name' => $options['name'] . '[]', 'value' => $value));
			}
		} else {
			if (is_array($options['value'])) {
				$options['value'] = $options['value'][0];
			}
			$previews .= $this->_View->element($element, array('name' => $options['name'], 'value' => $options['value']));
		}
		
		$html = $this->Form->label($field);
		$html .= $this->Html->div(null, $previews, array('id' => $options['id'] . 'Preview'));
		$html .= $this->Html->div('uploader-button', '', array('id' => $options['id'] . 'Button'));
		$html .= $script;
		
		return $this->Html->div($options['div'], $html);
	}

	public function image($field, $options = array()) {
		$options = $this->_init($field, $options);
		//$image = empty($options['value']) ? 'data://image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' : 'upload/' . $options['value'];
		//$link = empty($options['value']) ? '#' : '/img/' . $image;
		//$options['preview'] = $this->_previewLink($this->Html->image($image), $link, $options);
		$options['type'] = 'image';
		$options['scriptOptions']['request']['endpoint'] = $this->Html->url(array('plugin' => 'fine_uploader', 'controller' => 'files', 'action' => 'upload', 'ext' => 'json'));
		return $this->input($field, $options);
	}

	public function document($field, $options = array()) {
		$options = $this->_init($field, $options);
		//$link = empty($options['value']) ? '#' : '/files/upload/' . $options['value'];
		//$options['preview'] = $this->_previewLink($options['value'], $link, $options);
		$options['type'] = 'document';
		$options['scriptOptions']['request']['endpoint'] = $this->Html->url(array('plugin' => 'fine_uploader', 'controller' => 'files', 'action' => 'upload', 'document', 'ext' => 'json'));
		return $this->input($field, $options);
	}
	
	protected function _previewLink($text, $link, $options) {
		return $this->Html->link($text, $link, array('id' => $options['id'] . 'Preview', 'class' => 'preview', 'target' => '_blank', 'escape' => false));
	}
	
	protected function _init($field, $options) {
		$this->Form->setEntity($field);
		$options = $this->Form->_name($options);
		$options = $this->Form->value($options);
		$options = $this->Form->domId($options);
		return $options;
	}

}