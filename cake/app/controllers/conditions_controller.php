<?php
class ConditionsController extends AppController {

	var $name = 'Conditions';
	
	function add($emailId = null)
	{
	  $this->Condition->Email->expects();
	  $email = $this->verifyMember($this->Condition->Email, $emailId);
    $this->Condition->expects('ConditionType', 'PlayerTypes', 'ResponseTypes');
	  $conditions = $this->Condition->findAllByEmailId($emailId);
	  $this->set(compact('email', 'conditions'));
	  $this->data['Condition']['email_id'] = $emailId;
	  $this->_setFormData();
	}
	
	function _setFormData()
	{
    $player_types = $this->Condition->PlayerTypes->find('list');
    $response_types = $this->Condition->ResponseTypes->find('list');
    $condition_types = $this->Condition->ConditionType->find('list');
    $this->set(compact('response_types', 'player_types', 'condition_types'));
	  
	  return parent::_setFormData();
	}
	
	function delete($conditionId = null)
	{
	  $this->Condition->expects('Email');
	  $condition = $this->verifyManager($this->Condition, $conditionId);
	  if (!$this->Condition->delete($conditionId))
	  {
	    $this->error('Conditions::delete', 'Could not delete condition', $this->getUserID());
	  }
	  $this->Condition->expects('ConditionType', 'PlayerTypes', 'ResponseTypes');
	  $conditions = $this->Condition->findAllByEmailId($condition['Condition']['email_id']);
	  $this->set(compact('conditions'));
	}
	
	function validator()
	{
	  parent::validator();
	}
}
?>