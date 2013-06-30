<?php
class Email extends AppModel {

	var $name = 'Email';
	var $useTable = 'emails';
  var $validate = array(
//    'title' => array(
//      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must specify a title for the email'),
//      'length' => array('rule' => array('maxLength', 32), 'message' => 'The title of the email must not exceed 32 characters'
//      )
//    ),
      'days_before' => array(
      'valid' => array('rule' => array('notnegative', array('field' => 'days_before', 'allowEmpty' => true)), 'message' => 'Days Before: invalid number')
    ),
//    'condition_count' => array(
//      'valid' => array('rule' => array('validateEmptyOrNumber', array('field' => 'condition_count')), 'message' => 'Condition Count: invalid number')
//    )
  );

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Event' => array('className' => 'Event',
								'foreignKey' => 'event_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
      'Team' => array('className' => 'Team',
                'foreignKey' => 'team_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
      ),
  );

	var $hasMany = array(
      'Condition' => array('className' => 'Condition',
                'foreignKey' => 'email_id',
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
      'EmailPlayerType' => array('className' => 'EmailPlayerType',
                'foreignKey' => 'email_id',
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
//      'Error',
    );
	
	 var $hasAndBelongsToMany = array(
      'PlayerTypes' => array('className' => 'PlayerType',
            'joinTable' => 'email_player_types',
            'foreignKey' => 'email_id',
            'associationForeignKey' => 'player_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'player_type_id',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
      ),
      'ResponseTypes' => array('className' => 'ResponseType',
            'joinTable' => 'email_response_types',
            'foreignKey' => 'email_id',
            'associationForeignKey' => 'response_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
      ),
    );
	
  function notnegative($value, $params = array())
  {
//    if (strlen($value) > 0)
    if (isset($value[$params['field']]) && $value[$params['field']])
    {
      $value = $value[$params['field']];
      if (preg_match(VALID_NUMBER, $value))
      {
        $int = intval($value);
        return ($int >= 0);
      }
      else
      {
        return false;
      }
    }
    if (isset($params['allowEmpty']))
    {
      return $params['allowEmpty'];
    }
   
    // if allowEmpty is not set, default to false
    return false;
  }
  
  function validateData($data = array())
  {
    $errors = array();
    if (empty($data))
    {
      $data = $this->data;
    }
    
    $emailDate = null;
    // verify email date
    switch ($data['Email']['send'])
    {
      case 'days_before':
        {
          $this->Event->expects();
          $event = $this->Event->findById($this->data['Email']['event_id']);
          $emailDate = strtotime('-' . $this->data['Email']['days_before'] . ' days', strtotime($event['Event']['start']));
          break;
        }
      case 'send_on' :
        {
          $emailDate = strtotime($this->data['Email']['send_on']);
          break;
        }
    }
    if ($emailDate && ($emailDate <= strtotime(date('M j, Y'))))
    {
      $errors['Email']['send'] = 'You must select a date after today';
    }

    // verify player type values
    if (!isset($data['PlayerTypes']) || empty($data['PlayerTypes']))
    {
      $errors['PlayerTypes']['PlayerTypes'] = 'You must select at least 1 player type';
    }
    
    // verify response type values
    if (!isset($data['ResponseTypes']) || empty($data['ResponseTypes']))
    {
      $errors['ResponseTypes']['ResponseTypes'] = 'You must select at least 1 response type';
    }
    
    return $errors;
  }

  function __copyFields($inEmail, &$outEmail, $inName = 'Email', $outName = 'Email')
  {
    $copyFields = array('title', 'days_before', 'content', 'rsvp', 'send');
    foreach ($copyFields as $field)
    {
      $outEmail[$outName][$field] = $inEmail[$inName][$field];
    }
  }
  
  function __save($email)
  {
    $this->create();
    if (!$this->save($email))
    {
      $this->error('Email::__save', 'Could not save email ');
      return false;
    }
    return $email;
  }
  
  function toDefault($email, $teamId, $inName = 'Email', $outName = 'Email')
  {
    $defaultEmail = array('Email' => array('team_id' => $teamId));
    $this->__copyFields($email, $defaultEmail, $inName, $outName);
    $this->__copyAssocations($email, $defaultEmail);
    return $this->__save($defaultEmail);
  }
  
  function fromDefault($defaultEmail, $eventId, $inName = 'Email', $outName = 'Email')
  {
    $email = array($outName => array('event_id' => $eventId));
    $this->__copyFields($defaultEmail, $email, $inName, $outName);
    $this->__copyAssocations($defaultEmail, $email);
    return $this->__save($email);
  }
  
  function __copyAssocations($inEmail, &$outEmail)
  {
    $associations = array(
        'PlayerTypes' => array('index' => 'EmailPlayerType', 'id' => 'player_type_id'),
        'ResponseTypes' => array('index' => 'EmailResponseType', 'id' => 'response_type_id')
    );
    foreach ($associations as $association => $keys)
    {
      foreach ($inEmail[$association] as $original)
      {
        $outEmail[$association][$association][] = $original[$keys['index']][$keys['id']];
      }
    }
  }
}
?>