<?php

App::uses('AppController', 'Controller');

class FilesController extends AppController {

	public $components = array('RequestHandler');

	public function admin_upload($type = 'image') {
		if ($type == 'image') {
			$allowedExts = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
			$uploadPath = WWW_ROOT . '/img/upload/';
		} else if ($type == 'document') {
			$allowedExts = array('pdf', 'doc', 'docx', 'txt', 'odt', 'rtf');
			$uploadPath = WWW_ROOT . '/file/upload/';
		} else {
			$this->set('_serialize', array('success', 'error', 'preventRetry'));
			$this->set(array('success' => false, 'preventRetry' => true, 'error' => 'Type must be either \'image\' or \'document\''));
			return;
		}
		
		App::import('Vendor', 'FineUploader.FineUploader/php');
		$uploader = new qqFileUploader($allowedExts, null, 'file');
		$result = $uploader->handleUpload($uploadPath);
		if (!empty($result['success'])) {
			$result['type'] = $type;
			$result['file'] = $uploader->getUploadName();
			$this->set('_serialize', array('success', 'file', 'type'));
			$this->set($result);
		} else {
			$this->set('_serialize', array('success', 'error'));
			$this->set($result);
		}
	}

}