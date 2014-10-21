<?
App::uses('AppModel', 'Model');
class OrderProduct extends AppModel {
	
	// public $hasMany = array('Product');
	
	public function getOrderProducts($orderID) {
		App::import('Model', 'Product');
		$this->Product = new Product();
		
		$orderProducts = $this->findAllByOrderId($orderID);
		$aProductID = Hash::extract($orderProducts, '{n}.OrderProduct.product_id');
		return $this->Product->find('all', array(
			'conditions' => array('Product.id' => $aProductID), 
			'order' => array('Product.product_type_id', 'Product.serial')
		));
	}
	
}
