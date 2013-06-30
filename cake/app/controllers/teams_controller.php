<?php
class TeamsController extends AppController
{

  var $name = 'Teams';
  var $uses = array('Team', 'TeamsUser', 'UsersUser');
  var $components = array('GoogleCal');
  var $eventsLimit = 1;
  
  function beforeFilter()
  {
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))) . DS . 'vendors');
    include('Zend/gdata_connect.php');
    parent::beforeFilter();
  }
  
  function index()
  {
    //$this->Team->expects('Creator');
    //$teams = $this->Team->findUsersTeams($userId);
    $this->Team->expects();
    $teams = $this->findUsersTeams($this->Team);
    $i = 0;
    foreach ($teams as &$team)
    {
      $conditions = array('Event.team_id' => $team['Team']['id'], 'Event.end > "' . date('Y-m-d H:i:s') . '"');
      $order = 'Event.start ASC';
      $this->Team->Event->expects();
      $event = $this->Team->Event->find($conditions, null, $order, 1);
      $team['Event']  = $event['Event'];
      if ($i++ == 0)
      {
        $team['Events'] = $this->events($team['Team']['id']);
      }
    }
    $this->set(compact('teams'));
  }

  function view($teamId = null)
  {
    $this->verifyMember($this->Team, $teamId);
    if ($this->isTeamManager($teamId) !== false)
    {
      $isOwner = true;
    }
//    $this->paginate['conditions'] = array('Topic.team_id' => $teamId);
//    $this->paginate['order'] = 'Topic.modified DESC';
//    $this->paginate['limit'] = 5;
//    // for some reason expects() doesn't work with pagination
//    // unbinding manually for now, not sure yet if it gets reset in afterFind
//    //$this->Team->Topic->expects();
//    $this->Team->Topic->hasMany = array();
//    $this->Team->Topic->belongsTo = array();
//    $topics = $this->paginate('Topic');
//    $this->Team->expects();
//    $team = $this->Team->findById($teamId);
//    $team['Topic'] = $topics;
    $this->Team->expects();
    $team = $this->Team->findById($teamId);
    $events = $this->events($teamId);
    $team['Event'] = $events;
    if (isset($this->params['requested']))
    {
      return $team;
    }
    $this->set(compact('team', 'isOwner'));
  }

  function add()
  {
    $managers = array();
    if ($this->isAdmin())
    {
      $this->User->expects();
      $users = $this->User->find('all', array('fields' => 'id, first_name, last_name, email'));
      $users = Set::combine($users, '{n}.User.id', '{n}.User.nameAndEmail');
      $managers = $users;
    }
    else
    {
      $teams = $this->findUsersTeams($this->Team);
      $contacts = $this->User->findAllContacts($this->getUserID(), $teams);
      $managers = array();
      if (!empty($conacts))
      {
        $managers = Set::combine($contacts, '{n}.User.id', '{n}.User.nameOrEmail');
      }
    }
    $this->set(compact('users', 'managers'));
    $this->_setFormData();
  }

  function edit($teamId = null)
  {
    $this->verifyManager($this->Team, $teamId);
    $this->Team->expects('Creator', 'Managers');
    $this->data = $this->Team->findById($teamId);

    // get list of current managers
    $mgrs = array();
    if (!empty($this->data['Managers']))
    {
      $mgrs = Set::combine($this->data['Managers'], '{n}.id', '{n}.nameOrEmail');
    }
    $creator = $this->data['Creator'];
    // add the owner to the list of current managers
    $mgrs[$creator['id']] = $creator['nameOrEmail'];

    // find all players that aren't managers because we dont' want duplicates in the list
    $playerCondition = 'TRUE';
    if (!empty($mgrs))
    {
      $playerCondition = 'Player.user_id NOT IN (' . implode(', ', array_keys($mgrs)) . ')';
    }
    // remove the owner from the list so they can't be removed as a manager
    unset($mgrs[$creator['id']]);

    $players = $this->Team->Player->find('all', array('fields' => array('Player.user_id', 'User.*'), 
            'conditions' => array('Player.team_id' => $teamId, $playerCondition)));

    if (!empty($players))
    {
      $managers = Set::combine($players, '{n}.Player.user_id', '{n}.User.nameOrEmail');
    }
    // add the managers to the list
    foreach ($mgrs as $userId => $name)
    {
      $managers[$userId] = $name;
    }

    if ($this->isAdmin())
    {
      $this->Team->Creator->expects();
      $users = $this->Team->Creator->find('all', array('fields' => 'id, first_name, last_name, email'));
      $users = Set::combine($users, '{n}.Creator.id', '{n}.Creator.nameAndEmail');
    }
    
    //$this->data['Team']['isAjax'] = $this->isAjax();
    $this->set(compact('users', 'managers'));
    $this->_setFormData();
  }
  
  function _setFormData()
  {
//    $this->data['Team']['create_calendar'] = !(isset($this->data['Team']['calendar_id']) && $this->data['Team']['calendar_id']);
    $this->data['Team']['user_id'] = $this->getUserID();
    return parent::_setFormData();
  }
  
  function edit_players($teamId = null)
  {
    $this->verifyManager($this->Team, $teamId);
    
    $this->Team->Player->expects('User');
    $players = $this->Team->Player->findAllByTeamId($teamId);
    $this->set(compact('players'));
  }
  
  function delete($teamId = null)
  {
    $this->Team->expects();
    $this->verifyCreator($this->Team, $teamId);
    $team = $this->Team->findById($teamId);
    if (!empty($team))
    {
      if ($this->Team->delete($teamId))
      {
        $this->Session->setFlash($team['Team']['name'] . ' has been deleted.');
        if (isset($team['Team']['calendar_id']) && $team['Team']['calendar_id'])
        {
          try
          {
            $this->GoogleCal->deleteCalendar($team['Team']['calendar_id']);
          }
          catch (Exception $e)
          {
            // ignore exception, probably a calendar created by the user, trying to delete with myezteam.
          }
        }
      }
      else
      {
        $this->error('Team::delete', 'Could not delete team: ' . $teamId . ': ' . $team['Team']['name'], $this->getUserID());
      }
    }
    else
    {
      $this->error('Team::delete', 'Could not find team: ' . $teamId, $this->getUserID());
      $this->Session->setFlash('There was an error while trying to delete team ' . $team['Team']['name']);
    }
    $this->redirect(array('controller' => 'teams', 'action' => 'index'));
  }

  function actions($teamId = null)
  {
    $this->verifyMember($this->Team, $teamId);
    $actions = array('Info');
    $isTeamMgr = $this->isTeamManager($this->Team, $teamId);
    $this->Team->expects();
    $team = $this->Team->findById($teamId);
    if ($isTeamMgr || (isset($team['Team']['calendar_id']) && (strlen($team['Team']['calendar_id']) > 0)))
    {
      $actions[] =  'Calendar';
    }
    $moreActions = array('Events', 'Topics', 'Players', 'class' => 'small actions teamActions', 'default' => 'Events');
    $actions = array_merge($actions, $moreActions);
//    if ($this->isAdmin() || $this->isDevEnv())
//    {
//      array_push($actions, 'Calendar');
//    }
    //if ($this->isTeamManager($this->Team, $teamId))
    if ($isTeamMgr)
    {
      array_push($actions, 'DefaultEmails');
    }
    return $actions;
  }
  
  function events_actions($teamId = null)
  {
    $actions = array('Upcoming');
    if ($this->isTeamManager($this->Team, $teamId))
    {
      array_push($actions, 'AddEvent');
    }
    $actions = array_merge($actions, array('Past', 'class' => 'actions small', 'default' => 'Upcoming'));
    
    if (isset($this->params['requested']))
    {
      return $actions;
    }

    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/teams/events_actions/' . $teamId);
  }

  function players_actions($teamId = null)
  {
    $actions = array('Player');
    if ($teamId)
    {
      if ($this->isTeamManager($this->Team, $teamId))
      {
        $actions = array('List', 'class' => 'actions small', 'default' => 'List');
        array_push($actions, 'AddPlayer');
       //array_push($actions, 'EditPlayers'); // TODO: create edit players form
      }
    }
    
    if (isset($this->params['requested']))
    {
      return $actions;
    }

    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/teams/players_actions/' . $teamId);
  }

  function topics_actions($teamId = null)
  {
    $actions = array('List', 'AddTopic', 'class' => 'actions small', 'default' => 'List');

    if (isset($this->params['requested']))
    {
      return $actions;
    }

    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/teams/topics_actions/' . $teamId);
  }

  function events($teamId = null)
  {
    $this->verifyMember($this->Team, $teamId);
    // for some reason expects() doesn't work with pagination
    // unbinding manually for now, not sure yet if it gets reset in afterFind
    //$this->Team->Event->expects();

    $this->paginate['conditions'] = array('Event.team_id' => $teamId, 'Event.end >= "' . date('Y-m-d H:i:s') . '"');
    $this->paginate['order'] = 'start ASC';
    $this->paginate['limit'] = $this->eventsLimit;
    $this->Team->Event->hasMany = array();
    $responseTypeBelongsTo = $this->Team->Event->belongsTo['ResponseType'];
    //$this->Team->Event->belongsTo = array();
    $this->Team->Event->belongsTo['ResponseType'] = $responseTypeBelongsTo;
        
    $events['Event'] = $this->paginate('Event');
    $this->Team->expects('Creator');
    $team = $this->Team->findById($teamId);
    $events['Team'] = $team['Team'];
    
    $this->set(compact('events'));
    return $events;
  }

  function events_upcoming($teamId = null)
  {
//    $this->Team->expects('Creator');
    $team = $this->verifyMember($this->Team, $teamId);

    // for some reason expects() doesn't work with pagination
    // unbinding manually for now, not sure yet if it gets reset in afterFind
    //$this->Team->Event->expects();

    $this->paginate['conditions'] = array('Event.team_id' => $teamId, 'Event.end >= "' . date('Y-m-d H:i:s') . '"');
    $this->paginate['order'] = 'start ASC';
    $this->paginate['limit'] = $this->eventsLimit;
    $this->Team->Event->hasMany = array();
//    $responseTypeBelongsTo = $this->Team->Event->belongsTo['ResponseType'];
//    $this->Team->Event->belongsTo['ResponseType'] = $responseTypeBelongsTo;
        
    $events['Event'] = $this->paginate('Event');
    $this->Team->expects('Creator');
    $team = $this->Team->findById($teamId);
    $events['Team'] = $team['Team'];
    
    if (isset($this->params['requested']))
    {
      return $events;
    }

    $this->set(compact('events', 'team'));
  }

  function events_past($teamId = null)
  {
    $team = $this->verifyMember($this->Team, $teamId);

    // for some reason expects() doesn't work with pagination
    // unbinding manually for now, not sure yet if it gets reset in afterFind
    //$this->Team->Event->expects();

    $this->paginate['conditions'] = array('Event.team_id' => $teamId, 'Event.end < "' . date('Y-m-d H:i:s') . '"');
    $this->paginate['order'] = 'end DESC';
    $this->paginate['limit'] = $this->eventsLimit;
    $this->Team->Event->hasMany = array();
//    $responseTypeBelongsTo = $this->Team->Event->belongsTo['ResponseType'];
//    $this->Team->Event->belongsTo['ResponseType'] = $responseTypeBelongsTo;
    
    $events['Event'] = $this->paginate('Event');
    $this->Team->expects('Creator');
    $team = $this->Team->findById($teamId);
    $events['Team'] = $team['Team'];
    
    if (isset($this->params['requested']))
    {
      return $events;
    }

    $this->set(compact('events', 'team'));
  }

  function snapshot($teamId = null)
  {
    $this->Team->expects('Creator', 'Event', 'Topic');
    $team = $this->verifyMember($this->Team, $teamId);
    if (isset($this->params['requested']))
    {
      return $team;
    }
    $isAjax = $this->isAjax();

    $this->set(compact('team', 'isAjax'));
  }

  function topics($teamId = null)
  {
    $this->verifyMember($this->Team, $teamId);
    $this->paginate['conditions'] = array('Topic.team_id' => $teamId);
    $this->paginate['order'] = 'Topic.modified DESC';
    $this->paginate['limit'] = 5;
    // for some reason expects() doesn't work with pagination
    // unbinding manually for now, not sure yet if it gets reset in afterFind
    //$this->Team->Topic->expects();
    $this->Team->Topic->hasMany = array();
    $this->Team->Topic->belongsTo = array();

    $topics['Topic'] = $this->paginate('Topic');
    $team = $this->Team->expects();
    $team = $this->Team->findById($teamId);
    $topics['Team'] = $team['Team'];
    if (isset($this->params['requested']))
    {
      return $topics;
    }
    
    $this->set(compact('topics'));
  }

  function players($teamId = null)
  {
    $this->Team->recursive = 1;
    $this->Team->expects('Creator');
    $this->verifyMember($this->Team, $teamId);
    
    $this->paginate['conditions'] = array('Player.team_id' => $teamId);
    $this->paginate['order'] = 'User.last_login DESC';
    $this->paginate['limit'] = 20;
    // for some reason expects() doesn't work with pagination
    // unbinding manually for now, not sure yet if it gets reset in afterFind
    //$this->Team->Player->expects();
    $this->Team->Player->hasMany = array();
    $userBelongsTo = $this->Team->Player->belongsTo['User'];
    $typeBelongsTo = $this->Team->Player->belongsTo['PlayerType'];
    $this->Team->Player->belongsTo = array('User' => $userBelongsTo, 'PlayerType' => $typeBelongsTo);
    
    $players['Player'] = $this->paginate('Player');
    $this->Team->expects();
    $team = $this->Team->findById($teamId);
    $players['Team'] = $team['Team'];
    if ($this->isTeamManager($this->Team, $team['Team']['id']))
    {
      $this->set('isTeamManager', true);
    }
    
    if (isset($this->params['requested']))
    {
      return $players;
    }
    $this->set(compact('players'));
  }

  function info($teamId = null)
  {
    if (!$this->isAjax())
    {
      $this->redirect(array('controller' => 'teams', 'action' => 'view', $teamId));
    }
    $this->verifyMember($this->Team, $teamId);
    $this->Team->expects('Creator', 'Managers');
    $team = $this->Team->findById($teamId);
    if (isset($this->params['requested']))
    {
      return $team;
    }
    $this->set(compact('team'));
  }

  function set_default_emails($teamId = null)
  {
    $this->verifyManager($this->Team, $teamId);
    
    $eventNames = array();
    $this->Team->Event->expects();
    $events = $this->Team->Event->findAll(array('Event.team_id' => $teamId, 'Event.start > "' . date('Y-m-d H:i:s') . '"'), array('Event.id', 'Event.name'));
    if (!empty($events))
    {
      $eventIds = Set::extract($events, '{n}.Event.id');
      $this->Team->Event->Email->deleteAll('Email.event_id IN (' . implode(', ', $eventIds) . ')');
      $this->Team->DefaultEmail->expects('ResponseTypes', 'PlayerTypes');
      $defaultEmails = $this->Team->DefaultEmail->findAllByTeamId($teamId);
      foreach ($eventIds as $eventId)
      {
        foreach ($defaultEmails as $defaultEmail)
        {
          $this->Team->DefaultEmail->fromDefault($defaultEmail, $eventId, 'DefaultEmail', 'DefaultEmail');
        }
      }
      $eventNames = Set::extract($events, '{n}.Event.name');
    }
    $this->set('eventNames', $eventNames);
  }
  
  function default_emails($teamId = null)
  {
    $this->Team->expects('DefaultEmail');
    $this->verifyManager($this->Team, $teamId);
    
    $this->Team->expects('DefaultEmail');
    $this->Team->DefaultEmail->expects('PlayerTypes', 'ResponseTypes');
    $this->Team->recursive = 2;
    $team = $this->Team->findById($teamId);
    
    $this->set(compact('team'));
  }
  
  function drop_down($teamId = null)
  {
    $this->Team->expects();
    $this->verifyMember($this->Team, $teamId);
    $conditions = array('Event.team_id' => $teamId, 'Event.end > "' . date('Y-m-d H:i:s') . '"');
    $order = 'Event.start ASC';
    $this->Team->Event->expects('Team');
    $team = $this->Team->Event->find($conditions, null, $order, 1);
    if (empty($team))
    {
      $this->Team->expects();
      $team = $this->Team->findById($teamId);
    }
    $this->set(compact('team'));
  }
  
  function calendar($teamId = null)
  {
    $this->verifyMember($this->Team, $teamId);
    $this->Team->expects();
    $team = $this->Team->findById($teamId);
    if ($this->isAjax())
    {
      $width = 645;
      $length = 430;
    }
    $user = $this->getUser();
    $isManager = $this->isTeamManager($this->Team, $teamId);
    $this->set(compact('team', 'width', 'length', 'isManager', 'user'));
  }
  
  function create_calendar($teamId = null)
  {
    $this->verifyManager($this->Team, $teamId);
      $this->Team->expects('Managers');
      $team = $this->Team->findById($teamId);
      $managers = $team['Managers'];
      unset($team['Managers']);
      if (!empty($managers))
      {
        $userIds = Set::extract($managers, '{n}.id');
        $team['Managers'] = array('Managers' => $userIds);
      }
      $this->__createCalendar($team);
      $this->redirect(array('controller' => 'teams', 'action' => 'calendar', $team['Team']['id']));
  }

  function __createCalendar($team)
  {
    $this->Team->Creator->expects('TimeZone');
    $creator = $this->Team->Creator->findById($team['Team']['user_id']);
    try
    {
      $calId = $this->GoogleCal->createCalendar($team, $creator);
      echo $calId; exit();
      if ($calId)
      {
        $team['Team']['calendar_id'] = $calId;
        if (!$this->Team->save($team))
        {
          $this->error('Teams::create_calendar', 'Could not create calendar for team: ' . $teamId);
        }
        else
        {
          // create events for the calendar
          $this->__createEventsForCalendar($team['Team']['id'], $team['Team']['calendar_id']);
	  return $calId;
        }
      }
    }
    catch (Exception $e)
    {
      $this->error('Teams::__createCalendar', 'Could not create team calendar for team: ' . $team['Team']['id'] . ': ' . $e->getMessage());
      echo 'Exception: ' . $e->getMessage();
      print_r($e); exit();
    }
    return 0;
//    return $calId;
  }
  
  
  function __createEventsForCalendar($teamId, $calId)
  {
    $this->Team->Event->expects();
    $events = $this->Team->Event->findAllByTeamId($teamId);
    if (!empty($events))
    {
      $createdEvents = $this->GoogleCal->createEvents($calId, $events);
      // save google calendar ids
      foreach ($createdEvents as $eventId => $calEvent)
      {
        $calEventId = $calEvent->getId();
        if (!$this->Team->Event->save(array('Event' => array('id' => $eventId, 'cal_event_id' => $calEventId))))
        {
          $this->error('Teams::create_calendar', 'Could not save google calendar event id: ' . $calEventId . ' for event: ' . $eventId);
        }
      }
    }
  }
  
  function validator()
  {
    if (!isset($this->data['Team']['user_id']) || (strlen($this->data['Team']['user_id']) <= 0))
    {
      $this->data['Team']['user_id'] = $this->getUserID();
    }
    $add = !isset($this->data['Team']['id']);
    parent::validator();
    if ($this->AjaxValid->valid && $add)
    {
       //$this->__createCalendar($this->data);
    }
  }
  
//  function admin_index() {
//    $this->Team->recursive = 0;
//    $this->set('teams', $this->paginate());
//  }
//
//  function admin_view($id = null) {
//    if (!$id) {
//      $this->Session->setFlash(__('Invalid Team.', true));
//      $this->redirect(array('action'=>'index'));
//    }
//    $this->set('team', $this->Team->read(null, $id));
//  }
//
//  function admin_add() {
//    if (!empty($this->data)) {
//      $this->Team->create();
//      if ($this->Team->save($this->data)) {
//        $this->Session->setFlash(__('The Team has been saved', true));
//        $this->redirect(array('action'=>'index'));
//      } else {
//        $this->Session->setFlash(__('The Team could not be saved. Please, try again.', true));
//      }
//    }
//    $users = $this->Team->User->find('list');
//    $users = $this->Team->User->find('list');
//    $this->set(compact('users', 'users'));
//  }
//
//  function admin_edit($id = null) {
//    if (!$id && empty($this->data)) {
//      $this->Session->setFlash(__('Invalid Team', true));
//      $this->redirect(array('action'=>'index'));
//    }
//    if (!empty($this->data)) {
//      if ($this->Team->save($this->data)) {
//        $this->Session->setFlash(__('The Team has been saved', true));
//        $this->redirect(array('action'=>'index'));
//      } else {
//        $this->Session->setFlash(__('The Team could not be saved. Please, try again.', true));
//      }
//    }
//    if (empty($this->data)) {
//      $this->data = $this->Team->read(null, $id);
//    }
//    $users = $this->Team->User->find('list');
//    $users = $this->Team->User->find('list');
//    $this->set(compact('users','users'));
//  }
//
//  function admin_delete($id = null) {
//    if (!$id) {
//      $this->Session->setFlash(__('Invalid id for Team', true));
//      $this->redirect(array('action'=>'index'));
//    }
//    if ($this->Team->del($id)) {
//      $this->Session->setFlash(__('Team deleted', true));
//      $this->redirect(array('action'=>'index'));
//    }
//  }

}
?>
