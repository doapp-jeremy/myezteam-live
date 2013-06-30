<?php
class PicturesController extends AppController {

	var $name = 'Pictures';
	var $helpers = array('Html', 'Form');

//	function index() {
//		$this->Picture->recursive = 0;
//		$this->set('pictures', $this->paginate());
//	}
//
//	function view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Picture.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('picture', $this->Picture->read(null, $id));
//	}
//
//	function add() {
//		if (!empty($this->data)) {
//			$this->Picture->create();
//			if ($this->Picture->save($this->data)) {
//				$this->Session->setFlash(__('The Picture has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Picture could not be saved. Please, try again.', true));
//			}
//		}
//		$users = $this->Picture->User->find('list');
//		$this->set(compact('users'));
//	}
//
//	function edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Picture', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Picture->save($this->data)) {
//				$this->Session->setFlash(__('The Picture has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Picture could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Picture->read(null, $id);
//		}
//		$users = $this->Picture->User->find('list');
//		$this->set(compact('users'));
//	}
//
//	function delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Picture', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Picture->del($id)) {
//			$this->Session->setFlash(__('Picture deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}
//
//
//	function admin_index() {
//		$this->Picture->recursive = 0;
//		$this->set('pictures', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Picture.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('picture', $this->Picture->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Picture->create();
//			if ($this->Picture->save($this->data)) {
//				$this->Session->setFlash(__('The Picture has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Picture could not be saved. Please, try again.', true));
//			}
//		}
//		$users = $this->Picture->User->find('list');
//		$this->set(compact('users'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Picture', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Picture->save($this->data)) {
//				$this->Session->setFlash(__('The Picture has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Picture could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Picture->read(null, $id);
//		}
//		$users = $this->Picture->User->find('list');
//		$this->set(compact('users'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Picture', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Picture->del($id)) {
//			$this->Session->setFlash(__('Picture deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}

}
?>