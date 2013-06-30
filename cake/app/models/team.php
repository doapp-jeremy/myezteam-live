<?php
class Team extends AppModel
{

	var $name = 'Team';
	var $useTable = 'teams';
	
  var $validate = array(
    'name' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must specify a name for the team'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'The name of the team must not exceed 32 characters'
      )
    ),
//    'type' => array('length' => array('rule' => array('maxLength', 32), 'message' => 'must not exceed 32 characters')),
//    'description' => array('length' => array('rule' => array('maxLength', 500), 'message' => 'must not exceed 500 characters'))
  );
  
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Creator' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	var $hasMany = array(
			'DefaultEmail' => array('className' => 'Email',
								'foreignKey' => 'team_id',
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
			'Event' => array('className' => 'Event',
								'foreignKey' => 'team_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => 'Event.start ASC',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
      'PastEvent' => array('className' => 'Event',
                'foreignKey' => 'team_id',
                'dependent' => true,
                'conditions' => 'PastEvent.end < now()',
                'fields' => '',
                'order' => 'PastEvent.end DESC',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
			'Player' => array('className' => 'Player',
								'foreignKey' => 'team_id',
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
			'Topic' => array('className' => 'Topic',
								'foreignKey' => 'team_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => 'Topic.modified DESC',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Error'
	);

	var $hasAndBelongsToMany = array(
			'Managers' => array('className' => 'User',
						'joinTable' => 'teams_users',
						'foreignKey' => 'team_id',
						'associationForeignKey' => 'user_id',
						'unique' => true,
						'conditions' => '',
						'fields' => '',
						'order' => '',
						'limit' => '',
						'offset' => '',
						'finderQuery' => '',
						'deleteQuery' => '',
						'insertQuery' => ''
			)
	);

	
	function beforeSave()
	{
	  if (isset($this->data['Team']['id']))
	  {
	    // remove all managers, if any are set, they will be inserted during the team save
	    $this->TeamsUser->deleteAll(array('TeamsUser.team_id' => $this->data['Team']['id']));
	  }
	  return parent::beforeSave();
	}
	
	function afterSave($created)
	{
	  if ($created)
	  {
	    // add player manager as a player to team
	    $team_id = $this->getLastInsertID();
	    $user_id = $this->data['Team']['user_id'];
	    $playerType = $this->Player->PlayerType->findByName('regular');
	    $player_type_id = $playerType['PlayerType']['id'];
	    $player = array('Player' => compact('team_id', 'user_id', 'player_type_id'));
	    if (!$this->Player->save($player))
	    {
	      $this->error('Team::afterSave', 'Could not save logged in user as a player on the newly created team', $user_id);
	    }
	  }
	  return parent::afterSave($created);
	}
	
	/**
	 * My Teams are teams I own (I created, Team.user_id),
	 * teams I manage (TeamsUser.user_id) and teams for which
	 * I am a player on (Player.user_id).
	 *
	 * @param string or int $userId
	 */
//	function findUsersTeams($userId)
//	{
//    $teamsIManage = $this->findTeamsUserManages($userId);
//    $teamIds = Set::extract($teamsIManage, '{n}.Team.id');
//
//    $conditions = array('Player.user_id' => $userId);
//    if (!empty($teamIds))
//    {
//      array_push($conditions, 'Player.team_id NOT IN (' . implode(', ', $teamIds) . ')');
//    }
//
//    $this->Player->expects();
//    $players = $this->Player->find('all', array('fields' => 'team_id', 'conditions' => $conditions));
//    $teamsImOn = $this->Player->findAll($conditions);
//    $teams = array_merge($teamsIManage, $teamsImOn);
//    return $teams;
//	}
//	
//	function findTeamsUserManages($userId)
//	{
//	  $teamsIOwn = $this->findTeamsUserOwns($userId);
//	  $teamIds = Set::extract($teamsIOwn, '{n}.Team.id');
//	  $conditions = array('TeamsUser.user_id' => $userId);
//	  if (!empty($teamIds))
//	  {
//	    $conditions[] = 'TeamsUser.team_id NOT IN (' . implode(', ', $teamIds) . ')';
//	  }
//	  $teamsIManage = $this->TeamsUser->findAll($conditions);
//	  $teams = array_merge($teamsIOwn, $teamsIManage);
//	  return $teams;
//	}
//	
//	function findTeamsUserOwns($userId)
//	{
//    return $this->findAllByUserId($userId);
//	}
}
?>