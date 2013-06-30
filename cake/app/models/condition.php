<?php
class Condition extends AppModel {

	var $name = 'Condition';
	var $useTable = 'conditions';

	var $validate = array(
    'number_of_players' => array(
      'number' => array('rule' => VALID_NUMBER, 'message' => 'You must enter a valid number of players'),
      'length' => array('rule' => array('maxLength', 11), 'message' => 'The name of the team must not exceed 32 characters'
      )
    )
  );
  
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Email' => array('className' => 'Email',
								'foreignKey' => 'email_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'ConditionType' => array('className' => 'ConditionType',
								'foreignKey' => 'condition_type_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	 var $hasAndBelongsToMany = array(
      'ResponseTypes' => array('className' => 'ResponseType',
            'joinTable' => 'condition_response_types',
            'foreignKey' => 'condition_id',
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
      'PlayerTypes' => array('className' => 'PlayerType',
            'joinTable' => 'condition_player_types',
            'foreignKey' => 'condition_id',
            'associationForeignKey' => 'player_type_id',
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
	
  function validateData($data = array())
  {
    $errors = array();
    if (empty($data))
    {
      $data = $this->data;
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

//  function beforeSave()
//  {
//    // remove all response types and player types, if any are set, they will be inserted during the save
//    $this->ConditionPlayerType->deleteAll(array('condition_id' => $this->data['Condition']['id']));
//    $this->ConditionResponseType->deleteAll(array('condition_id' => $this->data['Condition']['id']));
//    return parent::beforeSave();
//  }
}
?>