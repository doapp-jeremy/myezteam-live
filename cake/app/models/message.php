<?php
class Message extends AppModel {

	var $name = 'Message';
	var $useTable = 'messages';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Response' => array('className' => 'Response',
								'foreignKey' => 'response_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Event' => array('className' => 'Event',
								'foreignKey' => 'event_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'User' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Topic' => array('className' => 'Topic',
								'foreignKey' => 'topic_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Post' => array('className' => 'Post',
								'foreignKey' => 'post_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Error' => array('className' => 'Error',
								'foreignKey' => 'error_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'NewUser' => array('className' => 'User',
								'foreignKey' => 'new_user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
	
	function newResponse($response)
	{
		// don't do anything, trying to increase performance
		return;
	  if (isset($response['Event']))
	  {
	    $event = $response;
	  }
	  else
	  {
	    $this->Response->Event->expects('Team');
	    $event = $this->Response->Event->findById($response['Response']['event_id']);
	  }
	  
    if (isset($response['Player']))
    {
      $player = $response;
    }
    else
    {
      $this->Response->Player->expects();
      $player = $this->Response->Player->findById($response['Response']['player_id']);
    }
    
	  $title = 'RSVP: ';
    $message = array('Message' => array('title' => $title, 'response_id' => $response['Response']['id']));
	  $this->createMessageForManagersOfTeam($event['Event']['team_id'], $message, array($player['Player']['user_id']));
	}
	
	function newPost($post)
  {
		// don't do anything, trying to increase performance
		return;
  	if (isset($post['Topic']))
    {
      $topic = $post;
    }
    else
    {
      $this->Topic->expect();
      $topic = $this->User->Topic->findById($post['Post']['topic_id']);
    }
    if (empty($topic))
    {
      $this->error('Message::newPost', 'Could not find topic for post', null, $post);
    }
    $title = 'New Post: ';
    $message = array('Message' => array('title' => $title, 'post_id' => $post['Post']['id']));
    if (isset($topic['Topic']['team_id']))
    {
      $this->createMessageForEveryoneOnTeam($topic['Topic']['team_id'], $message, $post['Post']['user_id']);
    }
    else
    {
      $this->createAdminMessage($message);
    }
  }
	
  function newTopic($topic)
  {
		// don't do anything, trying to increase performance
		return;
  	$title = 'New Topic: ';
    $message = array('Message' => array('title' => $title, 'topic_id' => $topic['Topic']['id']));
    if (!empty($topic['Topic']['team_id']))
    {
      $this->createMessageForEveryoneOnTeam($topic['Topic']['team_id'], $message, $topic['Topic']['user_id']);
    }
    else
    {
      $this->createMessageForAllUsers($message);
    }
  }
  
  function newEvent($event)
  {
		// don't do anything, trying to increase performance
		return;
  	$title = 'New Event: ';
    $this->createEventMessage($event, $title);
  }
  
  function modifiedEvent($event)
  {
    // clear the cached data of the messages that belong to this event
//    $this->expects();
//    $messages = $this->findAll(array('Message.event_id' => $event['Event']['id']), 'Message.id');
//    foreach ($messages as $message)
//    {
//      $cacheName = 'element_message_cache_plugin_' . $message['Message']['id'] . '_message';
//      if (!$this->_clearcache($cacheName))
//      {
//        debug('Could not clear cache: ' . $cacheName);
//      }
//    }
//    $title = 'Updated Event: ';
//    $this->createEventMessage($event, $title);
  }
  
  function deletedEvent($event)
  {
		// don't do anything, trying to increase performance
		return;
  	//$this->markMessagesAsDeleted($this->findAllByEventId($event['Event']['id']));
    $this->deleteAll(array('Message.event_id' => $event['Event']['id']));

    $title = 'Cancelled Event: ' . $event['Event']['name'];
    $msg = array('Message' => array('title' => $title));
    $team = array();
    if (isset($event['Team']))
    {
      $team = $event;
    }
    else
    {
      $team = $this->User->Team->findById($event['Event']['team_id']);
    }
    $mgr = array();
    if (isset($team['Team']) && isset($team['Team']['user_id']))
    {
      $mgr = $team['Team']['user_id'];
    }
    $this->createMessageForEveryoneOnTeam($event['Event']['team_id'], $msg, $mgr);
  }

  function createEventMessage($event, $title)
  {
		// don't do anything, trying to increase performance
		return;
  	$message = array('Message' => array('title' => $title, 'event_id' => $event['Event']['id']));
//    $team = array();
//    if (isset($event['Team']))
//    {
//      $team = $event;
//    }
//    else
//    {
//      $this->Event->Team->expects();
//      $team = $this->Event->Team->findById($event['Event']['team_id']);
//    }
//    $mgr = array();
//    if (isset($team['Team']) && isset($team['Team']['user_id']))
//    {
//      $mgr = $team['Team']['user_id'];
//    }
//    $this->createMessageForEveryoneOnTeam($event['Event']['team_id'], $message, $mgr);
    $this->createMessageForEveryoneOnTeam($event['Event']['team_id'], $message);
  }

  function createMessageForEveryoneOnTeam($teamId, $message, $blackList = array())
  {
    $this->createMessageForManagersOfTeam($teamId, $message, $blackList);
    $this->createMessageForPlayersOnTeam($teamId, $message, true, $blackList);
  }
  
  function createMessageForPlayersOnTeam($teamId, $message, $noDuplicates = true, $blackList = array())
  {
    if (!is_array($blackList))
    {
      $blackList = array($blackList);
    }
    
    $query = 'SELECT User.id, TeamsUser.user_id FROM teams AS Team LEFT JOIN users AS User ON User.id=Team.user_id';
    $query .= ' LEFT JOIN teams_users AS TeamsUser ON TeamsUser.team_id=Team.id';
    $query .= ' WHERE Team.id=' . $teamId;
    $results = $this->query($query);
    $userIds = array();
    if (!empty($results))
    {
      $userIds = Set::extract($results, '{n}.User.id');
      $tmp = Set::extract($results, '{n}.TeamsUser.user_id');
      $userIds = array_merge($userIds, $tmp);
    }
    $blackList = array_merge($blackList, $userIds);
    $this->User->Player->expects('');
    $players = $this->User->Player->findAllByTeamId($teamId);
    foreach ($players as $player)
    {
      //if (!($noDuplicates && $this->User->TeamsUser->isManagerOfTeam($teamId, $player['Player']['user_id'])))
      {
        if (array_search($player['Player']['user_id'], $blackList) === false)
        {
          $this->createMessageForUser($player['Player']['user_id'], $message);
        }
      }
    }
  }
  
  function createMessageForManagersOfTeam($teamId, $message, $blackList = array())
  {
    if (!is_array($blackList))
    {
      $blackList = array($blackList);
    }
    $creator = $this->createMessageForCreatorOfTeam($teamId, $message);
    array_push($blackList, $creator);
    $this->User->TeamsUser->expects('');
    $teamsUsers = $this->User->TeamsUser->findAllByTeamId($teamId);
    foreach ($teamsUsers as $teamsUser)
    {
      if (array_search($teamsUser['TeamsUser']['user_id'], $blackList) === false)
      {
        $this->createMessageForUser($teamsUser['TeamsUser']['user_id'], $message);
      }
    }
  }
  
  function createMessageForCreatorOfTeam($teamId, $message)
  {
    $this->Event->Team->expects();
    $team = $this->Event->Team->findById($teamId);
    $this->createMessageForUser($team['Team']['user_id'], $message);
    return $team['Team']['user_id'];
  }
  
  function createMessageForUser($userId, $message)
  {
    $message['Message']['user_id'] = $userId;
    $this->createMessage($message);
    // reset cache for my messages
  }
  
  function createMessageForAdmin($message)
  {
    $me = $this->User->findByEmail('junker37@gmail.com');
    if (empty($me))
    {
      $this->error('Message->newUser', 'Could not locate my id', null, $user);
    }
    $this->createMessageForUser($me['User']['id'], $message);
  }
  
  function createMessage($msg)
  {
    $this->create();
    if (!$this->save($msg))
    {
      $this->error('Message::createMessage', 'There was an error trying to save message');
    }
  }
  
  function createMessageForAllUsers($message)
  {
    $this->User->expects('');
    $users = $this->User->findAll();
    foreach($users as $user)
    {
      $this->createMessageForUser($user['User']['id'], $message);
    }
  }
  
  var $associations = array('response', 'event', 'topic', 'post', 'error', 'new_user');
  
  var $count = 0;
  
//  function afterFind($results, $primary = false)
//  {
//    $this->count++;
//    debug($this->count);
//    //debug($results);
//    debug($primary);
//    if ($this->count > 1) exit();
//    foreach ($results as $message)
//    {
//      foreach ($this->associations as $field)
//      {
//        if (isset($message['Message']) && isset($message['Message'][$field . '_id']) && (strlen($message['Message'][$field . '_id']) > 0))
//        {
//          $model = Inflector::humanize($field);
//          if (isset($message[$model]) && (strlen($message[$model]['id']) <= 0))
//          {
//            // delete message because model no longer exists
//            if ($this->delete($message['Message']['id']))
//            {
//              debug('Deleted message for ' . $field . ' with id: ' . $message['Message'][$field . '_id']);
//            }
//            else
//            {
//              debug('Could not delete message for ' . $field . ' with id: ' . $message['Message'][$field . '_id']);
//            }
//          }
//        }
//      }
//    }
//    return parent::afterFind($results, $primary);
//  }
}
?>