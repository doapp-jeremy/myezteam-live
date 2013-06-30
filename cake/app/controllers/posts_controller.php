<?php
class PostsController extends AppController {

	var $name = 'Posts';
//  var $helpers = array('Html', 'MyHtml', 'Form', 'Ajax', 'MyAjax', 'Javascript', 'Time');
  var $components = array('AjaxValid');

	function view($postId = null)
	{
	  $this->Post->expects();
	  $post = $this->verifyMember($this->Post, $postId);
	  $this->redirect(array('controller' => 'topics', 'action' => 'view', $post['Post']['topic_id']));
	}

	function add($topicId = null)
	{
	  $this->Post->Topic->expects();
	  $topic = $this->verifyMember($this->Post->Topic, $topicId);
	  $this->data['Post']['user_id'] = $this->getUserID();
	  $this->data['Post']['topic_id'] = $topicId;
	  $this->_setFormData();
	}

	function validator()
	{
	  $this->data['Post']['ip'] = $this->getIP();
	  parent::validator();
	  
	  $topicId = $this->data['Post']['topic_id'];
	  $this->Post->Topic->expects('User');
    $topic = $this->Post->Topic->findById($topicId);
    $this->paginate['conditions'] = array('Post.topic_id' => $topicId);
    $this->paginate['order'] = 'Post.created DESC';
    $this->paginate['limit'] = 5;

    $posts['Post'] = $this->paginate();
    $posts['Topic'] = $topic['Topic'];
    $posts['Topic']['User'] = $topic['User'];
    if (isset($this->params['requested']))
    {
      return $posts;
    }
    
    $this->set(compact('posts'));
	  
	}

//	function admin_index() {
//		$this->Post->recursive = 0;
//		$this->set('posts', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Post.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('post', $this->Post->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Post->create();
//			if ($this->Post->save($this->data)) {
//				$this->Session->setFlash(__('The Post has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Post could not be saved. Please, try again.', true));
//			}
//		}
//		$topics = $this->Post->Topic->find('list');
//		$users = $this->Post->User->find('list');
//		$this->set(compact('topics', 'users'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Post', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Post->save($this->data)) {
//				$this->Session->setFlash(__('The Post has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Post could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Post->read(null, $id);
//		}
//		$topics = $this->Post->Topic->find('list');
//		$users = $this->Post->User->find('list');
//		$this->set(compact('topics','users'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Post', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Post->del($id)) {
//			$this->Session->setFlash(__('Post deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}

}
?>