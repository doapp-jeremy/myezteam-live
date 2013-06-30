<?php
class User extends AppModel {

	var $name = 'User';
	var $useTable = 'users';
	
	var $passwordSalt = 'PasswordSalt';
	

	var $actsAs = array('AutoField' => array(
	 'name' => array('fields' => array('first_name', 'last_name', 'email'), 'mask'=>'%s %s'),
	 'nameAndEmail' => array('fields' => array('first_name', 'last_name', 'email'), 'mask' => '%s %s (%s)'),
   'nameOrEmail' => array('fields' => array('first_name', 'last_name', 'email'), 'function' => 'nameOrEmail')
	));
	
	var $otherNames = array('Creator', 'Managers', 'NewUser');

  var $validate = array(
    'email' => array(
      'required' => array('rule' => VALID_EMAIL, 'message' => 'You must specify a valid email address'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'The email address must not exceed 32 characters'),
    ),
    'password' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must enter a password'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'Your password must not exceed 32 characters')
    )
  );
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
      'TimeZone' => array('className' => 'TimeZone',
                'foreignKey' => 'time_zone_id',
                'dependent' => false,
                'conditions' => '',
                'fields' => '',
                'order' => ''
      ),
//			'Player' => array('className' => 'Player',
//								'foreignKey' => 'user_id',
//								'conditions' => '',
//								'fields' => '',
//								'order' => ''
//			),
//      'TeamsUser' => array('className' => 'TeamsUser',
//                'foreignKey' => 'user_id',
//                'conditions' => '',
//                'fields' => '',
//                'order' => ''
//      ),
			//      'Picture' => array('className' => 'Picture',
//                'foreignKey' => 'picture_id',
//                'conditions' => '',
//                'fields' => '',
//                'order' => ''
//      )
	);

	var $hasOne = array(
//			'Picture' => array('className' => 'Picture',
//								'foreignKey' => 'user_id',
//								'dependent' => false,
//								'conditions' => '',
//								'fields' => '',
//								'order' => ''
//			),
	);

	var $hasMany = array(
			'Message' => array('className' => 'Message',
								'foreignKey' => 'user_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
      'NewUserMessage' => array('className' => 'Message',
                'foreignKey' => 'new_user_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
			'Player' => array('className' => 'Player',
								'foreignKey' => 'user_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
//			'Post' => array('className' => 'Post',
//								'foreignKey' => 'user_id',
//								'dependent' => false,
//								'conditions' => '',
//								'fields' => '',
//								'order' => '',
//								'limit' => '',
//								'offset' => '',
//								'exclusive' => '',
//								'finderQuery' => '',
//								'counterQuery' => ''
//			),
			'Team' => array('className' => 'Team',
								'foreignKey' => 'user_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
      'TeamsUser' => array('className' => 'TeamsUser',
                'foreignKey' => 'user_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
//			'Topic' => array('className' => 'Topic',
//								'foreignKey' => 'user_id',
//								'dependent' => false,
//								'conditions' => '',
//								'fields' => '',
//								'order' => '',
//								'limit' => '',
//								'offset' => '',
//								'exclusive' => '',
//								'finderQuery' => '',
//								'counterQuery' => ''
//			),
			'UserEmail' => array('className' => 'UserEmail',
								'foreignKey' => 'user_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'UserIp' => array('className' => 'UserIp',
								'foreignKey' => 'user_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => 'UserIp.created DESC',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
      'UserSetting' => array('className' => 'UserSetting',
                'foreignKey' => 'user_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
//      'Settings' => array('className' => 'UserSetting',
//            'joinTable' => 'user_settings',
//            'foreignKey' => 'user_id',
//            'associationForeignKey' => 'setting_type_id',
//            'unique' => true,
//            'conditions' => '',
//            'fields' => '',
//            'order' => '',
//            'limit' => '',
//            'offset' => '',
//            'finderQuery' => '',
//            'deleteQuery' => '',
//            'insertQuery' => ''
//      )
    'Error'
	);

	 var $hasAndBelongsToMany = array(
//      'Contacts' => array('className' => 'User',
//            'joinTable' => 'users_users',
//            'foreignKey' => 'user_id',
//            'associationForeignKey' => 'contact_id',
//            'unique' => true,
//            'conditions' => '',
//            'fields' => '',
//            'order' => 'first_name ASC',
//            'limit' => '',
//            'offset' => '',
//            'finderQuery' => '',
//            'deleteQuery' => '',
//            'insertQuery' => ''
//      )
//      'Settings' => array('className' => 'UserSetting',
//            'joinTable' => 'user_settings',
//            'foreignKey' => 'user_id',
//            'associationForeignKey' => 'setting_type_id',
//            'unique' => true,
//            'conditions' => '',
//            'fields' => '',
//            'order' => '',
//            'limit' => '',
//            'offset' => '',
//            'finderQuery' => '',
//            'deleteQuery' => '',
//            'insertQuery' => ''
//      )
	 );
	
	function afterFind($results, $primary = false)
	{
	  if (!$primary && !empty($this->behaviors))
	  {
	    $b = array_keys($this->behaviors);
	    $c = count($b);

	    for ($i = 0; $i < $c; $i++)
	    {
	      $return = $this->behaviors[$b[$i]]->afterFind($this, $results, $primary);
	      if (is_array($return))
	      {
	        $results = $return;
	      }
	    }
	  }
	  return parent::afterFind($results, $primary);
	}
	
	function nameOrEmail($data)
	{
	  $name = $data[2];
	  if ((isset($data[0]) && !empty($data[0])) || (isset($data[1]) && !empty($data[1])))
	  {
	    if (isset($data[0]) && !empty($data[0]))
	    {
	      $name = $data[0];
	      if (isset($data[1]) && !empty($data[1]))
	      {
	        $name .= ' ' . $data[1];
	      }
	    }
	    else
	    {
	      $name = ' ' . $data[1];
	    }
	  }
	  return $name;
	}
	
	function findAllContacts($userId, $teams = array())
	{
	  if (empty($teams))
	  {
	    return array();
	  }
	  $myContacts = array();
    $userIds[] = $userId;
	  $this->expects('Contacts');
	  $contacts = $this->find('all', array('fields' => array('id'), 'conditions' => array('User.id' => $userId), 'order' => 'first_name'));
	  if (!empty($contacts) && !empty($contacts[0]['Contacts']))
	  {
	    foreach ($contacts[0]['Contacts'] as $contact)
	    {
        array_push($myContacts, array('User' => $contact));
	    }
      $ids = Set::extract($contacts[0], 'Contacts.{n}.id');
      $userIds = array_merge($userIds, array_values($ids));
	  }
	  // eventually won't have to make this call, once all users are in the contact list
    //$usersUsers = $this->UsersUser->findAll(array('UsersUser.contact_id' => $userId, 'UsersUser.user_id NOT IN (' . implode(', ', array_values($userIds)) . ')'));
    $query = 'SELECT * FROM users_users AS UsersUser JOIN users AS User ON User.id=UsersUser.user_id JOIN users AS Contact ON Contact.id=UsersUser.contact_id';
    $query .= ' WHERE UsersUser.contact_id=' . $userId;
    $query .= ' AND UsersUser.user_id NOT IN (' . implode(', ', array_values($userIds)) . ')';
    $results = $this->query($query);
    $usersUsers = $this->afterFind($results);
    if (!empty($usersUsers))
    {
      // add users to contact list
      $this->__addContacts($userId, $usersUsers, 'UsersUser', 'user_id');
      foreach ($usersUsers as $usersUser)
      {
        array_push($myContacts, array('User' => $usersUser['User']));
      }
      //$myContacts = array_merge($myContacts, $usersUsers);
      $usersIds = Set::extract($usersUsers, '{n}.UsersUser.user_id');
      $userIds = array_merge($userIds, $usersIds);
    }
    
    $conditions[] = 'User.id NOT IN (' . implode(', ', array_values($userIds)) . ')';
    
    // eventually won't have to make this call, once all users are in the contact list
//    $teams = $this->Team->findUsersTeams($userId);
    $teamIds = Set::extract($teams, '{n}.Team.id');
    if (!empty($teamIds))
    {
      $conditions[] = 'Team.id IN (' . implode(', ', array_values($teamIds)) . ')';
      $query = 'SELECT User.* FROM users AS User LEFT JOIN players AS Player ON User.id=Player.user_id LEFT JOIN teams AS Team ON Player.team_id=Team.id WHERE ';
      $query .= implode(' AND ', $conditions) . ' ORDER BY BINARY first_name ASC';
      $friends = $this->query($query);
      if (!empty($friends))
      {
        // add friends to contact list
        $friends = $this->afterFind($friends, false);
        $myContacts = array_merge($myContacts, $friends);
        $this->__addContacts($userId, $friends);
      }
    }
    return $myContacts;
	}
	
	function __addContacts($userId, $contacts, $model = 'User', $key = 'id')
	{
	  foreach ($contacts as $contact)
	  {
//      $this->UsersUser->create();
//      $usersUser = array('UsersUser' => array('user_id' => $userId, 'contact_id' => $contact[$model][$key]));
//      if (!$this->UsersUser->save($usersUser))
      $query = 'INSERT INTO users_users VALUES (NULL, now(), now(), ' . $userId . ', ' . $contact[$model][$key] . ')';
      $this->query($query);
//	    {
//        $this->error('UserModel::__addContacts', 'Could not create UsersUsers for user_id: ' . $userId . ' contact_id:' . $contact[$model][$key], $userId);
//      }
	  }
	}

  function createUserIp($user)
  {
    if (isset($user['User']['id']) && isset($user['User']['ip']))
    {
      $userIp = $this->UserIp->findByUserIdAndIp($user['User']['id'], $user['User']['ip']);
      if (empty($userIp))
      {
        if (!$this->UserIp->save(array('UserIp' => array('user_id' => $user['User']['id'], 'ip' => $user['User']['ip']))))
        {
          $this->error('User::createUserIp', 'Could not create UserIp: . ' . $user['User']['id'] . ':' . $user['User']['ip'], null, $userIp);
        }
      }
    }
  }

  function generateRSSFeedId($user)
  {
    if (isset($user['password']))
    {
      return md5($user['email'] . $user['password'] . 'RSS_FEED_SALT');
    }

    return null;
  }
  
  function createRSSFeedId($user)
  {
    $feedId = null;
    if (isset($user[$this->name]['password']))
    {
      $feedId = $this->generateRSSFeedId($user[$this->name]);
      $user[$this->name]['feed_id'] = $feedId;
      unset($user[$this->name]['password']);
    }
    $this->save($user, false, array('feed_id'));
    return $feedId;
  }
  
  function createUsersUser($user)
  {
    if ($this->Session->check('User'))
    {
      $current = $this->Session->read('User');
      // logged in user -> new user
      $usersUser = $this->UsersUser->findByUserIdAndContactId($current['User']['id'], $user['User']['id']);
      if (empty($usersUser))
      {
        $usersUser = array('UsersUser' => array('user_id' => $current['User']['id'], 'contact_id' => $user['User']['id']));
        $this->UsersUser->create();
        if (!$this->UsersUser->save($usersUser))
        {
          // couldn't save, check for the other user user
          // if the save completed successfully, it would check and create the other users_users if necessay
          $usersUser = $this->UsersUser->findByUserIdAndContactId($user['User']['id'], $current['User']['id']);
          if (empty($usersUser))
          {
            $usersUser = array('UsersUser' => array('user_id' => $user['User']['id'], 'contact_id' => $current['User']['id']));
            $this->UsersUser->create();
            $this->UsersUser->save($usersUser);
          }
        }
      }
    }
  }
	
  function validateLogin($data)
  {
    $this->expects('TimeZone');
    $user = $this->findByEmail($data['User']['email']);
    if (!empty($user))
    {
      if (!isset($user['User']['password']) || !$user['User']['password'])
      {
        return $data['User']['email'];
      }
      else if (md5($data['User']['password'] . $this->passwordSalt) == $user['User']['password'])
      {
        return $user;
      }
    }

    return false;
  }
  
  function beforeSave()
  {
    if (isset($this->data[$this->name]['password']) && $this->data[$this->name]['password'])
    {
      if (isset($this->data[$this->name]['confirm']) && $this->data[$this->name]['confirm'])
      {
        $this->data[$this->name]['password'] = md5($this->data[$this->name]['password'] . $this->passwordSalt);
      }
      else
      {
        unset($this->data[$this->name]['password']);
      }
    }
    return parent::beforeSave();
  }
  
}
?>