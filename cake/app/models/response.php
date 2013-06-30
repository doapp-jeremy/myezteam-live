<?php
class Response extends AppModel {

	var $name = 'Response';
	var $useTable = 'responses';
	var $order = 'Response.created DESC';
	
  var $responseTypes = array('yes', 'probable', 'maybe', 'no');
	
//  var $hasOne = array(
//      'ResponseType' => array('className' => 'ResponseType',
//                'foreignKey' => 'response_type_id',
//                'conditions' => '',
//                'fields' => '',
//                'order' => ''
//      ),
//  );
  
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Player' => array('className' => 'Player',
								'foreignKey' => 'player_id',
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
			'ResponseType' => array('className' => 'ResponseType',
                'foreignKey' => 'response_type_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
      ),
//      'Record' => array('className' => 'Record',
//                'foreignKey' => 'record_id',
//                'conditions' => '',
//                'fields' => '',
//                'order' => ''
//      )
	);

	var $hasMany = array(
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

  function afterSave($created)
  {
    if ($created)
    {
      // create messages
      $this->data[$this->name]['id'] = $this->getLastInsertID();
      $this->Message->newResponse($this->data);
    }
    return parent::afterSave($created);
  }
}
?>