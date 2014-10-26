<?php
App::uses('AppModel', 'Model');
class ChatRoom extends AppModel {

	protected $ChatRoomUser;
	
	public function getUsersID($roomID) {
		$this->loadModel('ChatRoomUser');

		$aRows = $this->ChatRoomUser->findAllByRoomId($roomID);
		return Hash::extract($aRows, '{n}.ChatRoomUser.user_id');
	}
}
