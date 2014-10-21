<?
App::uses('AppModel', 'Model');
App::uses('ProductType', 'Model');
App::uses('Media', 'Media.Model');
class Product extends AppModel {
	
	public $belongsTo = array(
		'ProductType' => array(
			'foreignKey' => 'product_type_id',
			'dependent' => true
		),
	);
	
	public $validate = array(
		'serial' => 'notempty'
	);
	
}
