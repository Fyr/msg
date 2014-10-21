<?php
App::uses('AdminController', 'Controller');
class AdminFaqController extends AdminController {
	public $name = 'AdminFaq';
	public $components = array('Auth', 'Table.PCTableGrid');
	public $uses = array('Faq');
	
	public function index() {
		$this->paginate = array(
			'fields' => array('id', 'question')
		);
		$this->PCTableGrid->paginate('Faq');
	}
	
	public function edit($id = 0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Faq->save($this->request->data)) {
				$id = $this->Faq->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} elseif ($id) {
			$row = $this->Faq->findById($id);
			$this->request->data = $row;
		}
	}
}
