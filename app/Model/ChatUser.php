<?php
App::uses('AppModel', 'Model');
class ChatUser extends AppModel {
	public $useTable = 'users';
	public $primaryKey = 'ID';
	
	protected function _initUserData($user) {
		$user['ChatUser']['id'] = $user['ChatUser'][$this->primaryKey];
		$user['Avatar']['url'] = '/img/temp/'.$user['ChatUser']['id'].'.jpg';
		return $user;
	}

	public function getUser($id) {
		$user = $this->findById($id);
		return $this->_initUserData($user);
	}
	
	public function getContactListUsers($currUserID) {
		$aUsers = $this->find('all');
		foreach($aUsers as $i => &$user) {
			$userID = $user['ChatUser'][$this->primaryKey];
			if ($userID != $currUserID) {
				$user = $this->_initUserData($user);
			} else {
				unset($aUsers[$i]);
			}
		}
		return $aUsers;
	}
	
	public function getUsers($aID) {
		$aUsers = $this->findAllById($aID);
		foreach($aUsers as $i => &$user) {
			$user = $this->_initUserData($user);
		}
		return $aUsers;
	}
}
