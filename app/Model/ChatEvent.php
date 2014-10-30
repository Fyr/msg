<?php
App::uses('AppModel', 'Model');
class ChatEvent extends AppModel {
	const OUTCOMING_MSG = 1;
	const INCOMING_MSG = 2;
	const ROOM_OPENED = 3;
	const USER_INCLUDED = 4;
	const USER_EXCLUDED = 5; // not used yet
	const FILE_UPLOADED = 6;
	const FILE_DOWNLOAD_AVAIL = 7;
	
	const ACTIVE = 1;
	const INACTIVE = 0;
	
	protected $ChatMessage, $ChatRoom, $Media;
	
	protected function _addEvent($event_type, $user_id, $room_id, $obj_id, $initiator_id, $active = 1) {
		$data = compact('event_type', 'user_id', 'room_id', 'initiator_id', 'active');
		if (in_array($event_type, array(self::OUTCOMING_MSG, self::INCOMING_MSG))) {
			$data['msg_id'] = $obj_id;
		} elseif (in_array($event_type, array(self::ROOM_OPENED, self::USER_INCLUDED, self::USER_EXCLUDED))) {
			$data['recipient_id'] = $obj_id;
		} elseif (in_array($event_type, array(self::FILE_UPLOADED, self::FILE_DOWNLOAD_AVAIL))) {
			$data['file_id'] = $obj_id;
		}
		
		$this->clear();
		if (!$this->save($data)) {
			throw new Exception("Chat event cannot be saved\n".print_r($data, true));
		}
	}
	
	/**
	 * Returns all users in the chat room
	 *
	 * @param int $roomID
	 * @return array
	 */
	protected function _getRoomUsersID($roomID) {
		$this->loadModel('ChatRoom');
		$room = $this->ChatRoom->findById($roomID);
		if (!$room) {
			throw new Exception('Incorrect room ID');
		}
		return array_merge(
			array($room['ChatRoom']['initiator_id'], $room['ChatRoom']['recipient_id']), 
			$this->ChatRoom->getUsersID($roomID)
		);
	}
	
	public function addMessage($currUserID, $roomID, $message) {
		$this->loadModel('ChatMessage');
		
		if (!$this->ChatMessage->save(compact('message'))) {
			throw new Exception("Message cannot be saved\n".print_r($data, true));
		}
		$msgID = $this->ChatMessage->id;
		
		$aUsersID = $this->_getRoomUsersID($roomID);
		foreach($aUsersID as $userID) {
			if ($userID == $currUserID) {
				$this->_addEvent(self::OUTCOMING_MSG, $currUserID, $roomID, $msgID, $currUserID, self::INACTIVE);
			} else {
				$this->_addEvent(self::INCOMING_MSG, $userID, $roomID, $msgID, $currUserID);
			}
		}
	}
	
	public function addFile($currUserID, $roomID, $mediaID) {
		$aUsersID = $this->_getRoomUsersID($roomID);
		foreach($aUsersID as $userID) {
			if ($userID == $currUserID) {
				$this->_addEvent(self::FILE_UPLOADED, $currUserID, $roomID, $mediaID, $currUserID, self::INACTIVE);
			} else {
				$this->_addEvent(self::FILE_DOWNLOAD_AVAIL, $userID, $roomID, $mediaID, $currUserID);
			}
		}
	}
	
	public function openRoom($currUserID, $userID) {
		$this->loadModel('ChatRoom');
		
		$room = $this->ChatRoom->getRoomWith2Users($currUserID, $userID);
		if (!$room) {
			$this->ChatRoom->clear();
			$data = array('initiator_id' => $currUserID, 'recipient_id' => $userID);
			if (!$this->ChatRoom->save($data)) {
				throw new Exception("Room cannot be opened\n".print_r($data));
			}
			$room = $this->ChatRoom->findById($this->ChatRoom->id);
			$this->_addEvent(self::ROOM_OPENED, $currUserID, $room['ChatRoom']['id'], $userID, $currUserID, self::INACTIVE);
			
			// Room is auto-opened by JS Chat  - no need to have an active event
			$this->_addEvent(self::ROOM_OPENED, $userID, $room['ChatRoom']['id'], $userID, $currUserID, self::INACTIVE);
		}
		return $room;
	}
	
	public function getActiveEvents($currUserID) {
		$this->loadModel(array('ChatMessage', 'ChatUser', 'Media.Media'));
		
		$conditions = array('user_id' => $currUserID, 'active' => 1);
		$order = array('room_id', 'created');
		$events = $this->find('all', compact('conditions', 'order'));
		
		foreach($events as &$event) {
			$event['ChatEvent']['created'] = date('H:i', strtotime($event['ChatEvent']['created']));
		}
		
		// Get info about sent messages
		$aMsgID = Hash::extract($events, '{n}.ChatEvent.msg_id');
		$messages = $this->ChatMessage->findAllById($aMsgID);
		$aAuthorsID = Hash::extract($events, '{n}.ChatEvent.initiator_id');
		$authors = $this->ChatUser->getUsers($aAuthorsID);
		
		// Get info about sent files
		$aFilesID = Hash::extract($events, '{n}.ChatEvent.file_id');
		$files = $this->Media->getList(array('id' => $aFilesID), 'Media.id');
		
		// rebuild data to have IDs as keys 
		// $events = Hash::combine($events, '{n}.ChatEvent.id', '{n}.ChatEvent');
		$messages = Hash::combine($messages, '{n}.ChatMessage.id', '{n}.ChatMessage');
		$authors = Hash::combine($authors, '{n}.ChatUser.id', '{n}');
		$files = Hash::combine($files, '{n}.Media.id', '{n}.Media');
		return compact('events', 'messages', 'authors', 'files');
	}
	
	public function markInactive($ids) {
		$this->updateAll(array('active' => self::INACTIVE), array('id' => $ids));
	}
	
	public function getActiveRooms($userID) {
		$this->loadModel('ChatMessage', 'Media.Media');
		
		$fields = array('ChatEvent.event_type', 'ChatEvent.room_id', 'ChatEvent.created', 'ChatEvent.initiator_id', 'ChatEvent.msg_id', 'ChatEvent.file_id', 'ChatMessage.message', 'SUM(active) AS count');
		$conditions = array('ChatEvent.user_id' => $userID, 'ChatEvent.active' => 1);
		$joins = array(
			array('type' => 'left', 'table' => $this->ChatMessage->getTableName(), 'alias' => 'ChatMessage', 'conditions' => array('`ChatEvent`.msg_id = `ChatMessage`.id'))
		);
		$order = array('count DESC');
		$group = array('ChatEvent.room_id');
		return $this->find('all', compact('fields', 'conditions', 'joins', 'order', 'group'));
	}
}
