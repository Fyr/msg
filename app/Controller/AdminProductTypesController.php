<?php
App::uses('AdminController', 'Controller');
class AdminProductTypesController extends AdminController {
	public $name = 'AdminProductTypes';
	public $uses = array('ProductType');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('id', 'title', 'arenda_price')
		);
		$this->PCTableGrid->paginate('ProductType');
	}
	
	public function edit($id = 0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if (!$this->request->data('ProductType.arenda_price')) {
				$this->request->data('ProductType.arenda_price', 0);
			}
			if ($this->ProductType->save($this->request->data)) {
				$id = $this->ProductType->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->ProductType->findById($id);
			$this->request->data = $row;
		}
	}
}
