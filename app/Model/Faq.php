<?
App::uses('AppModel', 'Model');
class Faq extends AppModel {
	public $validate = array(
		'title' => 'notempty'
	);
}