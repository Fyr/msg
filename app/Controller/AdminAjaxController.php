<?php
App::uses('AdminController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class AdminAjaxController extends PAjaxController {
	public $name = 'AdminAjax';
	public $components = array('Core.PCAuth');
	// public $uses = array('Category', 'Subcategory', 'Brand', 'Form.FormField', 'Form.PMForm');
		
	public function addOrderProduct() {
		$this->loadModel('Product');
		$this->loadModel('OrderProduct');
		
		fdebug($this->request->data);
		$errMsg = '';
		$aProducts = array();
		$orderID = $this->request->data('Order.id');
		if ($orderID && $this->request->data('OrderProduct')) {
			$product = $this->Product->find('first', array('conditions' => $this->request->data('OrderProduct')));
			if ($product) {
				$productID = $product['Product']['id'];
				$data = array('order_id' => $orderID, 'product_id' => $productID);
				if (!$this->OrderProduct->find('first', array('conditions' => $data))) {
					$this->OrderProduct->save($data);
				} else {
					$errMsg = __('Product already exists in the order');
				}
			} else {
				$errMsg = __('Product does not exist');
			}
		} 
		
		if ($orderID) {
			$aProducts = $this->OrderProduct->getOrderProducts($orderID);
		} else {
			$errMsg = __('Incorrect request');
		}
		
		$this->set('errMsg', $errMsg);
		$this->set('aProducts', $aProducts);
	}
	
	public function delOrderProduct($orderID, $productID) {
		$this->loadModel('OrderProduct');
		$this->OrderProduct->deleteAll(array('order_id'=> $orderID, 'product_id' => $productID));
		
		$aProducts = $this->OrderProduct->getOrderProducts($orderID);
		$this->set('aProducts', $aProducts);
	}
}
