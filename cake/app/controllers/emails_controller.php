<?php
class EmailsController extends AppController {

	var $name = 'Emails';
  var $helpers = array('Html', 'MyHtml', 'Form', 'Ajax', 'MyAjax', 'Javascript', 'Time');
  var $components = array('AjaxValid', 'Mailer', 'MyHtmlMailer');
  var $uses = array('Email', 'Event');
	
	function index($eventId = null)
	{
    $event = $this->verifyMember($this->Email->Event, $eventId);
    
    $this->paginate['conditions'] = array('Email.event_id' => $eventId);
    //$this->paginate['order'] = 'start ASC';
    $this->paginate['limit'] = 1;
//    $this->Email->hasMany = array();
//    $this->Email->belongsTo = array();
    
    $emails['Email'] = $this->paginate('Email');
    $emails['Event'] = $event['Event'];
    
    $this->set(compact('emails'));
	}

	function view($emailId = null)
	{
	  //$this->Event->expects('Event');
	  $this->Email->expects('Event', 'PlayerTypes', 'ResponseTypes', 'Condition', 'Team');
	  $email = $this->verifyManager($this->Email, $emailId);
	  $this->set(compact('email'));
	}

	function add($eventId = null)
	{
	  if ($eventId)
	  {
	    $this->data['Email']['event_id'] = $eventId;
	  }
	  $condition = 'FALSE';
//	  $teams = $this->Email->Event->Team->findTeamsUserManages($this->getUserID());
//	  if (!empty($teams))
	  {
	    //$teamIds = Set::extract($teams, '{n}.Team.id');
	    $teamIds = $this->getTeamIdsUserManages($this->Email->Event->Team);
	    if (!empty($teamIds))
	    {
	      $condition = 'Event.team_id IN (' . implode(', ', $teamIds) . ')';
	    }
	  }
	  $events = $this->Email->Event->find('list', array('conditions' => $condition));
    $associations[] = array('field' => 'event');
    $this->data['Email']['rsvp'] = 1;
    $this->set(compact('events'));
	  $this->_setFormData();
	}

	function _setFormData()
	{
	  $player_types = $this->Email->PlayerTypes->find('list');
	  
    $this->Email->Event->expects();
    $event = $this->Email->Event->findById($this->data['Email']['event_id']);
	  
    // filter out no_response if event's default is different than no_response
    $response_types = $this->Email->ResponseTypes->find('list');
    $no_response_key = array_search('no_response', $response_types);
    if ($no_response_key === false)
    {
      $no_response_key = array_search(Inflector::humanize('no_response'), $response_types);
    }
    if (($no_response_key !== false) && ($event['Event']['response_type_id'] != $no_response_key))
    {
      // remove no_response_key
      unset($response_types[$no_response_key]);
    }
    
    $response_types[$event['Event']['response_type_id']] .= ' (event\'s default rsvp)';
    $this->data['ResponseTypes']['ResponseTypes'] = $event['Event']['response_type_id'];
    
    // get # of days_before
    $eventDate = strtotime($event['Event']['start']);
    $days_before = array();
    $i = 0;
    while (strtotime(date('M j, Y')) < strtotime('-1 days', $eventDate))
    {
      $days_before[$i] = $i . ': ' . date('M j', $eventDate);
      $i++;
      $eventDate = strtotime('-1 days', $eventDate);
    }
    $this->set(compact('events', 'response_types', 'player_types', 'days_before'));
    // For some reason days_before isnt' getting set in compact
    $this->set('days_before', $days_before);
    return parent::_setFormData();
	}
	
	function edit($emailId = null)
	{
	  $this->Email->expects('PlayerTypes', 'ResponseTypes');
	  $this->data = $this->verifyManager($this->Email, $emailId);
  	$this->_setFormData();
	}

	function delete($emailId = null)
	{
	  $this->Email->expects('Event');
	  $email = $this->verifyManager($this->Email, $emailId);
	  if ($this->Email->delete($emailId))
	  {
	    $this->Session->setFlash('Email ' . $email['Email']['title'] . ' was deleted from event ' . $email['Event']['name']);
	    $this->data = $email;
	    parent::delete('Email ' . $email['Email']['title'] . ' was deleted from event ' . $email['Event']['name']);
	    return;
	  }
	  else
	  {
	    $this->error('Email::delete', 'Could not delete email: ' . $emailId . ': ' . $email['Email']['title'], $this->getUserID());
	    $this->Session->setFlash('There was an error while trying to delete email ' . $email['Email']['title']);
	  }
	  $this->redirect(array('controller' => 'teams', 'action' => 'index'));
	}

	function actions($emailId = null)
  {
    $actions = array();
    if ($emailId == null)
    {
      $actions = array('List', 'class' => 'actions small', 'default' => 'List');
    }
    else
    {
      $this->Email->expects();
      //$email = $this->Email->find(array('Email.id' => $emailId), array('Email.event_id'));
      $email = $this->verifyManager($this->Email, $emailId);
      $actions = array('Info', 'Send', /*'AddCondition',*/ 'class' => 'small actions emailActions', 'default' => 'Info');
      if ($email['Email']['send'] == 'days_before')
      {
        array_push($actions, 'MakeDefault');
      }
    }
    if (isset($this->params['requested']))
    {
      return $actions;
    }
    $this->invalidPage(array('controller' => 'events', 'action' => 'index'), '/emails/actions/' . $emailId);
  }
  
  function make_default($emailId = null)
  {
    $this->Email->expects('Event');
    $this->Email->Event->expects('Team');
    $email = $this->verifyManager($this->Email, $emailId);
    if (!isset($email['Event']['Team']))
    {
      $this->Email->Event->Team->expects();
      $team = $this->Email->Event->Team->findById($email['Event']['team_id']);
    }
    else
    {
      $team = $email['Event'];
    }
    $success = true;
    if (!empty($email) && ($email['Email']['send'] == 'days_before'))
    {
      if ($this->Email->toDefault($email, $team['Team']['id']) === false)
      {
        $this->error('Emails::__toggle_default', 'Could not toggle default email status: ' . $emailId . ': for team: ' . $team['Team']['id']);
        $success = false;
      }
    }
    else
    {
      $success = false;
      $message = 'The email must be specified to send a # of days before the event to be made a default email';
    }
    
    $this->Email->Event->Team->expects('DefaultEmail');
    $this->Email->Event->Team->DefaultEmail->expects('PlayerTypes', 'ResponseTypes');
    $this->Email->Event->Team->recursive = 2;
    $team = $this->Email->Event->Team->findById($team['Team']['id']);
    
//    $query = 'SELECT DefaultEmail.* FROM emails AS DefaultEmail LEFT JOIN events AS Event ON Event.id=DefaultEmail.event_id';
//    $query .= ' LEFT JOIN teams AS Team ON Team.id=Event.team_id';
//    //$query .= ' WHERE Team.id=' . $email['Event']['team_id'] . ' AND DefaultEmail.default=1 ORDER BY days_before DESC';
//    $query .= ' WHERE DefaultEmail.team_id=' . $team['Team']['id'] . ' ORDER BY days_before DESC';
//    $defaultEmails = $this->Email->query($query);
    $this->set(compact('success', 'email', 'team'));//, 'defaultEmails'));
  }
	
  function info($emailId = null)
  {
    if (!$this->isAjax() && !isset($this->params['requested']))
    {
      $this->redirect(array('controller' => 'emails', 'action' => 'view', $emailId));
    }
    
    $this->Email->recursive = 2;
    $this->Email->expects('Event', 'PlayerTypes', 'ResponseTypes', 'Condition', 'Team');
    $this->Email->Event->expects();
    $this->Email->Condition->expects('PlayerTypes', 'ResponseTypes', 'ConditionType');
    $email = $this->verifyManager($this->Email, $emailId);
    if (isset($this->params['requested']))
    {
      return $email;
    }
    $this->set(compact('email'));
  }
  
  function validator()
  {
    $this->cleanUpFields();
    parent::validator();
    if ($this->AjaxValid->valid)
    {
      if ($this->data['Email']['send'] == 'now')
      {
        /// redirect to send page
        $emailId = $this->Email->getLastInsertID();
        if (isset($this->data['Email']['id']))
        {
          $emailId = $this->data['Email']['id'];
        }
        
        //$this->redirect(array('controller' => 'emails', 'action' => 'send', $emailId));
        $redirStr = "<script type='text/javascript'>";
        $redirStr .= "document.location = '/emails/send/" . $emailId . "';\n";
        $redirStr .= "</script>";
        $this->set('data', $redirStr);
        $this->set('showTab', false);
      }
    }
  }
  
  function cleanUpFields($modelClass = 'Email')
  {
    if (isset($this->data['Email']['send']))
    {
      $send = $this->data['Email']['send'];
      if ($send == 'now')
      {
        $this->data['Email']['days_before'] = NULL;
        $this->data['Email']['send_on'] = NULL;
      }
      else if ($send == 'days_before')
      {
        $this->data['Email']['send_on'] = NULL;
      }
      else if ($send == 'send_on')
      {
        $this->data['Email']['days_before'] = NULL;
      }
    }
    if (isset($this->data['Email']['condition_count']))
    {
      if (strlen($this->data['Email']['condition_count']) <= 0)
      {
        $this->data['Email']['condition_count'] = NULL;
      }
    }
    
    return parent::cleanUpFields($modelClass);
  }
  
  function really_send($emailId = null, $cronJob = false)
  {
    $this->Email->expects();
    if (!$this->isCronJob())
    {
      $this->verifyManager($this->Email, $emailId);
    }
    
    $this->set(compact('email'));
    
    $data = $this->send($emailId);
    $event = $data['email'];
    $users = $data['users'];
    $creator = $data['creator'];
    $managers = $data['managers'];
    
    $from = $creator['Creator']['email'];
    $fromName = $event['Team']['name'];
    
    $goodUsers = array();
    $badUsers = array();

    // Set up mail
    foreach ($users as $type => &$players)
    {
      foreach ($players as $playerId => $user)
      {
        $this->Mailer->init($from, $fromName);
        $this->Mailer->Subject = $event['Event']['name'] . ': ' . date('l, F j', strtotime($event['Event']['start']));
        if ($event['Email']['title'])
        {
          $this->Mailer->Subject .= ': ' . $event['Email']['title'];
        }
        debug('Sending email to ' . $user['name'] . ' (' . implode(', ', $user['emails']) . ')');
        debug($user);
//        foreach ($user['emails'] as $email)
//        {
//          $this->Mailer->AddAddress($email);
//        }
        $this->Mailer->to = array($user['emails']);
        debug($this->Mailer->to);
        
        $isIBMEmail = false;
        foreach ($user['emails'] as $userEmail)
        {
          if (strchr($userEmail, 'ibm.com'))
          {
            $isIBMEmail = true;
            break;
          }
        }
        
        if ($type == 'member')
        {
          $event['Email']['rsvp'] = false;
        }
        $onlyIBMEmail = $isIBMEmail && (sizeof($user['emails']) == 1);
        
        $conditions = array('Response.event_id' => $event['Event']['id']);
        array_push($conditions, array('Response.player_id' => $playerId));
        $response = $this->Email->Event->Response->find($conditions, null, 'Response.id DESC');

        $responseTypeId = $event['Event']['response_type_id'];
        if (!empty($response))
        {
          $responseTypeId = $response['Response']['response_type_id'];
        }
        //$conditions = array('ResponseTypes.id != ' . $responseTypeId, 'ResponseTypes.name != "no_response"');
        $conditions = array('ResponseTypes.name != "no_response"');
        $response_types = $this->Email->ResponseTypes->find('list', compact('conditions'));
        //$response_types = $this->Email->ResponseTypes->find('list');
        debug('ResponseTypes:');
        debug($response_types);
        
        $responseKey = $this->generateResponseKey($event['Event']['id'], $playerId);
        $player = array('Player' => array('id' => $playerId, 'response_key' => $responseKey));
        $this->data['Response']['player_id'] = $playerId;
        $this->data['Response']['event_id'] = $event['Event']['id'];
        
        $query = 'SELECT User.*, TimeZone.* FROM players AS Player LEFT JOIN users AS User ON User.id=Player.user_id';
        $query .= ' LEFT JOIN time_zones AS TimeZone ON TimeZone.id=User.time_zone_id';
        $query .= ' WHERE Player.id=' . $playerId;
        $result = $this->Email->Event->Response->Player->User->query($query);
        $u = $this->Email->Event->Response->Player->User->afterFind($result);
        if (!empty($u))
        {
          $u = $u[0];
        }
        debug($u);
        date_default_timezone_set($u['TimeZone']['value']);
        
        // Set mail body
        debug('checking if cron_job');
        if (!$this->isCronJob())
        {
          debug('not cron job');
          ob_start();
          $this->set('email', $event);
          $this->set(compact('isIBMEmail', 'onlyIBMEmail'));
          $this->set('response_types', $response_types);
          $this->set('player', $player);
          $this->set('user', $u);
          $this->set('emails', $user['emails']);
          $this->set('response', $response);
          $this->autoRender = false;
          $this->render('event_email', 'email/event');
          $this->autoRender = 'auto';
          $this->Mailer->Body = ob_get_clean();
        }
        else
        {
          debug('cron job');
          debug('isIBMEmail: ' . $isIBMEmail);
          debug('onlyIBMEmail: ' . $onlyIBMEmail);
          //$pieChartData = $this->requestAction('events/RSVPS/' . $event['Event']['id']);
          $pieChartData = $this->__events_rsvps($event['Event']['id']);
          debug($pieChartData);
          debug('Creating HTML data');
          debug($player);
          $this->Mailer->Body = $this->MyHtmlMailer->getHtmlBody($u, $user['emails'], $event, $response_types, $player, $isIBMEmail, $onlyIBMEmail, $response, $pieChartData);
        }
        $this->Mailer->IsHtml(true); 
        if ($this->isAdminOn())
        {
          debug($this->Mailer->Body);
          exit();
        }
        // Send mail
        debug('Sending email...');
        debug($this->Mailer->to);
        if (sizeof($user['emails']) == sizeof($this->Mailer->to[0]))
        {
	  $result = $this->Mailer->send();
	  print_r($result);
          if ($result)
          {
            debug('Email Sent');
            //$players[$playerId]['sent'] = true;
            $goodUsers[$user['name']] = $user['emails'];
          }
          else
          {
            debug('Email failed');
            //$players[$playerId]['sent'] = $this->Mailer->ErrorInfo;
            $badUsers[$user['name']] = $user['emails'];
          }
        }
        else
        {
          $this->error('Emails::really_send', 'Size of emails does not match');
        }
      }
    }
    if (!empty($goodUsers))
    {
      // save email sent time
      $this->Email->id = $event['Email']['id'];
      if (!$this->Email->saveField('sent', date('Y-m-d H:i:s'), false))
      {
        $this->error('Emails::really_send', 'Could not save email sent time', $this->getUserID());
      }
    }
//    debug($goodUsers);
//    debug($badUsers);
    if ($this->isCronJob() || $cronJob)
    {
      return compact('goodUsers', 'badUsers');
    }
    $this->set(compact('users', 'goodUsers', 'badUsers'));
    $this->render();
  }
  
  function event_email($emailId = null)
  {
    $this->layout = 'email/event';
    $data = $this->send($emailId);
    $email = $data['email'];
    $users = $data['users'];
    $creator = $data['creator'];
    $managers = $data['managers'];
    $this->set(compact('email'));

    foreach ($users as $type => &$players)
    {
      foreach ($players as $playerId => $user)
      {
        $isIBMEmail = false;
        foreach ($user['emails'] as $userEmail)
        {
          if (strchr($userEmail, 'ibm.com'))
          {
            $isIBMEmail = true;
            break;
          }
        }
        $onlyIBMEmail = $isIBMEmail && (sizeof($user['emails']) == 1);
        $this->set(compact('isIBMEmail', 'onlyIBMEmail'));
        
        $conditions = array('Response.event_id' => $email['Event']['id']);
        array_push($conditions, array('Response.player_id' => $playerId));
        $response = $this->Email->Event->Response->find($conditions, null, 'Response.id DESC');

        $responseTypeId = $email['Event']['response_type_id'];
        if (!empty($response))
        {
          $responseTypeId = $response['Response']['response_type_id'];
        }
        $conditions = array('ResponseTypes.id != ' . $responseTypeId, 'ResponseTypes.name != "no_response"');
        $response_types = $this->Email->ResponseTypes->find('list', compact('conditions'));
        
        $responseKey = $this->generateResponseKey($email['Event']['id'], $playerId);
        $player = array('Player' => array('id' => $playerId, 'response_key' => $responseKey));
        $this->data['Response']['player_id'] = $playerId;
        $this->data['Response']['event_id'] = $email['Event']['id'];
        $this->set('response_types', $response_types);
        $this->set('player', $player);
        
        $query = 'SELECT User.* FROM users AS User LEFT JOIN players AS Player ON Player.user_id=User.id WHERE Player.id=' . $playerId;
        $result = $this->Email->Event->Response->Player->User->query($query);
        $u = $this->Email->Event->Response->Player->User->afterFind($result);
        if (!empty($u))
        {
          $u = $u[0];
        }
        $this->set('user', $u);
        $this->set('emails', $user['emails']);
        debug($user);
        return;
      }
    }
  }
  
  function send($emailId = null)
  {
    if (!$this->isCronJob())
    {
      $this->Email->expects('Event', 'PlayerTypes', 'ResponseTypes');
      $email = $this->verifyManager($this->Email, $emailId);
    }
    else
    {
      $email = $this->Email->findById($emailId);
      $event = $this->Event->findById($email['Email']['event_id']);
      $email['Event'] = $event['Event'];

//      $query = "SELECT PlayerTypes.* FROM `player_types` AS `PlayerTypes` LEFT JOIN `email_player_types` AS `EmailPlayerType`";
//      $query .= " ON (`EmailPlayerType`.`email_id` IN (" . $email['Email']['id'] . ") AND `EmailPlayerType`.`player_type_id` = `PlayerTypes`.`id`) ORDER BY player_type_id ASC";
      $query = 'SELECT PlayerTypes.* FROM player_types AS PlayerTypes LEFT JOIN email_player_types AS EmailPlayerType ON EmailPlayerType.player_type_id=PlayerTypes.id';
      $query .= ' WHERE EmailPlayerType.email_id=' . $email['Email']['id'] . ' ORDER BY PlayerTypes.id ASC';
      $playerTypes = $this->Email->query($query);
      $email['PlayerTypes'] = Set::extract($playerTypes, '{n}.PlayerTypes');
      
//      $query = "SELECT ResponseTypes.* FROM `response_types` AS `ResponseTypes` JOIN `email_response_types` AS `EmailResponseType`";
//      $query .= " ON (`EmailResponseType`.`email_id` IN (" . $email['Email']['id'] . ") AND `EmailResponseType`.`response_type_id` = `ResponseTypes`.`id`)";
      $query = 'SELECT ResponseTypes.* FROM response_types AS ResponseTypes LEFT JOIN email_response_types AS EmailResponseType ON EmailResponseType.response_type_id=ResponseTypes.id';
      $query .= ' WHERE EmailResponseType.email_id=' . $email['Email']['id'];
      $responseTypes = $this->Email->query($query);
      $email['ResponseTypes'] = Set::extract($responseTypes, '{n}.ResponseTypes');
    }
    $this->Email->Event->Team->expects();
    $team = $this->Email->Event->Team->findById($email['Event']['team_id']);
    $email['Team'] = $team['Team'];
    $responseType = $this->Email->Event->ResponseType->find(array('ResponseType.id' => $email['Event']['response_type_id']), array('ResponseType.name'));
    $email['ResponseType'] = $responseType['ResponseType'];
    $responseTypes = Set::extract($email['ResponseTypes'], '{n}.id');

    $query = 'SELECT Creator.email, Creator.first_name, Creator.last_name FROM teams AS Team LEFT JOIN users AS Creator ON Creator.id=Team.user_id';
    $query .= ' WHERE Team.id=' . $email['Event']['team_id'] . ' LIMIT 1';
    $results = $this->Email->Event->Team->Creator->query($query);
    $creator = $this->Email->Event->Team->Creator->afterFind($results);
    $creator = $creator[0];
    
    $query = 'SELECT Managers.email, Managers.first_name, Managers.last_name FROM teams AS Team LEFT JOIN teams_users AS TeamsUser ON';
    $query .= ' (TeamsUser.team_id=Team.id AND TeamsUser.user_id!=Team.user_id)';
    $query .= ' LEFT JOIN users AS Managers ON Managers.id=TeamsUser.user_id';
    $query .= ' WHERE TeamsUser.team_id=' . $email['Event']['team_id'];
    $results = $this->Email->Event->Team->query($query);
    $managers = $this->Email->Event->Team->Managers->afterFind($results);
    
    $users = array();
    foreach ($email['PlayerTypes'] as $playerType)
    {
      $query = 'SELECT User.id, User.email, User.first_name, User.last_name, UserEmail.email, Player.id FROM users AS User LEFT JOIN user_emails AS UserEmail';
      $query .= ' ON UserEmail.user_id=User.id LEFT JOIN players AS Player ON Player.user_id=User.id LEFT JOIN teams AS Team ON Team.id=Player.team_id';
      $query .= ' LEFT JOIN events AS Event ON Event.team_id=Team.id'; 
      $query .= ' WHERE Event.id=' . $email['Email']['event_id'];
      $query .= ' AND Player.player_type_id=' . $playerType['id'];
      $query .= ' ORDER BY Player.player_type_id';
      $results = $this->Email->Event->Team->Player->User->query($query);
      $results = $this->Email->Event->Team->Player->User->afterFind($results);
      //$users[$playerType['name']] = array();
      foreach ($results as $user)
      {
        $responseId = $email['Event']['response_type_id'];
        // check responses
        $this->Email->Event->Response->expects();
        $conditions = array('Response.event_id' => $email['Email']['event_id'], 'Response.player_id' => $user['Player']['id']);
        $response = $this->Email->Event->Response->find($conditions, 'Response.response_type_id', 'Response.id DESC');
        if (!empty($response))
        {
          $responseId = $response['Response']['response_type_id'];
        }
        else
        {
          // default response
        }
        
        if (array_search($responseId, $responseTypes) !== false)
        {
          $emailArray = &$users[$playerType['name']];
          $name = $user['User']['nameOrEmail'];
          //$emails = &$emailArray[$name];
          $playerId = $user['Player']['id'];
          //$emailArray[] = $playerId;
          $emailArray[$playerId]['name'] = $name;
          //$emailArray[$playerId]['email'] = $user['User']['email'];
          $emails = &$emailArray[$playerId]['emails'];
          if (!is_array($emails))
          {
            $emails[] = $user['User']['email'];
          }
          else if (array_search($user['User']['email'], $emails) === false)
          {
            $emails[] = $user['User']['email'];
          }
          if (isset($user['UserEmail']) && $user['UserEmail']['email'])
          {
            if (array_search($user['UserEmail']['email'], $emails) === false)
            {
              $emails[] = $user['UserEmail']['email'];
            }
          }
        }
      }
    }
    
    $this->set(compact('email', 'users', 'creator', 'managers'));
    
    if ($this->isAjax())
    {
      $this->set('chartSize', '500x200');
    }
    return compact('email', 'users', 'creator', 'managers');
  }
  
  function __sendEmail($email)
  {
    $event = $this->Email->Event->findById($email['Email']['event_id']);
    
  }
  
  
  
  
//	function admin_index() {
//		$this->Email->recursive = 0;
//		$this->set('emails', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Email.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('email', $this->Email->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Email->create();
//			if ($this->Email->save($this->data)) {
//				$this->Session->setFlash(__('The Email has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Email could not be saved. Please, try again.', true));
//			}
//		}
//		$events = $this->Email->Event->find('list');
//		$this->set(compact('events'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Email', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Email->save($this->data)) {
//				$this->Session->setFlash(__('The Email has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Email could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Email->read(null, $id);
//		}
//		$events = $this->Email->Event->find('list');
//		$this->set(compact('events'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Email', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Email->del($id)) {
//			$this->Session->setFlash(__('Email deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}
//
	
	function update()
	{
	  $this->verifyAdminAuthentication();
	  $this->Email->expects();
	  $emails = $this->Email->findAll();
	  foreach ($emails as $email)
	  {
//	    debug($email['Email']['id']);
	    foreach (array('regular', 'sub', 'member') as $type)
	    {
	      if (isset($email['Email']['type_' . $type]) && $email['Email']['type_' . $type])
	      {
	        // create an email_player_type
	        $this->Email->EmailPlayerType->create();
	        $playerType = $this->Email->PlayerTypes->findByName($type);
	        //$emailPlayerType = array('PlayerTypes' => array('email_id' => $email['Email']['id'], 'player_type_id' => $playerType['PlayerTypes']['id']));
          $emailPlayerType = array('EmailPlayerType' => array('email_id' => $email['Email']['id'], 'player_type_id' => $playerType['PlayerTypes']['id']));
	        if (!$this->Email->EmailPlayerType->save($emailPlayerType))
	        {
	          debug('Error saving EmailPlayerType');
	          debug($emailPlayerType);
	        }
	      }
	    }
	    foreach (array('no_response', 'no', 'yes', 'maybe') as $type)
      {
        if (isset($email['Email']['attending_' . $type]) && $email['Email']['attending_' . $type])
        {
          // create an email_player_type
          $this->Email->EmailResponseType->create();
          $responseType = $this->Email->ResponseTypes->findByName($type);
          //$emailResponseType = array('ResponseTypes' => array('email_id' => $email['Email']['id'], 'response_type_id' => $responseType['ResponseTypes']['id']));
          $emailResponseType = array('EmailResponseType' => array('email_id' => $email['Email']['id'], 'response_type_id' => $responseType['ResponseTypes']['id']));
          if (!$this->Email->EmailResponseType->save($emailResponseType))
          {
            debug('Error saving EmailResponseType');
            debug($emailResponseType);
          }
        }
      }
	  }
	  debug('done');
	  exit();
	}

  function cron_job()
  {
    if (!$this->isCronJob() && !$this->isAdmin())
    {
      $this->invalidPage('/', '/emails/cron_job');
    }
    $this->layout = null;
    
    $query = 'SELECT Email.*, Event.*, TimeZone.value FROM emails AS Email LEFT JOIN events AS Event ON Event.id=Email.event_id';
    $query .= ' LEFT JOIN teams AS Team ON Team.id=Event.team_id LEFT JOIN users AS User ON User.id=Team.user_id';
    $query .= ' LEFT JOIN time_zones AS TimeZone ON TimeZone.id=User.time_zone_id';
    $query .= ' WHERE Event.end >= "' . date('Y-m-d H:i:s') . '" AND Email.sent IS NULL AND Email.send != "now"';
    $results = $this->Email->query($query);
    
    App::import('Helper', 'Time');
    $time = & new TimeHelper();
        
    foreach ($results as $email)
    {
      debug($email['Event']);
      debug($email['Email']);
      date_default_timezone_set($email['TimeZone']['value']);
      $emailDate = null;
      switch($email['Email']['send'])
      {
        case 'days_before':
          {
            $emailDate = strtotime('-' . $email['Email']['days_before'] . ' days', strtotime($email['Event']['start']));
            break;
          }
        case 'send_on' :
        {
          $emailDate = strtotime($email['Email']['send_on']);
          break;
        }
        case 'now':
        default:
          continue;
      }
      if ($emailDate && ($time->sameDay(date('M j, Y', $emailDate), date('M j, Y'))))
      {
        $result = $this->really_send($email['Email']['id'], true);
      }
    }
    exit();
  }
  
  /**
   * This function is basically a copy-and-paste from events_controller/rsvps
   * because requestAction was failing while trying to render an HTMl email
   * during a cron job
   *
   * @param unknown_type $eventId
   * @return unknown
   */
  function __events_rsvps($eventId)
  {
    return $this->_getRSVPS($this->Event, $eventId);
//    $this->Event->expects('ResponseType');
//    $event = $this->Event->findById($eventId);
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
//      $query .= ' LEFT JOIN events AS Event ON Event.id=Response.event_id WHERE Event.id=' . $eventId . ' AND ResponseType.name="' . $responseType . '"';
//      if (!empty($playerIds))
//      {
//        $query .= ' AND Player.id NOT IN (' . implode(', ', $playerIds) .')';
//      }
//      $query .= ' GROUP BY Player.id ORDER BY Response.id';
//      $result = $this->Event->query($query);
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
//    
//    // get default responses
//    $query = 'SELECT COUNT(*) AS COUNT FROM players AS Player LEFT JOIN teams AS Team ON Team.id=Player.team_id LEFT JOIN';
//    $query .= ' events AS Event ON Event.team_id=Team.id' . $conditions;
//    $result = $this->Event->query($query);
//    $responses[$event['ResponseType']['name']] = $result[0][0]['COUNT'];
//
//    $default = $event['ResponseType']['name'];
//    $responseTypes = $this->Event->Response->ResponseType->findAll();
//    $responseTypes = array_values($responseTypes);
//    
//    $data['responses'] = $responses;
//    $data['default'] = $default;
//    $data['responseTypes'] = $responseTypes;    
//    return $data;
  }
}
?>
