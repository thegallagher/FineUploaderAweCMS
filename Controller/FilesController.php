<?php

App::uses('AppController', 'Controller');

class FilesController extends AppController {

	public $components = array('RequestHandler');

	public function admin_upload($type = 'image') {
		if ($type == 'image') {
			$allowedExts = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
			$uploadPath = WWW_ROOT . '/img/upload/';
		} else if ($type == 'file') {
			$uploadPath = WWW_ROOT . '/file/upload/';
		} else {
			$this->set('_serialize', array('success', 'error', 'preventRetry'));
			$this->set(array('success' => false, 'preventRetry' => true, 'error' => 'Type must be either \'image\' or \'file\''));
			return;
		}
		
		App::import('Vendor', 'FineUploader.FineUploader/php');
		$uploader = new qqFileUploader($allowedExts, null, 'file');
		$result = $uploader->handleUpload($uploadPath);
		if ($result['success']) {
			if ($type == 'image') {
				$result['preview'] = Router::url('/' . IMAGES_URL . 'upload/' . $this->request->query['file']);
			} else if ($type == 'file') {
				$result['preview'] = Router::url('/files/upload/' . $this->request->query['file']);
			}
		}
		$this->set('_serialize', array_keys($result));
		$this->set($result);
	}

}