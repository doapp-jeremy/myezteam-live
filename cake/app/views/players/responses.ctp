<?php // views/players/responses.ctp
//$players : (required) : array of players
//$event : (required) : the event the responses belong to
?>

<?php
$url = array('controller' => 'players', 'action' => 'responses', $event['Event']['id']);
$elementName = 'event/eventRSVPS';
$elementOptions = compact('players', 'event');
echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
?>
