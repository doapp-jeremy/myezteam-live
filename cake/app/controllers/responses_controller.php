<?php
class ResponsesController extends AppController {

	var $name = 'Responses';

	function email_rsvp($event_id = null, $player_id = null, $response_type_id = null, $responseKey = null)
	{
	  $this->verifyNotNull($event_id);
	  $this->verifyNotNull($player_id);
    $this->verifyNotNull($response_type_id);
    $this->verifyNotNull($responseKey);
    
	  $verifyKey = $this->generateResponseKey($event_id, $player_id);
	  if ($verifyKey != $responseKey)
	  {
	    $this->Session->setFlash('You have requested in valid page.  Please verify that you copied the link correctly.');
	    $this->redirect('/');
	  }
	  
	  $this->data['Response'] = compact('event_id', 'player_id', 'response_type_id');
	  $this->rsvp();
	  $this->render('rsvp');
	}
	
	function rsvp()
	{
	  if (empty($this->data))
	  {
	    $this->invalidPage('/', '/responses/rsvp/');
	  }
    $eventId = $this->data['Response']['event_id'];
    $playerId = $this->data['Response']['player_id'];
	  $isLoggedIn = $this->isLoggedIn();
    if (!$isLoggedIn)
    {
      // if the user isn't logged in, set their timezone
      $query = 'SELECT TimeZone.value FROM players AS Player LEFT JOIN users AS User ON User.id=Player.user_id';
      $query .= ' LEFT JOIN time_zones AS TimeZone ON TimeZone.id=User.time_zone_id';
      $query .= ' WHERE Player.id=' . $playerId;
      $result = $this->Response->Player->User->query($query);
      if (!empty($result))
      {
        date_default_timezone_set($result[0]['TimeZone']['value']);
      }
    }
    $this->data['Response']['ip'] = $this->getIP();
	  $this->data['Response']['created'] = date('Y-m-d H:i:s');
    $this->Response->ResponseType->expects();
    $response = $this->data;
    $responseType = $this->Response->ResponseType->findById($this->data['Response']['response_type_id']);
    $response['ResponseType'] = $responseType['ResponseType'];
    $this->Response->Event->expects('ResponseType', 'Team');
    $event = $this->Response->Event->findById($eventId);
    
    if (!$this->Response->save($this->data))
	  {
	    $this->error('Responses::rsvp', 'Could not save response for event: ' . $eventId . ' and player: ' . $playerId);
	    $this->Session->setFlash('There was a problem trying to RSVP.  Its possible that the event no longer exists, or you are no longer a player on the team');
	  }
	  else
	  {
      $this->Session->setFlash('Thank you for your RSVP to event ' . $event['Event']['name'] . ': ' . Inflector::humanize($responseType['ResponseType']['name']));
	  }
    $this->set(compact('response', 'event', 'isLoggedIn'));
	}
	
	function add($eventId = null, $playerId = null)
	{
	  $event = $this->verifyMember($this->Response->Event, $eventId);
    $conditions = array('Player.team_id' => $event['Event']['team_id']);
    if (!$playerId)
    {
      $userId = $this->getUserID();
      $conditions[] = array('Player.user_id' => $userId);
    }
    else
    {
      $conditions[] = array('Player.id' => $playerId);
    }

    $this->Response->Player->expects('User');
    $player = $this->Response->Player->find($conditions);
    if (empty($player))
    {
      $userMsg = 'Could not RSVP for event ' . $event['Event']['name'] . '.  It is possible that you are no longer apart of the team.';
      $url = array('controller' => 'teams', 'action' => 'index');
      $this->error('Responses::add', 'Could not find response for event: ' . $eventId . ' and user id: ' . $userId, $userId, $userMsg, $url);
    }
    $userName = $player['User']['nameOrEmail'];

    $this->data['Response']['event_id'] = $eventId;
    $this->data['Response']['player_id'] = $player['Player']['id'];

    $this->set(compact('userName'));
    $this->_setFormData();
	}
	
	function _setFormData()
	{
	  $this->data['Response']['ip'] = $this->getIP();
	  
	  $associations[] = array('field' => 'response_type_id', 'label' => 'RSVP');
	  $responseTypes = $this->Response->ResponseType->find('list', array('conditions' => array('ResponseType.name!="no_response"')));
	  foreach ($responseTypes as $id => $value)
	  {
	    $response_types[$id] = Inflector::humanize($value);
	  }
	  //$this->set(compact('associations', 'response_types'));
    $this->set(compact('response_types'));
	  return parent::_setFormData();
	}

	function validator()
	{
	  parent::validator();
	}

//	function admin_index() {
//		$this->Response->recursive = 0;
//		$this->set('responses', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Response.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('response', $this->Response->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Response->create();
//			if ($this->Response->save($this->data)) {
//				$this->Session->setFlash(__('The Response has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Response could not be saved. Please, try again.', true));
//			}
//		}
//		$records = $this->Response->Record->find('list');
//		$this->set(compact('records'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Response', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Response->save($this->data)) {
//				$this->Session->setFlash(__('The Response has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Response could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Response->read(null, $id);
//		}
//		$records = $this->Response->Record->find('list');
//		$this->set(compact('records'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Response', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Response->del($id)) {
//			$this->Session->setFlash(__('Response deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}
//

  /*
   * convert all responses to the new format
   */
  function update()
  {
    $this->verifyAdminAuthentication();
    // get the response types
    $yes = $this->Response->ResponseType->findByName('yes');
    $probable = $this->Response->ResponseType->findByName('probable');
    $maybe = $this->Response->ResponseType->findByName('maybe');
    $no = $this->Response->ResponseType->findByName('no');
    
    // get all the responses
    $responses = $this->Response->findAll();
    foreach ($responses as $response)
    {
      $response['Response']['response_type_id'] = ${$response['Response']['attending']}['ResponseType']['id'];
      $response['Response']['event_id'] = $response['Record']['event_id'];
      $response['Response']['player_id'] = $response['Record']['player_id'];
      //debug($response);
      // save the response
      if ($this->Response->save($response))
      {
        //debug('SAVED');
      }
      else
      {
        debug('ERROR');
      }
    }
    debug('done');
    exit();
  }
}
?>