<?
App::uses('AppModel', 'Model');
class Contractor extends AppModel {
	
	public $validate = array(
		'title' => 'notempty'
	);
	
}
