<?php
App::uses('AppModel', 'Model');
class ChatMessage extends AppModel {
	const UNREAD = 0;
	const READ = 1;
}
