<?php
class EventsController extends AppController
{
	var $name = 'Events';
	var $components = array('GoogleCal');
	
	function index()
	{
	  $userId = $this->getUserID();
	  if ($userId === false)
	  {
	    $this->invalidPage('/', '/events');
	  }
	  
	  $query = ' SELECT DISTINCT Team.*, Event.*, ResponseType.* FROM users AS User LEFT JOIN players AS Player ON Player.user_id=User.id';
	  $query .= ' LEFT JOIN teams_users AS Manager ON Manager.user_id=User.id';
	  $query .= ' JOIN teams AS Team ON (Team.id=Player.team_id OR Team.id=Manager.team_id OR Team.user_id=User.id)';
	  $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id';
	  $query .= ' JOIN response_types AS ResponseType ON ResponseType.id=Event.response_type_id';
	  $query .= ' WHERE Event.end > "' . date('Y-m-d H:i:s') . '" AND User.id=' . $userId;
    $query .= ' ORDER BY Event.start ASC';
    $query .= ' LIMIT 10';
	  
//	  $query = 'SELECT Event.*, Team.*, DefaultResponse.* FROM users AS User LEFT JOIN players AS Player ON Player.user_id=User.id';
//	  $query .= ' LEFT JOIN teams_users AS Manager ON Manager.user_id=User.id';
//	  $query .= ' JOIN teams AS Team ON (Team.id=Player.team_id OR Team.id=Manager.team_id OR Team.user_id=User.id)';
//	  $query .= ' JOIN events AS Event ON Event.team_id=Team.id';
//	  $query .= ' JOIN response_types AS DefaultResponse ON DefaultResponse.id=Event.response_type_id';
//	  $query .= ' WHERE Event.end > "' . date('Y-m-d H:i:s') . '" AND User.id=' . $userId;
//	  $query .= ' GROUP BY Event.team_id';
//	  $query .= ' ORDER BY Event.start ASC';
//	  $query .= ' LIMIT 10';

	  $events = $this->Event->query($query);
//	  debug($events); exit();
	  
	  $this->set(compact('events'));
	}
	
	function view($eventId = null)
	{
    $this->Event->expects('ResponseType', 'Team');
	  $event = $this->verifyMember($this->Event, $eventId);
//	  if ($this->isAjax())
//	  {
//	    $this->redirect(array('controller' => 'events', 'action' => 'info', $eventId));
//	  }
    $chartSize = '750x300';
		$this->set(compact('event', 'chartSize'));
	}

	function add($teamId = null)
  {
    if ($teamId)
    {
      $this->data['Event']['team_id'] = $teamId;
      $this->Event->Team->expects();
      $team = $this->Event->Team->findById($teamId);
      $this->data['Event']['location'] = $team['Team']['default_location'];
    }
    
    $this->_setFormData();
	}

	function _setFormData()
	{
    $association['name'] = 'team';
	  
    $repeats = array(
      'day' => 'daily',
      'week' => 'weekly',
      'month' => 'monthly',
      'year' => 'yearly'
    );
    $times = array(0 => "Don't Repeat");
    for ($i = 1; $i < 21; ++$i)
    {
      $times[$i] = $i;
    }
    $this->set(compact('repeats', 'times'));
    
    //$teams = $this->Event->Team->findTeamsUserManages($this->getUserID());
    $teams = $this->findUsersTeamsTheyManage($this->Event->Team);
    $teams = Set::combine($teams, '{n}.Team.id', '{n}.Team.name');

    $response_types = $this->Event->ResponseType->find('all');
    $response_types = Set::combine($response_types, '{n}.ResponseType.id', '{n}.ResponseType.humanName');
//    $response_types = $this->Event->ResponseType->find('list');
//    foreach ($response_types as &$responseType)
//    {
//      $responseType = Inflector::humanize($responseType);
//    }
    $this->set(compact('teams', 'response_types', 'association'));
    $this->Event->Team->expects();
    
    return parent::_setFormData();
	}
	
	function edit($eventId = null)
	{
    $this->Event->expects();
	  $event = $this->verifyManager($this->Event, $eventId);
//	  $this->data = $this->Event->findById($eventId);
    $this->data = $event;
	  $this->_setFormData();
	}

	function validator()
	{
	  $edit = isset($this->data['Event']['id']);
	  parent::validator();
	  if ($this->AjaxValid->valid)
	  {
	    $this->data['Event']['start'] = $this->Event->deconstruct('start', $this->data['Event']['start']);
	    $this->data['Event']['end'] = $this->Event->deconstruct('end', $this->data['Event']['end']);
	    $events = array();
	    if (!$edit)
	    {
	      // create a new event
	      array_push($events, $this->data);
	    }
	    else if (isset($this->data['Event']['cal_event_id']) && $this->data['Event']['cal_event_id'])
	    {
	      // update event
	      //$this->Session->write('updatedEvent', $this->data);
	      $this->__updateEvent($this->data);
	    }
	    unset($this->data['Event']['id']);
	    // if repeat, create events
	    if (isset($this->data['Event']['times']) && ($this->data['Event']['times'] > 0))
	    {
	      for ($i = $this->data['Event']['times']; $i > 0; --$i)
	      {
	        $this->Event->create();
	        $repeats = $this->data['Event']['repeats'];
	        $start = $this->data['Event']['start'];
	        $end = $this->data['Event']['end'];
	        $this->data['Event']['start'] = date('Y-m-d H:i:s', strtotime('+1 ' . $repeats, strtotime($start)));
          $this->data['Event']['end'] = date('Y-m-d H:i:s', strtotime('+1 ' . $repeats, strtotime($end)));
          if (!$this->Event->save($this->data))
          {
            $this->error('Events::validator', 'Could not save repeating event: ' . $this->Event->getLastInsertID());
          }
          else
          {
            $this->data['Event']['id'] = $this->Event->getLastInsertID();
            array_push($events, $this->data);
            unset($this->data['Event']['id']);
          }
	      }
	    }
	    $this->Event->Team->expects();
	    $team = $this->Event->Team->findById($this->data['Event']['team_id']);
	    if (isset($team['Team']['calendar_id']) && $team['Team']['calendar_id'])
	    {
	      // create event for the team calendar
//	      set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))) . DS . 'vendors');
//	      include('Zend/gdata_connect.php');
        if (!empty($events))
        {
	       //$this->Session->write('eventsToCreate', array('team_id' => $this->data['Event']['team_id'], 'calId' => $team['Team']['calendar_id'], 'events' => $events));
	        $this->__createEvents($team['Team']['calendar_id'], $events);
        }
//        if ($this->Session->check('updatedEvent') || $this->Session->check('eventsToCreate'))
//        {
//          $authLink = $this->GoogleCal->getAuthLink('events/create_calendar_events/');
//          $data = "<script type='text/javascript'>";
//          $data .= "document.location = '" . $authLink . "';\n";
//          $data .= "</script>";
//          $showTab = false;
//          $this->set(compact('data', 'showTab'));
//        }
	    }
	  }
	}
	
	function __updateEvent($event)
	{
	  $this->GoogleCal->updateEvent($event);
	}
	
	function __createEvents($calId, $events)
	{
	  $createdEvents = $this->GoogleCal->createEvents($calId, $events);
	  // save google calendar ids
	  foreach ($createdEvents as $eventId => $calEvent)
	  {
	    $calEventId = $calEvent->getId();
	    if (!$this->Event->save(array('Event' => array('id' => $eventId, 'cal_event_id' => $calEventId))))
	    {
	      $this->error('Events::__createEvents', 'Could not save google calendar event id');
	    }
	  }
	}
	
//	function create_calendar_events()
//	{
//	  if (!isset($this->params['url']['token']))
//	  {
//	    $this->invalidPage('/', 'teams/create_calendar/'. $teamId);
//	  }
//	  $token = $this->params['url']['token'];
//	  if ($this->Session->check('updatedEvent'))
//	  {
//	    $event = $this->Session->read('updatedEvent');
//	    $teamId = $event['Event']['team_id'];
//	    try
//	    {
//	      $this->GoogleCal->updateEvent($token, $event);
//	    }
//	    catch (Exception $e)
//	    {
//        $this->error('Events::create_calendar_events', 'Could not update calendar event: ' . $event['Event']['cal_event_id'] . ': ' . $e->getMessage());
//        $this->Session->setFlash('There was an error creating calendar events');
//	    }
//	  }
//	  if ($this->Session->check('eventsToCreate'))
//	  {
//	    $eventsToCreate = $this->Session->read('eventsToCreate');
//	    $teamId = $eventsToCreate['team_id'];
//	    $calId = $eventsToCreate['calId'];
//	    $events = $eventsToCreate['events'];
//	    try
//	    {
//	      $createdEvents = $this->GoogleCal->createEvents($token, $calId, $events);
//	      // save google calendar ids
//	      foreach ($createdEvents as $eventId => $calEvent)
//	      {
//	        $calEventId = $calEvent->getId();
//	        if (!$this->Event->save(array('Event' => array('id' => $eventId, 'cal_event_id' => $calEventId))))
//	        {
//	          $this->error('Events::create_calendar_events', 'Could not save google calendar event id');
//	        }
//	      }
//	    }
//	    catch (Exception $e)
//	    {
//	      $this->error('Events::create_calendar_events', 'Could not create calendar events for team: ' . $teamId . ': ' . $e->getMessage());
//	      $this->Session->setFlash('There was an error creating calendar events');
//	    }
//	  }
//	  $this->Session->delete('updatedEvent');
//    $this->Session->delete('eventsToCreate');
//    $this->redirect(array('controller' => 'teams', 'action' => 'calendar', $teamId));
//	}
	
	function delete($eventId = null)
	{
	  $this->Event->Expects('Team');
	  $event = $this->verifyManager($this->Event, $eventId);
	  if ($this->Event->delete($eventId))
	  {
	    $this->Session->setFlash('Event ' . $event['Event']['name'] . ' was deleted from ' . $event['Team']['name']);
	    // delete google event if it exists
	    if (isset($event['Event']['cal_event_id']) && $event['Event']['cal_event_id'])
	    {
//	      $this->Session->write('deleteCalendarEvent', $event['Event']['cal_event_id']);
//        $authLink = $this->GoogleCal->getAuthLink('events/delete_calendar_event/');
//	      $this->redirect($authLink);
        $this->GoogleCal->deleteEvent($event['Event']['cal_event_id']);
	    }
//	    else
	    {
	      $this->data = $event;
	      parent::delete('Event ' . $event['Event']['name'] . ' was deleted from ' . $event['Team']['name']);
	      return;
	    }
	  }
	  else
	  {
	    $this->error('Events::delete', 'Could not delete event: ' . $eventId . ': ' . $event['Event']['name'], $this->getUserID());
      $this->Session->setFlash('There was an error trying to delete event ' . $event['Event']['name']);
	  }
	  $this->redirect(array('controller' => 'teams', 'action' => 'index'));
	}
	
	function delete_calendar_event()
	{
	  if ($this->Session->check('deleteCalendarEvent'))
	  {
	    $calEventId = $this->Session->read('deleteCalendarEvent');
      if (!isset($this->params['url']['token']))
      {
        $this->invalidPage('/', 'teams/delete_calendar_event/');
      }
      $token = $this->params['url']['token'];
	    try
	    {
	     $this->GoogleCal->deleteEvent($token, $calEventId);
	    }
	    catch (Exception $e)
	    {
	      $this->error('Events::delete_calendar_event', 'Could not delete calendar event: ' . $calEventId . ': ' . $e->getMessage());
	      $this->Session->setFlash('Could not delete calendar event');
	    }
	  }
	  
	  $this->Session->delete('deleteCalendarEvent');
    $this->redirect(array('controller' => 'teams', 'action' => 'index'));
	}

  function actions($eventId = null)
  {
    $this->Event->expects();
		$actions = array();
		if ($eventId == null)
		{
		  $actions = array('Upcoming', 'AddEvent', 'Past', 'class' => 'actions small', 'default' => 'Upcoming');
		}
		else
		{
			$actions = array('Info', 'RSVP', 'RSVPS', 'class' => 'small actions eventActions', 'default' => 'Info');
			$event = $this->verifyMember($this->Event, $eventId);
			if ($this->isTeamManager($this->Event->Team, $event['Event']['team_id']))
			{
				$actions = array_merge($actions, array('Emails'));
			}
		}
    if (isset($this->params['requested']))
    {
      return $actions;
    }
    $this->invalidPage(array('controller' => 'events', 'action' => 'index'), '/events/actions/' . $eventId);
  }

  function emails_actions($eventId = null)
  {
    $actions = array('List', 'class' => 'actions small', 'default' => 'List');
    if ($eventId)
    {
      $event = $this->isManager($this->Event, $eventId);
      if ($event !== false)
      {
       array_push($actions, 'AddEmail');
       // only add default emails link if some exist
       $query = 'SELECT COUNT(*) AS COUNT FROM emails WHERE team_id=' . $event['Event']['team_id'];
       $result = $this->Event->Email->query($query);
       $count = $result[0][0]['COUNT'];
       if (intval($count) > 0)
       {
         array_push($actions, 'AddDefaultEmails');
       }
      }
    }
    
    if (isset($this->params['requested']))
    {
      return $actions;
    }

    $this->invalidPage(array('controller' => 'events', 'action' => 'index'), '/events/emails_actions/' . $eventId);
  }
  
  function emails($eventId = null)
  {
    $this->Event->expects();
    $event = $this->verifyManager($this->Event, $eventId);
    
    $this->Event->Email->recursive = 2;
    $this->paginate['conditions'] = array('Email.event_id' => $eventId);
    $this->paginate['limit'] = 1;
    //$this->Event->Email->hasMany = array();
    //$this->Event->Email->belongsTo = array();
    
    //$emails = $this->paginate('Email');
    $emails['Email'] = $this->paginate('Email');
    $emails['Event'] = $event['Event'];
    
    $this->set(compact('emails'));
  }
  
  function info($eventId = null)
  {
    if (!$this->isAjax() && !isset($this->params['requested']))
    {
      $this->redirect(array('controller' => 'events', 'action' => 'view', $eventId));
    }
    $this->Event->expects('ResponseType', 'Team');
    $event = $this->verifyMember($this->Event, $eventId);
    if (!isset($event['ResponseType']))
    {
      $responseType = $this->Event->ResponseType->findById($event['Event']['response_type_id']);
      $event['ResponseType'] = $responseType['ResponseType'];
    }
    if (isset($this->params['requested']))
    {
      return $event;
    }
    $default = $event['ResponseType']['name'];
    $responseTypes = $this->Event->Response->ResponseType->findAll();
    $responseTypes = array_values($responseTypes);
    
    $response = array();
    
    $rsvp = $default;
    $query = 'SELECT ResponseType.name, Response.created, Response.comment FROM response_types AS ResponseType LEFT JOIN responses AS Response ON Response.response_type_id=ResponseType.id';
    $query .= ' LEFT JOIN players AS Player ON Player.id=Response.player_id';
    $query .= ' WHERE Response.event_id=' . $eventId . ' AND Player.user_id=' . $this->getUserID() . ' ORDER BY Response.id DESC LIMIT 1';
    $result = $this->Event->query($query);
    if (!empty($result))
    {
      //$rsvp = $result[0]['ResponseType']['name'];
      $response = $result[0];
    }
    
    $this->set(compact('event', 'responseTypes', 'default', 'response'));
  }

  function responses($eventId = null)
  {
    $this->Event->recursive = 0;
    $this->Event->expects();
    $event = $this->verifyMember($this->Event, $eventId);

    $this->paginate['conditions'] = array('Player.team_id' => $event['Event']['team_id']);
    $this->paginate['order'] = 'Response.id DESC';
    $this->paginate['limit'] = 20;
    $this->Event->Team->expects('Player');
    $players = $this->paginate('Event.Team.Player');
    
    $event['Player'] = $players;
    
    if (isset($this->params['requested']))
    {
      return $event;
    }
    $this->set(compact('event'));
  }
  
  function RSVPS($eventId = null)
  {
    if (isset($this->params['requested']))
    {
      return $this->_getRSVPS($this->Event, $eventId);
    }
    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/events/RSVPS/' . $eventId);
  }
  
  function drop_down($eventId = null)
  {
    $event = $this->verifyMember($this->Event, $eventId);
    $this->set(compact('event'));
  }
  
//  function RSVPS($eventId = null)
//  {
//    $this->Event->recursive = 0;
//    $this->Event->expects('ResponseType');
//    if (!$this->isCronJob())
//    {
//      $event = $this->verifyMember($this->Event, $eventId);
//    }
//    else
//    {
//      $event = $this->Event->findById($eventId);
//    }
//    
//    $query = 'SELECT ResponseType.name, Player.id FROM response_types AS ResponseType';
//    $query .= ' JOIN responses AS Response ON ResponseType.id=Response.response_type_id';
//    $query .= ' JOIN players AS Player ON Response.player_id=Player.id JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
//    $query .= ' INNER JOIN (SELECT MAX(id) AS id FROM responses WHERE event_id=' . $eventId  . ' GROUP BY player_id) Responses';
//    $query .= ' ON Response.id=Responses.id';
//    $query .= ' WHERE PlayerType.name!="member"';
//    $results = $this->Event->query($query);
//    $rsvps = array();
//    $playerIds = array();
//    if (!empty($results))
//    {
//      $rsvps = Set::extract($results, '{n}.ResponseType.name');
//      $playerIds = Set::extract($results, '{n}.Player.id');
//    }
//    $responses = array_count_values($rsvps);
//    // get # of people who haven't responded, don't include members or subs
//    $playerTypesNotInDefault = array('"member"', '"sub"');
//    $conditions = ' WHERE Event.id=' . $eventId;
//    if (!empty($playerIds))
//    {
//      $conditions .= ' AND Player.id NOT IN (' . implode(', ', $playerIds) . ')';
//    }
//    $conditions .= ' AND PlayerType.name NOT IN (' . implode(', ', $playerTypesNotInDefault) . ')';
//    
//    // get default responses
//    $default = $event['ResponseType']['name'];
//    $query = 'SELECT COUNT(*) AS COUNT FROM players AS Player LEFT JOIN teams AS Team ON Team.id=Player.team_id';
//    $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
//    $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id' . $conditions;
//    $result = $this->Event->query($query);
//    $responses[$default] += $result[0][0]['COUNT'];
//    
//    
//    $conditions = 'TRUE';
//    if ($default != 'no_response')
//    {
//      $conditions = 'ResponseType.name!="no_response"';
//    }
//    $responseTypes = $this->Event->Response->ResponseType->findAll($conditions);
//    $responseTypes = array_values($responseTypes);
//    $data['responses'] = $responses;
//    $data['default'] = $default;
//    $data['responseTypes'] = $responseTypes;    
//    if (isset($this->params['requested']))
//    {
//      return $data;
//    }
//    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/events/RSVPS/' . $eventId);
//  }
  
//  function RSVPS_old($eventId = null)
//  {
//    $this->Event->recursive = 0;
//    $this->Event->expects('ResponseType');
//    if (!$this->isCronJob())
//    {
//      $event = $this->verifyMember($this->Event, $eventId);
//    }
//    else
//    {
//      $event = $this->Event->findById($eventId);
//    }
//    
//    $this->Event->Response->Player->PlayerType->expects();
//    $playerTypes = $this->Event->Response->Player->PlayerType->findAll('PlayerType.name != "regular"');
//    $playerTypes = Set::combine($playerTypes, '{n}.PlayerType.name', '{n}.PlayerType.id');
//    $playerTypeIds = array_values($playerTypes);
//
//    if (!isset($event['ResponseType']))
//    {
//      $responseType = $this->Event->ResponseType->findById($event['Event']['response_type_id']);
//      $event['ResponseType'] = $responseType['ResponseType'];
//    }
//    //$responseTypes = $this->Event->Response->ResponseType->find('list', array('conditions' => 'ResponseType.name != "no_response"'));
//    $responseTypes = $this->Event->Response->ResponseType->find('list');
//    $this->set(array('responseTypes' => array_values($responseTypes)));
//    $playerIds = array();
//    foreach ($responseTypes as $responseType)
//    {
//      $responses[$responseType] = 0;
//      $query = 'SELECT Player.id FROM players AS Player LEFT JOIN teams AS Team ON Team.id=Player.team_id LEFT JOIN';
//      $query .= ' responses AS Response ON Response.player_id=Player.id LEFT JOIN response_types AS ResponseType ON ResponseType.id=Response.response_type_id';
//      $query .= ' LEFT JOIN events AS Event ON Event.id=Response.event_id';
//      $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
//      $query .= ' WHERE Event.id=' . $eventId . ' AND ResponseType.name="' . $responseType . '"';
//      //$query .= ' AND Player.player_type_id NOT IN (' . implode(', ', $playerTypeIds) . ')';
//      $query .= ' AND PlayerType.name != "member"';
//      
//      if (!empty($playerIds))
//      {
//        $query .= ' AND Player.id NOT IN (' . implode(', ', $playerIds) .')';
//      }
//      $query .= ' GROUP BY Player.id ORDER BY Response.id';
//      $result = $this->Event->query($query);
//      //debug($responseType . ": " . sizeof($result));
//      $playerResponses = Set::extract($result, '{n}.Player.id');
//      $playerIds = array_merge($playerIds, $playerResponses);
//      $responses[$responseType] = sizeof($playerResponses);
//    }
//    
//    $conditions = ' WHERE Event.id=' . $eventId;
//    $playerIds = $this->Event->Response->findAll(array('Response.event_id' => $eventId), array('Response.player_id'));
//    if (!empty($playerIds))
//    {
//      $playerIds = Set::extract($playerIds, '{n}.Response.player_id');
//      if (!empty($playerIds))
//      {
//        $conditions .= ' AND Player.id NOT IN (' . implode(', ', $playerIds) . ')';
//      }
//    }
//    $conditions .= ' AND Player.player_type_id NOT IN (' . implode(', ', $playerTypeIds) . ')';
//    //$conditions .= ' AND PlayerType.name != "member"';
//    
//    // get default responses
//    $query = 'SELECT COUNT(*) AS COUNT FROM players AS Player LEFT JOIN teams AS Team ON Team.id=Player.team_id';
//    $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
//    $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id' . $conditions;
//    $result = $this->Event->query($query);
//    $responses[$event['ResponseType']['name']] += $result[0][0]['COUNT'];
//
//    $conditions = 'TRUE';
//    if ($default['ResponseType']['name'] != 'no_response')
//    {
//      $conditions = 'ResponseType.name!="no_response"';
//    }
//    $default = $event['ResponseType']['name'];
//    $responseTypes = $this->Event->Response->ResponseType->findAll($conditions);
//    $responseTypes = array_values($responseTypes);
//    $data['responses'] = $responses;
//    $data['default'] = $default;
//    $data['responseTypes'] = $responseTypes;    
//    if (isset($this->params['requested']))
//    {
//      return $data;
//    }
//
//    $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/events/RSVPS/' . $eventId);
//  }

  function rsvp($eventId = null)
  {
    $this->redirect(array('controller' => 'responses', 'action' => 'add', $eventId));
  }
  
  function rsvp_status($eventId = null)
  {
    if (!isset($this->params['requested']))
    {
      $this->invalidPage(array('controller' => 'teams', 'action' => 'index'), '/events/rsvp_status/' . $eventId);
    }
    $event = $this->verifyMember($this->Event, $eventId);
    $userId = $this->getUserID();
//    $query = 'SELECT ResponseType.name, Response.created, Response.comment';
//    $query .= ' FROM responses AS Response LEFT JOIN response_types AS ResponseType ON ResponseType.id=Response.response_type_id';
//    $query .= ' LEFT JOIN events AS Event ON Event.id=Response.event_id LEFT JOIN teams AS Team ON Team.id=Event.team_id';
//    $query .= ' LEFT JOIN players AS Player ON Player.team_id=Team.id';
//    $query .= ' WHERE Player.user_id=' . $userId . ' AND Event.id=' . $eventId . ' ORDER BY Response.created DESC LIMIT 1';
    $eventId = $event['Event']['id'];
    $query = 'SELECT ResponseType.name, Response.created, Response.comment FROM response_types AS ResponseType LEFT JOIN responses AS Response ON Response.response_type_id=ResponseType.id';
    $query .= ' LEFT JOIN players AS Player ON Player.id=Response.player_id';
    $query .= ' WHERE Response.event_id=' . $eventId . ' AND Player.user_id=' . $this->getUserID() . ' ORDER BY Response.id DESC LIMIT 1';
    $results = $this->Event->Response->query($query);
    if (!empty($results))
    {
      $results = $results[0];
    }
    if (isset($this->params['requested']))
    {
      return $results;
    }
  }
  
  function add_default_emails($eventId = null)
  {
    $this->Event->expects();
    $event = $this->verifyManager($this->Event, $eventId);
    
    // first delete all the current emails for the event
    $this->Event->Email->deleteAll(array('Email.event_id' => $eventId));
    
    $this->Event->Team->expects('DefaultEmail');
    $this->Event->Team->DefaultEmail->expects('PlayerTypes', 'ResponseTypes');
    $this->Event->Team->recursive = 2;
    $team = $this->Event->Team->findById($event['Event']['team_id']);
    foreach ($team['DefaultEmail'] as $defaultEmail)
    {
      $default['PlayerTypes'] = $defaultEmail['PlayerTypes'];
      $default['ResponseTypes'] = $defaultEmail['ResponseTypes'];
      unset($defaultEmail['PlayerTypes']);
      unset($defaultEmail['ResponseTypes']);
      $default['DefaultEmail'] = $defaultEmail;
      if (!$this->Event->Email->fromDefault($default, $eventId, 'DefaultEmail'))
      {
        $this->error('Events::add_default_emails', 'Could not create email from default: ' . $defaultEmail['DefaultEmail']['id']);
      }
    }
    $this->set(compact('team'));
  }
  
//	function admin_index() {
//		$this->Event->recursive = 0;
//		$this->set('events', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Event.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('event', $this->Event->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Event->create();
//			if ($this->Event->save($this->data)) {
//				$this->Session->setFlash(__('The Event has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Event could not be saved. Please, try again.', true));
//			}
//		}
//		$teams = $this->Event->Team->find('list');
//		$this->set(compact('teams'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Event', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Event->save($this->data)) {
//				$this->Session->setFlash(__('The Event has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Event could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Event->read(null, $id);
//		}
//		$teams = $this->Event->Team->find('list');
//		$this->set(compact('teams'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Event', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Event->del($id)) {
//			$this->Session->setFlash(__('Event deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}
//
	
	function update()
	{
    $this->verifyAdminAuthentication();
	  $responseTypes = $this->Event->ResponseType->find('all');
	  $responseTypes = Set::combine($responseTypes, '{n}.ResponseType.name', '{n}.ResponseType.id');
	  $this->Event->expects();
	  $events = $this->Event->findAll();
	  foreach ($events as $event)
	  {
	    //if ($event['Event']['default_response'] != 'no_response')
	    {
	      $event['Event']['response_type_id'] = $responseTypes[$event['Event']['default_response']];
	      //debug($event);
	      if (!$this->Event->save($event))
	      {
	        debug('error');
	      }
	    }
	  }
	  debug('done');
	  exit();
	}
}
?>
