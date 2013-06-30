<?php
class MyAjaxHelper extends AppHelper
{
  var $name = 'MyAjax';
  var $helpers = array('Ajax', 'MyHtml');
  
  function teamActionTitle($action, $team = null)
  {
    if ($action == 'Main')
    {
      return $team['Team']['name'];
    }
    else if (isset($this->teamActions[$action]['title']))
    {
      return $this->MyHtml->teamActions[$action]['title'];
    }
    
    return $team['Team']['name'] . ' ' . $action;
  }
  
  function delete($data, $title = 'delete', $href = null, $options = array(), $confirm = null, $escapeTitle = true)
  {
    return $this->Ajax->link($title, $href, $options, $confirm, $escapeTitle);
  }
  
  function options($updateId, $linkName, $holderId = null, $linkId = null, $linkClass = null, $innerHTML = null, $loading = null, $complete = null)
  {
    if ($linkId)
    {
      $idToShow = $updateId;
      if ($holderId)
      {
        $idToShow = $holderId;
      }
      if (!$loading || ($loading !== false))
      {
        $loading = 'showElementId("loader", "inline"); loadingDiv("' . $idToShow . '", "' . $updateId . '"); linkClicked("' . $linkId . '", "' . $idToShow . '", "' . $linkClass . '");';
      }
      if (($complete === null) || ($complete !== false))
      {
        $complete = 'hideElementId("loader"); convertToShow("' . $linkId . '", "' . $idToShow . '", "' . $linkName . '", "' . $linkClass . '"';
        if ($innerHTML)
        {
          $complete .= ', "' . $innerHTML . '"';
        }
        $complete .= ');';
      }
      else if ($complete === false)
      {
        $complete = 'document.getElementById("loader").innerHTML = ""; hideElementId("loader");';
      }
    }
    $options = array(
      'title' => $linkName,
      'update' => $updateId,
      'loading' => $loading,
      'complete' => $complete
    );
    return $options;
  }

  function link($model, $action, $modelId = null, $selectedAction = false, $data = null, $ajax = true, $params = null)
  {
		$link = '';
    if (($selectedAction == $action) || ($ajax === false))
    {
      $link = $this->MyHtml->showDivAction($model, $action, $modelId, $data);
    }
    else
    {
      $link = $this->linkAjax($model, $action, $modelId, $data, $params);
    }
    return $link;
  }

  function linkAjax($model, $action, $modelId = null, $data = null, $params = null, $title = null)
  {
    if (!$title)
    {
      $title = $this->MyHtml->actionTitle($model, $action, $data);
    }
    $url = $this->MyHtml->actionUrl($model, $action, $modelId, $data, $params);
    $divId = $this->MyHtml->actionDivId($model, $action, $modelId, $data);
    $holderId = $this->MyHtml->actionHolderDivId($model, $action, $modelId, $data);
    $linkId = $this->MyHtml->actionId($model, $action, $modelId, $data);
    $linkClass = $this->MyHtml->linkClass($model, $action);
    $innerHTML = $this->MyHtml->showDivAction($model, $action, $modelId, $data);
    $innerHTML = str_replace('"', '\"', $innerHTML);
    $convert = $this->MyHtml->actionConvert($model, $action);
    $options = $this->options($divId, $title, $holderId, $linkId, $linkClass, $innerHTML, null, $convert);
    $confirm = $this->MyHtml->confirm($model, $action);
    return $this->Ajax->link($title, $url, $options, $confirm);
  }
  
  function refresh($model, $action, $modelId = null, $data = null, $params = null, $title = 'refresh')
  {
    $url = $this->MyHtml->actionUrl($model, $action, $modelId, $data, $params);
    $divId = $this->MyHtml->actionDivId($model, $action, $modelId, $data);
    $holderId = $this->MyHtml->actionHolderDivId($model, $action, $modelId, $data);
    $options = $this->options($divId, $title, $holderId);
    //$options['loading'] = 'document.getElementById("' . $divId . '").innerHTML = "refreshing..."';
    $loading = 'document.getElementById("loader").innerHTML = "refreshing..."; showElementId("loader", "inline")';
    $complete = 'document.getElementById("loader").innerHTML = ""; hideElementId("loader")';
    $options['loading'] = $loading;
    $options['complete'] = $complete;
    return $this->Ajax->link($title, $url, $options);
  }
  
  function showRefresh($model, $action)
  {
    return $this->MyHtml->actionValue($model, $action, 'refresh', false);
  }
}
?>