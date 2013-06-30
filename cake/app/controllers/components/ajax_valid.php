<?php
class AjaxValidComponent extends Object{
  var $controller = true;
  var $valid = true;//Valid until proven otherwise
  var $errors = array();//Where the list of errors will be stored
  var $form = array();//Where the form data will be stored
  var $return = "array";
  var $html;
  var $javascript;
  var $classFlag = false;
  var $actionUrl;
  var $method;
  
  function startup(&$controller)
  {
    // This method takes a reference to the controller which is loading it.
    // Perform controller initialization here.
    $this->controller = &$controller;
  }
  
  function setForm($form = array(), $actionUrl = null, $method = null)
  {
    $this->form = $form;
    if ($actionUrl != null)
    {
      if($method != null)
      {
        $this->method = $method;
      }
      $actionUrl = Router::url($actionUrl);
      $regEx = "^(ftp|http|https)://(www.)?";
      if(!ereg($regEx,$actionUrl))
      {
        $regEx = "^[A-Z][a-z]+/[a-z]+$";
        if(ereg($regEx,$actionUrl))
        {
          $url_ar = explode('/',$actionUrl);
          $actionUrl = strrchr(ROOT, "/").'/'.Inflector::pluralize(strtolower($url_ar[0])).'/'.$url_ar[1];
        }
      }
      $this->actionUrl = $actionUrl;
    }
    else
    {
      $this->actionUrl = false;
    }
  }
  
  function submit()
  {
    if($this->valid)
    {
      $elem = Inflector::camelize(key($this->form)." ".key($this->form[key($this->form)]));
      $submitStr = "";
      $submitStr = "<script type='text/javascript'>";
      $submitStr .= "elem = document.getElementById('".$elem."');\n";
      $submitStr .= "elem.form.action = '".$this->actionUrl."';\n";
      $submitStr .= "alert(elem.form.action);\n";
      $submitStr .= "elem.form.method='POST';\n";
      $submitStr .= "//elem.form.submit();\n";
      $submitStr .= "</script>";
      return $submitStr;
    }
  }
  
  function jsRedirect()
  {
    if($this->valid){
      $redirStr = "";
      $redirStr = "<script type='text/javascript'>";
      $redirStr .= "document.location = '".$this->actionUrl."';\n";
      $redirStr .= "</script>";
      return $redirStr;
    }
  }

  function confirm($initField = string, $fields = array(), $errormsg = string){
    $init_ar = explode("/",$initField);
    foreach($fields as $field){
      $field_ar = explode("/", $field);
//      if($this->form[$init_ar[0]][$init_ar[1]] != $field){
      if ($this->form[$init_ar[0]][$init_ar[1]] != $this->form[$field_ar[0]][$field_ar[1]])
      {
        $this->valid = false;
        $this->errors[$initField]['confirm'] = $errormsg;
        break;
      }
    }
  }

  function required($fields = array())
  {
    foreach ($fields as $field)
    {
      $field_ar = explode('.', $field);
      if (is_array($this->controller->{$field_ar[0]}->validate[$field_ar[1]]))
      {
        foreach ($this->controller->{$field_ar[0]}->validate[$field_ar[1]] as $key => $required)
        {
          if (isset($required['expression']))
          {
            if(!preg_match($required['expression'], $this->form[$field_ar[0]][$field_ar[1]]))
            {
              $this->errors[$field]['required'][$key] = $required['message'];
              $this->valid = false;
            }
          }
        }
      }
      else
      {
        if(!preg_match($this->controller->{$field_ar[0]}->validate[$field_ar[1]],$this->form[$field_ar[0]][$field_ar[1]]))
        {
          $this->errors[$field]['required'][$field_ar[1]] = Inflector::humanize(str_replace("_id","",$field_ar[1]))." is required.";
          $this->valid = false;
        }
      }
    }
  }

  function unique($table = array())
  {
    foreach ($table as $key => $fields):
    foreach($fields as $field):
    $field_ar = explode('.',$field);
    $model = $field_ar[0];
    $fieldName = $field_ar[1];
    //$tableField = str_replace('/','.',$field);
    $tableField = $field;
    $result = $this->controller->{$model}->find(array($tableField =>$this->form[$model][$fieldName]), $tableField);
    if(!empty($result))
    {
      $this->errors[$model][$fieldName] = $this->form[$model][$fieldName].' already exists.';
      $this->valid = false;
    }
    endforeach;
    endforeach;
  }

  function changeClass($errorClass = string)
  {
    $this->classFlag = $errorClass;
  }

  function changeClassFun()
  {
    if (!$this->valid)
    {
      $classStr = "";
      $classStr = "<script type='text/javascript'>";
      $classStr .= "
function errorClass(id,newClass){
    var elem_ar = document.getElementsByTagName('label');
    var classOld = '';
    var labelFor = '';
    var elem;
    for(x in elem_ar){
        labelFor = elem_ar[x].htmlFor+'';
        if(labelFor.indexOf(id) != -1){
            elem = elem_ar[x];
        }
    }
    classOld = elem.className+'';
    if(classOld.indexOf(newClass) == -1){
        elem.className = newClass+' '+classOld;
    }
}
function validClass(id,newClass){
    var elem_ar = document.getElementsByTagName('label');
    var classOld = '';
    var labelFor = '';
    var elem = '';
    for(x in elem_ar){
        labelFor = elem_ar[x].htmlFor+'';
        if(labelFor.indexOf(id) != -1){
            elem = elem_ar[x];
        }
    }
    if(elem!=''){
        classOld = elem.className+'';
        if(classOld.indexOf(newClass+' ') != -1){
            elem.className = classOld.replace(newClass+' ','');
        } else if (classOld.indexOf(newClass) != -1){
            elem.className = classOld.replace(newClass,'');
        }
    }
}";
      foreach($this->form as $parentKey =>$parentVal):
        foreach ($parentVal as $childKey => $childVal):
        $childKey_cam = Inflector::camelize($childKey);
        //if(!empty($this->errors[$parentKey.".".$childKey]))
        if(!empty($this->errors[$parentKey][$childKey]))
        {
          $classStr.="errorClass('".$parentKey.$childKey_cam."','".$this->classFlag."');
  ";
        }
        else
        {
          $classStr.="validClass('".$parentKey.$childKey_cam."','".$this->classFlag."');
  ";
        }
        endforeach;
      endforeach;
      $classStr .= "</script>" ;
      return $classStr;
    }
  }
  
  function validateModel($model, $ignore = array())
  {
    $modelObject = null;
    if (isset($this->controller->{$model}))
    {
      $modelObject = $this->controller->{$model};
    }
    else
    {
      $modelObject = $this->controller->{Inflector::singularize($this->controller->name)}->{$model};
    }
    $data = $modelObject->create($this->form[$model]);
    $errors = $modelObject->invalidFields();
    //$more_errors = $modelObject->validateData($data);
    $more_errors = $modelObject->validateData($this->form);
    //    $data = $this->controller->{$model}->create($this->form[$model]);
//    $errors = $this->controller->{$model}->invalidFields();
//    $more_errors = $this->controller->{$model}->validateData($data);
    if (!empty($more_errors))
    {
      $errors = array_merge($errors, $more_errors);
    }
    
    if (!empty($errors))
    {
      foreach ($errors as $field => $error)
      {
        if (!in_array($field, $ignore))
        {
          $this->valid = false;
          //$this->errors[$field][$error] = Inflector::humanize($field) . ': ' . $error;
          //$this->errors[$model][$field][$error] = $error;
          if (!is_array($error))
          {
            $this->errors[$model][$field] = $error;
          }
          else
          {
            foreach ($error as $field2 => $e)
            {
              $this->errors[$field][$field2] = $e;
            }
          }
        }
      }
    }
  }
  
  function validate ($models = null, $ignore = array())
  {
    if ($models)
    {
      if (is_array($models))
      {
        foreach ($models as $model)
        {
          $this->validateModel($model, $ignore);
        }
      }
      else
      {
        $this->validateModel($models, $ignore);
      }
    }
    else
    {
      $this->valid = empty($this->errors);
    }
   
    switch ($this->return){
      case 'array':
        return $this->errors;
        break;
      case 'html':
/*        $this->html = '<ul class="errorsList">';
        foreach ($this->errors as $err_key => $err_val):
        $this->html .='<li>'.ucfirst(substr($err_key,strpos($err_key,'/')+1));
        $this->html .= '<ul class="errorChild">';
        foreach ($err_val as $error1):
        if(is_array($error1)){
          foreach ($error1 as $error2):
          $this->html .='<li>'.$error2.'</li>';
          endforeach;
        } else {
          $this->html .='<li>'.$error1.'</li>';
        }
        endforeach;
        $this->html .='</ul></li>';
        endforeach;
        $this->html .= '</ul>';
*/
        $this->html = '';
        if (!empty($this->errors))
        {
          $this->html = '<div class="error">';
        }
        $this->html .= '<ul class="errorChild">';
        foreach ($this->errors as $models => $error_keys)
        {
          echo '<br>';
          foreach ($error_keys as $error_val => $error1)
          {
            if(is_array($error1))
            {
              foreach ($error1 as $error2):
              $this->html .='<li>'.$error2.'</li>';
              endforeach;
            }
            else
            {
              $this->html .='<li>'.$error1.'</li>';
            }
          }
        }
        $this->html .= '</ul>';
        if (!empty($this->errors))
        {
          $this->html .= '</div>';
        }
        
        if($this->classFlag != false)
        {
//          echo '<br>Changing class!!<br>';
          $this->html .=$this->changeClassFun();
        }
        if($this->method == 'submit'){
          $this->html .= $this->submit();
        }
        if($this->method == 'redirect'){
          // TODO: uncomment
          $this->html .= $this->jsRedirect();
        }
        return $this->html;
        break;
      case 'javascript':
        if(!$this->valid){
          $this->javascript = '<script type="text/javascript">alert("';
          $this->javascript .= 'Please fix the following Errors:\\n';
          foreach ($this->errors as $err_val):
          foreach ($err_val as $error1):
          if(is_array($error1)){
            foreach ($error1 as $error2):
            $this->javascript .='- '.$error2.'\\n';
            endforeach;
          } else {
            $this->javascript .='- '.$error1.'\\n';
          }
          endforeach;
          endforeach;
          $this->javascript .='");</script>';
        }
        if($this->classFlag != false){
          $this->javascript .=$this->changeClassFun();
        }
        if($this->method == 'submit'){
          $this->javascript .= $this->submit();
        }
        if($this->method == 'redirect'){
          $this->javascript .= $this->jsRedirect();
        }
        return $this->javascript;
        break;
      case 'test':
        return $this->submit();
        break;
    }
  }
}
?>