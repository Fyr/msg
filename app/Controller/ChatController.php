<?php
App::uses('AppController', 'Controller');
class ChatController extends AppController {
	
	public function index($userID = 0) {
		$this->set('userID', ($userID) ?  $userID : 'null');
	}
}
