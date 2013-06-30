<?php class AppModel extends Model
{
  var $oldExpects = null;
  
	function afterFind($results, $primary = false) 
	{ 
		if (isset($this->__runResetExpects) && $this->__runResetExpects)
		{
			$this->__resetExpects();
			unset($this->__runResetExpects);
		}
		
		return parent::afterFind($results, $primary);
	}
	
	/**
	 * Unbinds all relations from a model except the specified ones. Calling this function without
	 * parameters unbinds all related models.
	 * 
	 * @access public
	 * @since 1.0
	 */
	function expects() 
	{
		$models = array();
		$arguments = func_get_args();
		if (!empty($arguments) && ($arguments[0] == 'reload'))		{
		  $arguments = $this->oldExpects;
		}
		else
		{
		  $this->oldExpects = $arguments;
		}
		$innerCall = false;

		if (!empty($arguments) && is_bool($arguments[0]))
		{
			$innerCall = $arguments[0];
		}
		
		foreach($arguments as $index => $argument) 
		{ 
			if (is_array($argument)) 
			{ 
				if (count($argument) > 0) 
				{ 
					$arguments = am($arguments, $argument); 
				} 

				unset($arguments[$index]); 
			}
		}
		
		foreach($arguments as $index => $argument)
		{
			if (!is_string($argument))
			{
				unset($arguments[$index]);
			}
		}

		if (count($arguments) == 0) 
		{ 
			$models[$this->name] = array(); 
		} 
		else 
		{ 
			foreach($arguments as $argument) 
			{ 
				if (strpos($argument, '.') !== false) 
				{ 
					$model = substr($argument, 0, strpos($argument, '.')); 
					$child = substr($argument, strpos($argument, '.') + 1); 

					if ($child == $model) 
					{
						$models[$model] = array(); 
					} 
					else 
					{ 
						$models[$model][] = $child; 
					} 
				}
				else 
				{ 
					$models[$this->name][] = $argument; 
				} 
			} 
		}
		
		$relationTypes = array ('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');

		foreach($models as $bindingName => $children) 
		{
			$model = null;
			
			foreach($relationTypes as $relationType) 
			{ 
				$currentRelation = (isset($this->$relationType) ? $this->$relationType : null);
				
				if (isset($currentRelation) && isset($currentRelation[$bindingName]) && is_array($currentRelation[$bindingName]) && isset($currentRelation[$bindingName]['className'])) 
				{
					$model = $currentRelation[$bindingName]['className'];
					break;
				}
			}
			
			if (!isset($model))
			{
				$model = $bindingName;
			}
			
			if (isset($model) && $model != $this->name && isset($this->$model)) 
			{
				if (!isset($this->__backInnerAssociation))
				{
					$this->__backInnerAssociation = array();
				} 
				
				$this->__backInnerAssociation[] = $model;
				
				$this->$model->expects(true, $children);
			} 
		}
		
		if (isset($models[$this->name])) 
		{ 
			foreach($models as $model => $children) 
			{ 
				if ($model != $this->name) 
				{ 
					$models[$this->name][] = $model; 
				} 
			} 
	
			$models = array_unique($models[$this->name]);
			$unbind = array(); 
	
			foreach($relationTypes as $relation) 
			{ 
				if (isset($this->$relation)) 
				{ 
					foreach($this->$relation as $bindingName => $bindingData)
					{ 
						if (!in_array($bindingName, $models))
						{ 
							$unbind[$relation][] = $bindingName; 
						} 
					} 
				} 
			} 
	
			if (count($unbind) > 0) 
			{ 
				$this->unbindModel($unbind); 
			}
		}

		if (!$innerCall)
		{
			$this->__runResetExpects = true;
		}
	}
	
	/**
	 * Resets all relations and inner model relations after calling expects() and find().
	 * 
	 * @access private
	 * @since 1.1
	 */
	function __resetExpects()
	{
		if (isset($this->__backAssociation))
		{
			$this->__resetAssociations();
		}
		
		if (isset($this->__backInnerAssociation))
		{
			foreach($this->__backInnerAssociation as $model)
			{
				$this->$model->__resetExpects();
			}
			
			unset($this->__backInnerAssociation);
		}
	}

  function error($function, $internalErrorMessage, $userId = 0)
	{
	  $error = array('Error' => array('function' => $function, 'message' => $internalErrorMessage, 'user_id' => $userId));
	  $this->Error->save($error);
	}

  /*
   * This function is used to validate data for models that
   * have inputs that are dependent on other inputs so they can't
   * be verified just be themselves
   * 
   * returns array of error strings
   */
  function validateData($data)
  {
    return array();
  }

  function validateEmptyOrNumber($value, $params = array())
  {
    //if (strlen($value) > 0)
    if (isset($value[$params['field']]))
    {
      $value = $value[$params['field']];
      if (strlen($value) > 0)
      {
        if (preg_match(VALID_NUMBER, $value))
        {
          return true;
        }
        else
        {
          return false;
        }
      }
    }
    return true;
  }
}
?>