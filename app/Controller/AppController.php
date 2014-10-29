<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {
	public $components = array('Session');
	public $uses = array('ChatUser');
    public $paginate;
    protected $currUser = array(), $currUserID;
    
    public function __construct($request = null, $response = null) {
	    $this->_beforeInit();
	    parent::__construct($request, $response);
	    $this->_afterInit();
	}
	
	protected function _beforeInit() {
	    // Add here components, models, helpers etc that will be also loaded while extending child class
	}

	protected function _afterInit() {
	    // after construct actions here
	}
	
	public function isAuthorized($user) {
		return true;
	}
	
	public function beforeFilter() {
		if (TEST_ENV) {
			$userID = Hash::get($this->request->params, 'pass.0');
			$action = Hash::get($this->request->params, 'action');
			if ($userID && $action == 'auth') {
				$this->Session->write('currUser.id', $userID);
				$this->redirect('/');
				return false;
			}
			$this->currUserID = $this->Session->read('currUser.id');
			if (!$this->currUserID) {
				$this->autoRender = false;
				exit('You must be authorized');
			}
		} else {
			$this->loadModel('ClientProject');
			$userData = ClientProject::getUserAuthData();
			$this->currUserID = $userData['user_id'];
		}
		$this->currUser = $this->ChatUser->getUser($this->currUserID);
		//fdebug($this->currUser, 'curr_user'.$this->currUserID.'.log', false);
	}
	
	public function beforeRender() {
		$this->set('currUser', $this->currUser);
		$this->set('currUserID', $this->currUserID);
	}
}
