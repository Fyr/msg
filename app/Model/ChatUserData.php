<?php
App::uses('AppModel', 'Model');
class ChatUserData extends AppModel {
	public $useDbConfig = 'users';
	public $useTable = 'clients_data';
	public $primaryKey = 'user_id';
}
