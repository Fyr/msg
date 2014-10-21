<?php
App::uses('AdminController', 'Controller');
class AdminOrdersController extends AdminController {
	public $name = 'AdminOrders';
	public $components = array('Auth', 'Table.PCTableGrid');
	public $uses = array('Order', 'Contractor', 'ProductType', 'OrderProduct');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('id', 'created', 'period', 'paid', 'shipped')
		);
		$this->PCTableGrid->paginate('Order');
	}
	
	public function edit($id = 0) {
		$aFlags = array('paid', 'shipped');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if (is_array($this->request->data('Order.status'))) {
				foreach($aFlags as $field) {
					$_field = 'Order.'.$field;
					$this->request->data($_field, in_array($field, $this->request->data('Order.status')));
				}
			}
			if ($this->Order->save($this->request->data)) {
				$id = $this->Order->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->Order->findById($id);
			$this->request->data = $row;
		} else {
			$this->request->data('Order.period', 0);
		}
		
		if ($id) {
			$status = array();
			foreach($aFlags as $field) {
				if ($this->request->data('Order.'.$field)) {
					$status[] = $field;
				}
			}
			$this->request->data('Order.status', $status);
			
			$this->set('aProducts', $this->OrderProduct->getOrderProducts($id));
		}
		
		$this->set('aContractorOptions', $this->Contractor->find('list'));
		$this->set('aProductTypeOptions', $this->ProductType->find('list'));
	}
}
