<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class ProductType extends AppModel {
	
	public $hasOne = array(
		'Media' => array(
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'ProductType', 'Media.main' => 1),
			'dependent' => true
		)
	);
	
	public $validate = array(
		'title' => 'notempty'
	);
}
