<?php
App::uses('AdminController', 'Controller');
class AdminProductsController extends AdminController {
	public $name = 'AdminProducts';
	public $components = array('Auth', 'Table.PCTableGrid');
	public $uses = array('ProductType', 'Product');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('Product.id', 'ProductType.title', 'Product.serial')
		);
		$this->PCTableGrid->paginate('Product');
	}
	
	public function edit($id = 0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Product->save($this->request->data)) {
				$id = $this->Product->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->Product->findById($id);
			$this->request->data = $row;
		}
		$this->set('aProductTypeOptions', $this->ProductType->find('list'));
	}
}
