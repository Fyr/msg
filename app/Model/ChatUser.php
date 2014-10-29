<?php
App::uses('AppModel', 'Model');
class ChatUser extends AppModel {
	public $useDbConfig = 'users';
	public $useTable = 'clients';
	public $primaryKey = 'id';
	
	public $hasOne = array(
		'ChatUserData' => array(
			'foreignKey' => 'user_id'
		)
	);
	
	protected $ChatEvent;
	
	protected function _initUserData($user) {
		$user['ChatUser']['name'] = (trim($user['ChatUserData']['full_name'])) ? $user['ChatUserData']['full_name'] : $user['ChatUser']['username'];
		$user['Avatar']['url'] = (trim($user['ChatUserData']['avatar'])) ? $user['ChatUserData']['avatar'] : '/img/no-photo.jpg';
		return $user;
	}

	public function getUser($id) {
		$user = $this->findById($id);
		return $this->_initUserData($user);
	}
	
	public function getContactListUsers($currUserID) {
		$this->loadModel('ChatEvent');
		
		$aActiveRooms = $this->ChatEvent->getActiveRooms($currUserID);
		foreach($aActiveRooms as &$user) {
			$user = array_merge($user, $this->getUser($user['ChatMessage']['user_id']));
			$user['ChatMessage']['count'] = $user[0]['count'];
			unset($user[0]);
		}
		
		$aUserID = Hash::extract($aActiveRooms, '{n}.ChatUser.id');
		$conditions = array('NOT' => array($this->primaryKey => $aUserID));
		$aUsers = $this->find('all', compact('conditions'));
		foreach($aUsers as $i => &$user) {
			$userID = $user['ChatUser'][$this->primaryKey];
			if ($userID != $currUserID) {
				$user = $this->_initUserData($user);
			} else {
				unset($aUsers[$i]);
			}
		}
		return array_merge($aActiveRooms, $aUsers);
	}
	
	public function getUsers($aID) {
		$aUsers = $this->findAllById($aID);
		foreach($aUsers as $i => &$user) {
			$user = $this->_initUserData($user);
		}
		return $aUsers;
	}
}
