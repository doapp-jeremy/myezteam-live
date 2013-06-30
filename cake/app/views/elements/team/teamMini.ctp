<?php // views/elements/miniTeam.ctp : renders a team drop down element
//$team: the team to render
?>

<?php $title = $html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?>
<?php echo $this->element('dropDown',
array('options' => array(
        'title' => $title, 
        'data' => $team['Team']['description'],
        'url' => array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])
))); ?>
