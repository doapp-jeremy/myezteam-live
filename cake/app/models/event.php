<?php
class Event extends AppModel {

	var $name = 'Event';
	var $useTable = 'events';
	
	var $order = 'Event.start ASC';

  var $validate = array(
    'name' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must specify a name for the event'),
      'length' => array('rule' => array('maxLength', 32), 'message' => 'must not exceed 32 characters')
    )
  );
  
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Team' => array('className' => 'Team',
								'foreignKey' => 'team_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
      'ResponseType' => array('className' => 'ResponseType',
                'foreignKey' => 'response_type_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
      )
	);

	var $hasMany = array(
      'Message' => array('className' => 'Message',
                'foreignKey' => 'event_id',
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
	    'Email' => array('className' => 'Email',
								'foreignKey' => 'event_id',
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
      'Response' => array('className' => 'Response',
                'foreignKey' => 'event_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => 'created DESC',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
      ),
//			'Record' => array('className' => 'Record',
//								'foreignKey' => 'event_id',
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
			'Topic' => array('className' => 'Topic',
								'foreignKey' => 'event_id',
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
      'Message' => array('className' => 'Message',
                'foreignKey' => 'event_id',
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

	function createDefaultEmails($teamId)
	{
      // TODO: create default emails
      $this->Email->expects('PlayerTypes', 'ResponseTypes');
      $defaultEmails = $this->Email->findAllByTeamId($teamId);
      $eventId = $this->getLastInsertID();
      foreach ($defaultEmails as $email)
      {
        $this->Email->fromDefault($email, $eventId);
      }
	}

	function afterSave($created)
  {
    $event = $this->data;
    if ($created)
    {
      $eventId = $this->getLastInsertID();
      $event['Event']['id'] = $eventId;
      // create default emails
      $this->createDefaultEmails($event['Event']['team_id']);
      $this->Message->newEvent($event);
      
      $title = null;
      $msg = null;
    }
    else
    {
      if (isset($event['Event']['team_id']))
      {
        $this->Message->modifiedEvent($event['Event']['team_id']);
      }
    }
    
    parent::afterSave($created);
  }
  
//  var $deletedEvent = array();
//  
//  function beforeDelete($cascade = true)
//  {
//    if ($cascade)
//    {
//      // delete all records for the event
//      $this->Record->deleteAll('Record.event_id=' . $this->id);
//      // delete all emails for the event
//      $this->Email->deleteAll('Email.event_id=' . $this->id);
//    }
//    $this->deletedEvent = $this->findById($this->id);
//    return parent::beforeDelete($cascade);
//  }
//  
//  function afterDelete()
//  {
//    if (!empty($this->deletedEvent))
//    {
//      // update messages
//      $this->Message->deletedEvent($this->deletedEvent);
//    }
//    return parent::afterDelete();
//  }
//	
}
?>