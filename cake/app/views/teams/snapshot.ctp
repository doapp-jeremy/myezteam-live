<?php // views/team/snapshop.ctp : shows upcoming events and recent topics

?>

<?php
$useAjax = true;
$showTitle = true;
echo $this->element('events', compact('team', 'isAjax', 'useAjax', 'showTitle'));

$topics = $team['Topic'];
$title = $myAjax->teamLink($team['Team']['id'], 'Topics', $team['Team']['id']);
$baseId = 'team' . $team['Team']['id'];
echo $this->element('topics', compact('title', 'baseId', 'topics', 'isAjax', 'useAjax'));
?>
