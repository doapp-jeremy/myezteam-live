<?php
class PlayersController extends AppController
{

	var $name = 'Players';

	function index($teamId = null)
	{
    //$team = $this->verifyMember($this->Player->Team, $teamId);
    //$this->verifyManager($this->Player->Team, $teamId);
    $this->verifyMember($this->Player->Team, $teamId);
    
    $this->paginate['conditions'] = array('Player.team_id' => $teamId);
    $this->paginate['order'] = 'User.last_login DESC';
    $this->paginate['limit'] = 20;
    
    $players['Player'] = $this->paginate();
    $this->Player->Team->expects();
    $team = $this->Player->Team->findById($teamId);
    $players['Team'] = $team['Team'];
    
    if ($this->isTeamManager($this->Player->Team, $teamId))
    {
      $this->set('isTeamManager', true);
    }
    
    $this->set(compact('players'));
	}

	function add($teamId = null)
	{
	  $this->verifyManager($this->Player->Team, $teamId);
	  $this->data['Player']['team_id'] = $teamId;
	  
	  // TODO: find all contacts not on the team
	  
	  $this->_setFormData();
	}

	function _setFormData()
	{
	  $associations[] = array('label' => 'Player Type', 'field' => 'player_type');
	  $player_types = $this->Player->PlayerType->find('list');
	  $this->set(compact('associations', 'player_types'));
	  return parent::_setFormData();
	}
	
	function edit($playerId = null)
	{
	  $this->Player->expects('User');
	  $player = $this->verifyManager($this->Player, $playerId);
	  $this->data = $player;
	  $this->_setFormData();
	}

	function delete($playerId = null)
	{
	  $this->Player->expects('User', 'Team');
	  $player = $this->verifyManager($this->Player, $playerId);
	  if (!empty($player))
	  {
	    if ($this->Player->delete($playerId))
	    {
	      $this->Session->setFlash($player['User']['nameOrEmail'] . ' was removed from the ' . $player['Team']['name']);
        $this->data = $player;
        parent::delete($player['User']['nameOrEmail'] . ' was removed from the ' . $player['Team']['name']);
        return;
	    }
	    else
	    {
	      $this->error('Players::delete', 'Could not delete player: ' . $playerId, $this->getUserID());
	      $this->Session->setFlash('There was a problem trying to delete player ' . $player['User']['nameOrEmail'] . ' The error has been logged.');
	    }
	    $this->redirect(array('controller' => 'teams', 'action' => 'index'));
	  }
	}

	function responses($eventId = null)
	{
	  $this->Player->Team->Event->expects('ResponseType');
	  $event = $this->verifyMember($this->Player->Team->Event, $eventId);

	  if (!isset($event['ResponseType']))
	  {
	    $responseType = $this->Player->Response->ResponseType->findById($event['Event']['response_type_id']);
	    $event['ResponseType'] = $responseType['ResponseType'];
	  }

	  //$this->Player->recursive = 2;
    $userBelongs = $this->Player->belongsTo['User'];
    $typeBelongs = $this->Player->belongsTo['PlayerType'];
    $this->Player->belongsTo = array();
    $this->Player->belongsTo['User'] = $userBelongs;
    $this->Player->belongsTo['PlayerType'] = $typeBelongs;
    
	  $this->Player->hasMany['Response']['conditions'] = array('Response.event_id' => $eventId);
    $this->Player->hasMany['Response']['limit'] = 1;
    $this->Player->hasMany['Response']['order'] = 'Response.id DESC';
    
    $this->paginate['conditions'] = array('Player.team_id' => $event['Event']['team_id']);
    $this->paginate['conditions'] = array('Player.team_id' => $event['Event']['team_id']);
	  $this->paginate['order'] = 'User.first_name';
    //$this->paginate['order'] = 'Response.created';
	  $this->paginate['limit'] = 20;
	  $players = $this->paginate();

	  foreach ($players as &$player)
	  {
	    if (isset($player['Response']) && !empty($player['Response']) && !isset($player['Response'][0]['ResponseType']))
	    {
	      $this->Player->Response->ResponseType->expects();
	      $responseType = $this->Player->Response->ResponseType->findById($player['Response'][0]['response_type_id']);
	      $player['Response'][0]['ResponseType'] = $responseType['ResponseType'];
	    }
	  }
	  
	  if (isset($this->params['requested']))
	  {
	    return $players;
	  }
	  
	  $isManager = $this->isTeamManager($this->Player->Team, $event['Event']['team_id']);
	  
	  $this->set(compact('players', 'event', 'isManager'));
	}

	function actions($playerId = null)
  {
    $this->Player->expects();
    $actions = array();
    if ($playerId == null)
    {
      $actions = array('List', 'class' => 'actions small', 'default' => 'List');
    }
//    else if ($this->isPlayerManager($playerId))
//    {
//      array_push($actions, 'AddPlayer');
//    }
//    else
//    {
//      $actions = array('Info', 'RSVP', 'class' => 'small actions eventActions', 'default' => 'Info');
//      $this->Event->Team->expects('Creator');
//      $event = $this->verifyEventMember($eventId);
//      if ($this->isEventManager($event))
//      {
//        $actions = array_merge($actions, array('Emails', 'RSVPS'));
//      }
//    }
    
    if (isset($this->params['requested']))
    {
      return $actions;
    }
    $this->invalidPage(array('controller' => 'players', 'action' => 'index'), '/players/actions/' . $playerId);
  }
	
  function validator()
  {
    //if (isset($this->data['User']['email']) && (strlen($this->data['User']['email']) > 0))
    if (!isset($this->data['Player']['id']))
    {
      $this->layout = '';
      $this->AjaxValid->return = 'html';
      $this->AjaxValid->changeClass('errors');
      $this->AjaxValid->setForm($this->data);
      $data = $this->AjaxValid->validate('User');
      if (!$this->AjaxValid->valid)
      {
        $valid = false;
        $this->set(compact('data', 'valid'));
        return;
      }

      $this->Player->User->expects();
      $user = $this->Player->User->find(array('User.email' => $this->data['User']['email']), array('User.id', 'User.email'));
      if (!empty($user))
      {
        // TODO: see if the player is already on the team
        $this->data['Player']['user_id'] = $user['User']['id'];
      }
      else
      {
        // create a new user
        $first_name = NULL;
        $last_name = NULL;
        foreach (array('first_name', 'last_name') as $field)
        {
          if (isset($this->data['User'][$field]) && (strlen($this->data['User'][$field])))
          {
            ${$field} = $this->data['User'][$field];
          }
        }
        $user = array('User' => array('email' => $this->data['User']['email'], 'first_name' => $first_name, 'last_name' => $last_name));
        if (!$this->Player->User->save($user))
        {
          $this->error('Players::validator', 'Could not create new user: ' . $this->data['User']['email'], $this->getUserID());
        }
        else
        {
          $this->data['Player']['user_id'] = $this->Player->User->getLastInsertID();
        }
      }
    }
    
    return parent::validator();
  }
  
//	function admin_index() {
//		$this->Player->recursive = 0;
//		$this->set('players', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Player.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('player', $this->Player->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Player->create();
//			if ($this->Player->save($this->data)) {
//				$this->Session->setFlash(__('The Player has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Player could not be saved. Please, try again.', true));
//			}
//		}
//		$teams = $this->Player->Team->find('list');
//		$users = $this->Player->User->find('list');
//		$this->set(compact('teams', 'users'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Player', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Player->save($this->data)) {
//				$this->Session->setFlash(__('The Player has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Player could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Player->read(null, $id);
//		}
//		$teams = $this->Player->Team->find('list');
//		$users = $this->Player->User->find('list');
//		$this->set(compact('teams','users'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Player', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Player->del($id)) {
//			$this->Session->setFlash(__('Player deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}
//
	
	function update()
	{
    $this->verifyAdminAuthentication();
	  $this->Player->expects();
	  $players = $this->Player->findAll();
	  foreach ($players as $player)
	  {
	    $playerType = $this->Player->PlayerType->findByName($player['Player']['type']);
	    $player['Player']['player_type_id'] = $playerType['PlayerType']['id'];
	    if (!$this->Player->save($player))
	    {
	      debug('Could not save player');
	      debug($player);
	    }
	  }
	  debug('done');
	  exit();
	}
}
?>