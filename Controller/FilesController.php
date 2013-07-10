<?php

App::uses('AppController', 'Controller');
App::uses('AwecmsUploader', 'Awecms.Lib');

class FilesController extends AppController {

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		$isAllowed = false;
		if ($this->Components->loaded('Auth')) {
			$isAllowed = $this->Auth->isAuthorized();
		} else {
			$eventName = 'FineUploader.is_authorized';
			$globalListeners = CakeEventManager::instance()->listeners($eventName);
			$objectListeners = $this->getEventManager()->listeners($eventName);
			if (count($globalListeners) > 0 || count($objectListeners) > 0) {
				$event = new CakeEvent($eventName, $this, true);
				$this->getEventManager()->dispatch($event);
				$isAllowed = !$event->isStopped() && $event->result;
			}
		}

		if (!$isAllowed) {
			$result = array(
				'success' => false,
				'error' => __d('fine_uploader', 'You are not logged in. Please login and try again.'),
			);
			$this->set('_serialize', array('success', 'error'));
			$this->set($result);
			$this->RequestHandler->renderAs($this, 'json');
			echo $this->render();
			$this->_stop();
			return false;
		}
	}

	public function admin_upload($type = 'image') {
		$uploader = new AwecmsUploader($this->request, compact('type'));
		if ($uploader->upload('file', 'query')) {
			$result = array(
				'success' => true,
				'type' => $type,
				'file' => $uploader->getUploadedFileName()
			);
			$this->set('_serialize', array('success', 'file', 'type'));
			$this->set($result);
		} else {
			$result = array(
				'success' => false,
				'error' => $uploader->getLastErrorMessage(),
			);
			$this->set('_serialize', array('success', 'error'));
			$this->set($result);
		}
	}

}