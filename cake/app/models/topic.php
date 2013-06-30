<?php
class Topic extends AppModel {

	var $name = 'Topic';
	var $useTable = 'topics';
  
	var $actsAs = array('AutoField' => array(
   'newPosts' => array('fields' => array('id'), 'function' => 'newPosts'),
  ));
	
  var $validate = array(
    'title' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must specify a title for the topic'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'The title of the topic must not exceed 32 characters'
      )
    ),
  );
  
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Team' => array('className' => 'Team',
								'foreignKey' => 'team_id',
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
	);

	var $hasMany = array(
			'Post' => array('className' => 'Post',
								'foreignKey' => 'topic_id',
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
      'Message' => array('className' => 'Message',
                'foreignKey' => 'topic_id',
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
	 );

  function newPosts($data)
  {
    $newPosts = 0;
    $id = $data[0];
    if ($this->Session->check('User'))
    {
      $loginTime = $this->Session->read('loginTime');
      $user = $this->Session->read('User');
      $loginTime = $user['User']['last_login'];
      $userId = $user['User']['id'];
      $conditions = array('Post.topic_id' => $id, 'Post.created > "' . $loginTime . '"', 'Post.user_id!=' . $userId);
      $this->Post->expects();
      $newPosts = $this->Post->find('count', array('conditions' => $conditions));
    }
    return $newPosts;
  }

}
?>