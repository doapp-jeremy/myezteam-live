<?php
class MessagesController extends AppController {

	var $name = 'Messages';
  var $components = array('Mailer', 'MyHtmlMailer');
//  var $uses = array('Message', 'Event');
	
	function index()
	{
	  if (!$this->isLoggedIn())
	  {
	    // redirect to /pages/home controller
	    $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
	  }
	  // don't need any extra user information
	  $this->Message->User->hasMany = array();
    $this->Message->User->belongsTo = array();
    
    $this->Message->Topic->hasMany = array();
    $this->Message->Topic->belongsTo = array();
    
	  $this->Message->recursive = 3;
		$user = $this->getUser();
		$conditions['Message.user_id'] = $user['User']['id'];
		//$conditions['Message.del'] = 0;
		if (isset($user['User']['last_login']) && $user['User']['last_login'])
		{
      array_push($conditions, "Message.created > '" . $user['User']['last_login'] . "'");
		}
    //array_push($conditions, 'Message.topic_id IS NULL');
		$this->paginate['limit'] = 5;
		$this->paginate['conditions'] = $conditions;
		$this->paginate['order'] = 'Message.created DESC';

		$messages = $this->paginate();
		$this->set(compact('messages', 'user'));
		
		if (!empty($messages))
		{
		  $messageIds = Set::extract($messages, '{n}.Message.id');
		  $query = 'UPDATE messages SET del=1 WHERE id IN (' . implode(', ', $messageIds) . ')';
		  if ($this->Message->$query($query) === false)
		  {
		    $this->error('Messages::index', 'Could not mark messages for deletion: ' . $query, $user['User']['id']);
		  }
		}
	}
	
  function digest($userId = null)
  {
    $user = $this->getUser($userId);
    // get users emails
    $userEmails = $this->User->UserEmail->findAllByUserId($user['User']['id']);
    $emails = array();
    if (!empty($userEmails))
    {
      $emails = Set::extract($userEmails, '{n}.UserEmail.email');
    }

    $messages = $this->__getMessagesAfter($user['User']['id'], $user['User']['last_login']);
    debug(sizeof($messages));
    
    $this->layout = 'email/email';
    $isEmail = true;
    $this->set(compact('user', 'messages', 'emails', 'isEmail'));
    $this->render('daily_digest');
  }
  
  function __getMessagesAfter($user, $date)
  {
    $userId = $user['User']['id'];
    $query = 'SELECT Message.*, Event.*, Post.*, Response.*, Team.*, User.*, Topic.*, Player.*, ResponseType.* FROM messages AS Message';
    $query .= ' LEFT JOIN posts AS Post ON Post.id=Message.post_id';
    $query .= ' LEFT JOIN topics AS Topic ON (Topic.id=Message.topic_id OR Topic.id=Post.topic_id)';
    $query .= ' LEFT JOIN responses AS Response ON Response.id=Message.response_id';
    $query .= ' LEFT JOIN events AS Event ON (Event.id=Message.event_id OR Event.id=Response.event_id)';
    $query .= ' LEFT JOIN teams AS Team ON (Team.id=Event.team_id)';
    $query .= ' LEFT JOIN players AS Player ON Player.id=Response.player_id';
    $query .= ' LEFT JOIN users AS User ON (User.id=Post.user_id OR User.id=Topic.user_id OR User.id=Player.user_id)';
    $query .= ' LEFT JOIN response_types AS ResponseType ON ResponseType.id=Response.response_type_id';
    $query .= ' WHERE Message.user_id=' . $userId;
    $query .= ' AND Message.del=0';
    if ($date)
    {
      $query .= ' AND Message.created > "' . $date . '"';
    }
    if (isset($user['User']['last_login']) && $user['User']['last_login'])
    {
      $query .= ' AND Message.created > "' . $user['User']['last_login'] . '"';
    }
    $query .= ' AND (Event.end IS NULL OR Event.end >= "' . date('Y-m-d H:i:s') . '")';
    $query .= ' GROUP BY Message.id';
    $query .= ' ORDER BY Message.created ASC';
    $messages = $this->Message->query($query);
    $messages = $this->Message->User->afterFind($messages);
    $messages = $this->__clean($messages);
    return $messages;
  }
  
  function __clean($messages)
  {
    $goodMsgs = array();
    foreach ($messages as $message)
    {
      foreach (array('event', 'response', 'topic', 'post', 'new_user', 'error') as $field)
      {
        $model = Inflector::camelize($field);
        if (isset($message['Message'][$field . '_id']) && $message['Message'][$field . '_id'] && isset($message[$model]) && $message[$model]['id'])
        {
          $goodMsgs[] = $message;
        }
        else
        {
          // delete message
          if (!$this->Message->delete($message['Message']['id']))
          {
            $this->error('Messages::__clean', 'Could not delete message');
          }
        }
      }
    }
    return $goodMsgs;
  }
  
  function __getEvents($userId = null)
  {
    $user = $this->getUser($userId);
    $userId = $user['User']['id'];
    $events = array();
    
    $weekFromNow = date('Y-m-d H:i:s', strtotime('+1 week'));
    $teamIds = $this->getTeamIds($this->Message->Event->Team, $user['User']['id'], true);
    if (!empty($teamIds))
    {
      foreach ($teamIds as $teamId)
      {
//        $query = 'SELECT Team.*, Event.*, Response.*, ResponseType.*, Player.*, PlayerType.*, DefaultResponse.* FROM users AS User';
//        $query .= ' LEFT JOIN players AS Player ON (Player.user_id=User.id AND Player.team_id=' . $teamId . ')';
//        $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
//        $query .= ' LEFT JOIN teams AS Creator ON Creator.user_id=User.id';
//        $query .= ' LEFT JOIN teams_users AS Manager ON Manager.user_id=User.id';
//        $query .= ' JOIN teams AS Team ON (Team.id=Player.team_id OR Team.id=Creator.id OR Team.id=Manager.team_id)';
//        $query .= ' JOIN events AS Event ON Event.team_id=Team.id';
//        $query .= ' LEFT JOIN responses AS Response ON (Response.player_id=Player.id AND Response.event_id=Event.id)';
//        $query .= ' RIGHT JOIN (SELECT MAX(R.id) AS id FROM responses AS R JOIN players AS P ON P.id=R.player_id WHERE P.user_id=' . $userId;
//        $query .= ' GROUP BY R.event_id) R ON (R.id=Response.id OR Response.id IS NULL)';
//        $query .= ' LEFT JOIN (SELECT MAX(R.id) AS id FROM responses AS R JOIN players AS P ON P.id=R.player_id WHERE P.user_id=' . $userId;
//        $query .= ' GROUP BY R.event_id) S ON (S.id=Response.id OR R.id IS NULL)';
//        $query .= ' LEFT JOIN response_types AS ResponseType ON ResponseType.id=Response.response_type_id';
//        //$query .= ' (ResponseType.id=Response.response_type_id OR (Response.id IS NULL AND ResponseType.id=Event.response_type_id))';
//        $query .= ' JOIN response_types AS DefaultResponse ON ';
//        $query .= ' ((PlayerType.name="sub" AND Response.id IS NULL AND DefaultResponse.name="no_response") OR (DefaultResponse.id=Event.response_type_id))';
//        $query .= ' WHERE (Event.start > "' . date('Y-m-d H:i:s') . '" AND Event.start < "' . $weekFromNow . '")';
//        $query .= ' AND User.id=' . $userId;
//        $query .= ' AND ((PlayerType.name!="sub" OR Response.id IS NOT NULL) OR Team.user_id=' . $userId . ' OR Manager.user_id=' . $userId . ')';
//        $suffix = ' GROUP BY Team.id ORDER BY Event.start';
//        $results = $this->Message->Event->query($query . ' AND Team.id=' . $teamId . $suffix);
        
        $query = 'SELECT Team.*, Event.*, DefaultResponse.*, Player.*, PlayerType.* FROM teams AS Team';
        $query .= ' LEFT JOIN players AS Player ON (Player.user_id=' . $userId . ' AND Player.team_id=Team.id)';
        $query .= ' LEFT JOIN player_types AS PlayerType ON PlayerType.id=Player.player_type_id';
        $query .= ' LEFT JOIN teams_users AS Manager ON (Manager.user_id=' . $userId . ' AND Manager.team_id=Team.id)';
        $query .= ' JOIN events AS Event ON Event.team_id=Team.id';
        $query .= ' JOIN response_types AS DefaultResponse ON DefaultResponse.id=Event.response_type_id';
        $query .= ' WHERE (Manager.user_id=' . $userId . ' OR Team.user_id=' . $userId . ' OR Player.user_id=' . $userId . ')';
        //$query .= ' AND (PlayerType.name IS NULL OR PlayerType.name!="sub"';
        $query .= ' AND Team.id=' . $teamId . ' AND Event.start > "' . date('Y-m-d H:i:s') . '" AND Event.start < "' . $weekFromNow . '" LIMIT 1';
        $results = $this->Message->Event->query($query);
        if (!empty($results))
        {
          $event = $results[0];
          $response = array('Response' => array('id' => null));
          $responseType = array('ResponseType' => array());
          // get the users latest response
          if ($event['Player']['id'])
          {
            $query = 'SELECT Response.*, ResponseType.* FROM responses AS Response JOIN';
            $query .= ' (SELECT MAX(R.id) AS id FROM responses AS R WHERE R.player_id=' . $event['Player']['id'];
            $query .= ' AND R.event_id=' . $event['Event']['id'] . ') LastResponse ON LastResponse.id=Response.id';
            $query .= ' JOIN response_types AS ResponseType ON ResponseType.id=Response.response_type_id';
            $results = $this->Message->Event->query($query);
            if (!empty($results))
            {
              $response = $results[0];
              $responseType = $results[0];
            }
          }
          $event['Response'] = $response['Response'];
          $event['ResponseType'] = $responseType['ResponseType'];
          if (!$event['PlayerType']['name'] || (($event['PlayerType']['name'] != 'sub') || $event['Response']['id']))
          {
            array_push($events,  $event);
          }
        }
      }
    }
    return $events;
  }
	
  function __setDailyDigest($userId = null, $pastWeek = false)
  {
    $user = $this->getUser($userId);
    // get users emails
    $emails = $this->_getEmails($user['User']['id']);
    $response_types = $this->Message->Response->ResponseType->findAll();
    
    $events = $this->__getEvents($userId);
    $yesterday = date('Y-m-d', strtotime('-3 day'));
    if ($pastWeek)
    {
      $yesterday = date('Y-m-d', strtotime('-1 week'));
    }
    $messages = $this->__getMessagesAfter($user, $yesterday);
    $this->layout = 'email/email';
//    debug($response_types);
    $data = compact('user', 'messages', 'emails', 'events', 'response_types');
    $this->set($data);
//    $this->set('response_types', $response_types);
    return $data;
  }
  
  function daily_digest($userId = null, $pastWeek = false)
  {
    $this->__setDailyDigest($userId, $pastWeek);
  }
  
  function __sendDailyDigest($user, $emails, $data)
  {
    array_push($emails, $user['User']['email']);
    $subject = 'Daily Digest: (' . date('m/d/Y') . ')';
    $this->__sendDailyDigestTo($user, $emails, $subject, $data);
  }
  
  function __sendDailyDigestTo($user, $emails, $subject = 'Daily Digest', $data = array())
  {
    debug('Sending digest to: (' . implode(', ', $emails) . ')');
    $this->Mailer->init();
    $this->Mailer->Subject = $subject;
    $this->Mailer->to = array($emails);
    debug($this->Mailer->to);
    if (false && !$this->isCronJob())
    {
      debug('ob_start');
      ob_start();
      $this->autoRender = false;
      debug('rendering...');
      $this->render('daily_digest', 'email/email');
      $this->autoRender = 'auto';
      debug('ob_get_clean');
      $this->Mailer->Body = ob_get_clean();
    }
    else
    {
      debug('Cron job');
      // get pieChartData for events
      foreach ($data['events'] as &$event)
      {
        $event['PieChartData'] = $this->__events_rsvps($event['Event']['id']);
        debug($event['PieChartData']);
      }
      $this->Mailer->Body = $this->MyHtmlMailer->getDailyDigestBody($user, $emails, $data['messages'], $data['events'], $data['response_types']);
      debug($this->Mailer->Body);
    }
    $this->Mailer->IsHtml(true);
    debug('Seding email....');
    if ($this->Mailer->send())
    {
      debug('Daily digest sent to: ' . $user['User']['nameOrEmail']);
    }
    else
    {
      debug('Sending email failed');
      $this->error('Messages::__sendDailyDigestToAdmin', 'Could not sent daily digest to admin for: ' . implode(', ', $emails));
    }
  }
  
  function __sendDailyDigestToAdmin($user, $emails, $data)
  {
    array_push($emails, $user['User']['email']);
    debug('Sending daily digest for (' . implode(', ', $emails) . ') to junker37@gmail.com');
    $this->__sendDailyDigestTo($user, array('junker37@gmail.com'), 'Daily Digest for: ' . $user['User']['nameOrEmail'], $data);
  }
  
  function cron_job($pastWeek = false)
  {
    if (!$this->isCronJob() && !$this->isAdmin())
    {
      $this->invalidPage('/', '/messages/cron_job');
    }
    Configure::write('debug', 2);
    $this->layout = null;
    $dailyEmailId = $this->_getSettingId('Daily Email');
    $this->Message->User->expects('TimeZone', 'UserSetting');
    $conditions = 'User.email IN ("junker37@gmail.com", "jrmcjunk@us.ibm.com", "mcju0003@umn.edu")';
    $conditions = 'User.id=30';
    $users = $this->Message->User->findAll();
    foreach ($users as $user)
    {
      date_default_timezone_set($user['TimeZone']['value']);
      debug('Checking settings for user(' . $user['User']['id'] . '): ' . $user['User']['nameOrEmail']);
      if ($this->_getSettingValue($dailyEmailId, $user))
      {
        debug('Checking messages for user(' . $user['User']['id'] . '): ' . $user['User']['nameOrEmail']);
        $data = $this->__setDailyDigest($user['User']['id'], $pastWeek);
        if (!empty($data['messages']))
        {
          // send email
          // TODO: turn on after testing
          $this->__sendDailyDigest($user, $data['emails'], $data);
          //$this->__sendDailyDigestToAdmin($user, $data['emails'], $data);
        }
        else
        {
          debug('No New Messages');
        }
      }
      else
      {
        debug('Daily Email turned off');
      }
    }
    exit();
  }
  
  function clean()
  {
    $this->verifyAdminAuthentication();
    $associations = array('response', 'event', 'topic', 'post', 'error', 'new_user');
    $messages = $this->Message->findAll();
    foreach ($messages as $message)
    {
      foreach ($associations as $field)
      {
        if (isset($message['Message']) && isset($message['Message'][$field . '_id']) && (strlen($message['Message'][$field . '_id']) > 0))
        {
          $model = Inflector::camelize($field);
          if (isset($message[$model]) && (strlen($message[$model]['id']) <= 0))
          {
            // delete message because model no longer exists
            if ($this->Message->delete($message['Message']['id']))
            {
              debug('Deleted message for ' . $field . ' with id: ' . $message['Message'][$field . '_id']);
            }
            else
            {
              debug('Could not delete message for ' . $field . ' with id: ' . $message['Message'][$field . '_id']);
            }
          }
        }
      }
    }
    exit();
  }
	
  function ads($adId)
  {
    $servingAds = true;
    $this->set(compact('adId', 'servingAds'));
  }
  
//	function admin_index() {
//		$this->Message->recursive = 0;
//		$this->set('messages', $this->paginate());
//	}
//
//	function admin_view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid Message.', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->set('message', $this->Message->read(null, $id));
//	}
//
//	function admin_add() {
//		if (!empty($this->data)) {
//			$this->Message->create();
//			if ($this->Message->save($this->data)) {
//				$this->Session->setFlash(__('The Message has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Message could not be saved. Please, try again.', true));
//			}
//		}
//		$responses = $this->Message->Response->find('list');
//		$events = $this->Message->Event->find('list');
//		$users = $this->Message->User->find('list');
//		$topics = $this->Message->Topic->find('list');
//		$posts = $this->Message->Post->find('list');
//		$errors = $this->Message->Error->find('list');
//		$newUsers = $this->Message->NewUser->find('list');
//		$this->set(compact('responses', 'events', 'users', 'topics', 'posts', 'errors', 'newUsers'));
//	}
//
//	function admin_edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid Message', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Message->save($this->data)) {
//				$this->Session->setFlash(__('The Message has been saved', true));
//				$this->redirect(array('action'=>'index'));
//			} else {
//				$this->Session->setFlash(__('The Message could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Message->read(null, $id);
//		}
//		$responses = $this->Message->Response->find('list');
//		$events = $this->Message->Event->find('list');
//		$users = $this->Message->User->find('list');
//		$topics = $this->Message->Topic->find('list');
//		$posts = $this->Message->Post->find('list');
//		$errors = $this->Message->Error->find('list');
//		$newUsers = $this->Message->NewUser->find('list');
//		$this->set(compact('responses','events','users','topics','posts','errors','newUsers'));
//	}
//
//	function admin_delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for Message', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Message->del($id)) {
//			$this->Session->setFlash(__('Message deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//	}

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
    return $this->_getRSVPS($this->Message->Event, $eventId);
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