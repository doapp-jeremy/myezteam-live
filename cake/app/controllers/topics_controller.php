<?php
class TopicsController extends AppController {

	var $name = 'Topics';
	
	function index($teamId = null)
	{
	  $this->Topic->expects();
    $this->verifyMember($this->Topic->Team, $teamId);
    
    $this->paginate['conditions'] = array('Topic.team_id' => $teamId);
    $this->paginate['order'] = 'Topic.modified DESC';
    $this->paginate['limit'] = 5;
    
    $this->Topic->expects();
    $this->Topic->hasMany = array();
    $this->Topic->belongsTo = array();
    
    $this->Topic->recursive = 0;
    $topics['Topic'] = $this->paginate();
    $this->Topic->Team->expects();
    $team = $this->Topic->Team->findById($teamId);
    $topics['Team'] = $team['Team'];
    $this->set(compact('topics'));
	}

	function add($teamId = null, $eventId = null)
	{
	  if ($teamId)
	  {
	    $this->verifyMember($this->Topic->Team, $teamId);
	    $this->data['Topic']['team_id'] = $teamId;
	  }
	  if ($eventId)
	  {
	    $this->verifyMember($this->Topic->Event, $eventId);
	    $this->data['Topic']['event_id'] = $eventId;
	  }
	  $this->_setFormData();
	}

	function _setFormData()
	{
	  $this->Topic->Team->expects();
    //$teams = $this->Topic->Team->findUsersTeams($this->getUserId());
    $teams = $this->findUsersTeams($this->Topic->Team);
    if (!empty($teams))
    {
      $teams = Set::combine($teams, '{n}.Team.id', '{n}.Team.name');
    }
    $this->Topic->Event->expects();
//    $events = $this->Topic->Event->findUsersEvents($this->getUserId());
//    if (!empty($events))
//    {
//      $events = Set::extract($events, '{n}.Event.id', '{n}.Event.name');
//    }
//    $this->set(compact('teams', 'events'));
    $this->set(compact('teams'));
    return parent::_setFormData();
	}
	
	function validator()
	{
	  $userId = $this->getUserID();
	  $ip = $this->getIP();
	  $this->data['Topic']['user_id'] = $userId;
    $this->data['Topic']['ip'] = $ip;
	  parent::validator();
	  if ($this->AjaxValid->valid)
	  {
	    if (isset($this->data['Post']) && isset($this->data['Post']['text']) && (strlen($this->data['Post']['text']) > 0))
	    {
	      $post = array('Post' => array('text' => $this->data['Post']['text'], 'topic_id' => $this->Topic->getLastInsertID(), 'ip' => $ip, 'user_id' => $userId));
	      if (!$this->Topic->Post->save($post))
	      {
	        $this->error('Topics::validator', 'Could not create post', $userId);
	      }
	    }
	  }
	}
	
	function posts($topicId = null)
	{
    $this->Topic->expects('User');
    $topic = $this->verifyMember($this->Topic, $topicId);
    $this->paginate['conditions'] = array('Post.topic_id' => $topicId);
    $this->paginate['order'] = 'Post.created DESC';
    $this->paginate['limit'] = 5;

    $posts['Post'] = $this->paginate('Post');
    $posts['Topic'] = $topic['Topic'];
    $posts['Topic']['User'] = $topic['User'];
    if (isset($this->params['requested']))
    {
      return $posts;
    }
    
    $this->set(compact('posts'));
	}
	
	function view($topicId = null)
	{
	  $this->redirect(array('controller' => 'topics', 'action' => 'posts', $topicId));
	}

//	function admin_index() {
//		$this->Topic->recursive = 0;
//		$this->set('topics', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Topic.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('topic', $this->Topic->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Topic->create();
//			if ($this->Topic->save($this->data)) {
//				$this->Session->setFlash(__('The Topic has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Topic could not be saved. Please, try again.', true));
//			}
//		}
//		$teams = $this->Topic->Team->find('list');
//		$events = $this->Topic->Event->find('list');
//		$users = $this->Topic->User->find('list');
//		$this->set(compact('teams', 'events', 'users'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Topic', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Topic->save($this->data)) {
//				$this->Session->setFlash(__('The Topic has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Topic could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Topic->read(null, $id);
//		}
//		$teams = $this->Topic->Team->find('list');
//		$events = $this->Topic->Event->find('list');
//		$users = $this->Topic->User->find('list');
//		$this->set(compact('teams','events','users'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Topic', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Topic->del($id)) {
//			$this->Session->setFlash(__('Topic deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}

}
?>