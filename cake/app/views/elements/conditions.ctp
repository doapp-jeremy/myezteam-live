<?php // views/elements/conditions.ctp

?>

<?php if (empty($conditions)): ?>
  There are no conditions.  The email will be sent at the specified time.
<?php endif; ?>

<?php
$conditionStrings = array();
foreach ($conditions as $condition)
{
  $conditionObject = $condition;
  if (isset($condition['Condition']))
  {
    $conditionObject = $condition['Condition'];
  }
  $playerTypes = array();
  foreach ($condition['PlayerTypes'] as $playerType)
  {
    //$playerTypes[] = Inflector::humanize($playerType['name']);
    $playerTypes[] = Inflector::pluralize($playerType['name']);
  }
  $responseTypes = array();
  foreach ($condition['ResponseTypes'] as $responseType)
  {
    $responseTypes[] = low(Inflector::humanize($responseType['name']));
  }
  $cond = 'Send if <b>' . implode('</b> and <b>', $playerTypes) . '</b> who have RSVP\'d <b>' . implode('</b> or <b>', $responseTypes);
  $cond .= '</b> ' . $condition['ConditionType']['name'] . ' <b>' . $conditionObject['number_of_players'] . '</b>';
  $cond .= '<span class="mar10 smaller remove">';
  $options = array(
    'update' => $updateId,
    'title' => 'Delete Condition',
    'loading' => 'document.getElementById("' . $loadingId . '").innerHTML="removing condition...";',
    'complete' => 'doucument.getElementById("' . $loadingId . '").innnerHTML="";'
  );
  $cond .= $ajax->link('remove', array('controller' => 'conditions', 'action' => 'delete', $conditionObject['id']), $options, 'Are you sure you want to remove this condition?');
  //$cond .= $html->link('remove', array('controller' => 'conditions', 'action' => 'delete', $conditionObject['id']), array(), 'Are you sure you want to remove this condition?');
  $cond .= '</span>';
  $conditionStrings[] = $cond;
}
$conditionString = implode('<br>', $conditionStrings);
echo $conditionString;
?>
