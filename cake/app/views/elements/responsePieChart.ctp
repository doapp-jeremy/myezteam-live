<?php // views/elements/responsePieChart.ctp : renders a pie chart for responses
//$responses : (optional -- if not set, $eventId or $event must be) : array of responses
?>

<?php
$labels = array();
$colors = array();
$values = array();
foreach ($responseTypes as $responseType)
{
  $responseType = array_pop($responseTypes);
  if (($responseType != 'no_response') || ($default == 'no_response'))
  {
    $responseName = $responseType['ResponseType']['name'];
    array_push($colors, $responseType['ResponseType']['color']);
    $value = 0;
    if (isset($responses[$responseName]))
    {
      $value = $responses[$responseName];
    }
    array_push($values, $value);
    $label = Inflector::humanize($responseName) . ' (' . $value . ')';
    if ($default == $responseName)
    {
      $label .= ' - Default';
    }
    array_push($labels, $label);
  }
}

$url = 'http://chart.apis.google.com/chart?';
$url .= 'cht=p3';
if (isset($chartSize))
{
  $url .= '&chs=' . $chartSize;
}
else
{
  $url .= '&chs=600x240';
}
$url .= '&chd=t:' . implode(',', $values);
$url .= '&chco=' . implode(',', $colors);
$url .= '&chl=' . implode('|', $labels);
echo $html->image($url);

?>