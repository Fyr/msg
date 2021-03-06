<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class ChatAjaxController extends PAjaxController {
	public $name = 'ChatAjax';
	public $uses = array('ChatUser', 'ChatMessage', 'ChatEvent');

	public function beforeFilter() {
		if (TEST_ENV) {
			$this->currUserID = $this->Session->read('currUser.id');
		} else {
			$this->loadModel('ClientProject');
			$userData = ClientProject::getUserAuthData();
			$this->currUserID = Hash::get($userData, 'user_id');
		}
		if (!$this->currUserID) {
			$this->autoRender = false;
			exit('You must be authorized');
		}
		$this->currUser = $this->ChatUser->getUser($this->currUserID);
	}
	
	public function jsSettings() {
		$this->loadModel('ChatRoom');
	}
	
	public function panel() {
		/*
		$aUsers = $this->ChatUser->getContactListUsers($this->currUserID);
		$this->set('aUsers', $aUsers);
		*/
		$this->contactList();
	}
	
	public function contactList() {
		/*
		try {
			$aUsers = $this->ChatUser->getContactListUsers($this->currUserID);
			$this->setResponse($aUsers);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
		*/
		$aUsers = $this->ChatUser->getContactListUsers($this->currUserID);
		$this->set('aUsers', $aUsers);
	}
	
	public function openRoom() {
		$userID = $this->request->data('user_id');
		try {
			if (!$userID) {
				throw new Exception('Incorrect request');
			}
			
			$room = $this->ChatEvent->openRoom($this->currUserID, $userID);
			$user = $this->ChatUser->getUser($userID);
			$events = $this->ChatEvent->getActiveEvents($this->currUserID);
			return $this->setResponse(compact('room', 'user', 'events'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
	public function sendMsg() {
		$roomID = $this->request->data('roomID');
		$msg = $this->request->data('msg');
		try {
			if (!($msg && $roomID)) {
				throw new Exception('Incorrect request');
			}
			
			$this->ChatEvent->addMessage($this->currUserID, $roomID, $msg);
			
			return $this->setResponse(true);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
	public function sendFile() {
		$roomID = $this->request->data('roomID');
		$mediaID = $this->request->data('id');
		try {
			if (!($mediaID && $roomID)) {
				throw new Exception('Incorrect request');
			}
			
			$this->ChatEvent->addFile($this->currUserID, $roomID, $mediaID);
			
			return $this->setResponse(true);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
	public function updateState() {
		try {
			$data = $this->ChatEvent->getActiveEvents($this->currUserID);
			// fdebug($data, 'update_chat.log', false);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
	public function markRead() {
		try {
			$ids = $this->request->data('ids');
			if (!$ids || !is_array($ids)) {
				throw new Exception('Incorrect request');
			}
			$this->ChatEvent->markInactive($ids);
			$this->setResponse(true);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
}
