<?php
class ErrorsController extends AppController {

	var $name = 'Errors';
	var $helpers = array('Html', 'Form');

	function beforeFilter()
	{
	  $this->verifyAdminAuthentication();
	  return parent::beforeFilter();
	}
	
	function index()
	{
		$this->Error->recursive = 0;
		$this->set('errors', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Error.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('error', $this->Error->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Error->create();
			if ($this->Error->save($this->data)) {
				$this->Session->setFlash(__('The Error has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Error could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Error', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Error->save($this->data)) {
				$this->Session->setFlash(__('The Error has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Error could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Error->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Error', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Error->del($id)) {
			$this->Session->setFlash(__('Error deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function admin_index() {
		$this->Error->recursive = 0;
		$this->set('errors', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Error.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('error', $this->Error->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Error->create();
			if ($this->Error->save($this->data)) {
				$this->Session->setFlash(__('The Error has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Error could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Error', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Error->save($this->data)) {
				$this->Session->setFlash(__('The Error has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Error could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Error->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Error', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Error->del($id)) {
			$this->Session->setFlash(__('Error deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>