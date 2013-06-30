<?php
class Player extends AppModel {

	var $name = 'Player';
	var $useTable = 'players';

 var $validate = array(
    'type' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must select a player type')
    ),
    'user_id' => array(
      'required' => array('rule' => VALID_NOT_EMPTY, 'message' => 'You must select a contact, or enter an email for a new contact'),
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
			'User' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
      'PlayerType' => array('className' => 'PlayerType',
                'foreignKey' => 'player_type_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
      ),
	);

	var $hasMany = array(
			'Response' => array('className' => 'Response',
								'foreignKey' => 'player_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => 'created DESC',
								'limit' => 1,
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			)
//      'Record' => array('className' => 'Record',
//                'foreignKey' => 'player_id',
//                'dependent' => false,
//                'conditions' => '',
//                'fields' => '',
//                'order' => '',
//                'limit' => '',
//                'offset' => '',
//                'exclusive' => '',
//                'finderQuery' => '',
//                'counterQuery' => ''
//      )
  );

}
?>