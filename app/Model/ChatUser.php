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
		// TODO: replace this hard-code!!!
		$aActiveRooms = $this->query('SELECT `ChatEvent`.room_id, SUM(active) AS `count`, `ChatUser`.ID, `ChatUser`.name, `ChatEvent`.created, `ChatMessage`.message
FROM chat_events AS `ChatEvent`
JOIN chat_messages AS `ChatMessage` ON `ChatEvent`.msg_id = `ChatMessage`.id
JOIN users AS `ChatUser` ON `ChatMessage`.user_id = `ChatUser`.id
WHERE `ChatEvent`.user_id = '.$currUserID.' AND `ChatEvent`.active = 1
GROUP BY `ChatEvent`.room_id
ORDER BY count DESC');
		foreach($aActiveRooms as &$user) {
			$user = $this->_initUserData($user);
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
