<?php
App::uses('AdminController', 'Controller');
class AdminContractorsController extends AdminController {
	public $name = 'AdminContractors';
	public $components = array('Auth', 'Table.PCTableGrid');
	public $uses = array('Contractor');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('id', 'created', 'title', 'contact_person', 'phone', 'email')
		);
		$this->PCTableGrid->paginate('Contractor');
	}
	
	public function edit($id = 0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Contractor->save($this->request->data)) {
				$id = $this->Contractor->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->Contractor->findById($id);
			$this->request->data = $row;
		}
	}
}
