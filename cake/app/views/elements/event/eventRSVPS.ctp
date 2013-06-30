<?php // views/elements/event/eventRSVPS.ctp : renders player RSVPs for an event
//$players : (required) : array of players
//$event : (required) : the event the rsvps are for
?>

<?php if (isset($isManager) && $isManager): ?>
  <h2>Click on a players RSVP to change their response</h2> 
<?php endif; ?>
<table class="playerList" cellspacing="5" cellpadding="5">
    <th><?php echo $paginator->sort('First Name', 'User.first_name');?>
      <br><?php echo $paginator->sort('Last Name', 'User.last_name');?>
    </th>
    <th><?php echo $paginator->sort('Player Type', 'name');?></th>
    <th>RSVP</th>
    <th>When</th>
    <th>Comment</th>
  <?php $i = 0; ?>
  <?php foreach ($players as $player): ?>
  <?php 
  $class = 'odd_row';
  if (($i % 2) == 0)
  {
    $class = 'even_row';
  }
  $eventId = $event['Event']['id'];
  $type = $player['PlayerType']['name'];
  ?>
    <tr class="<?php echo $class; $i++ ?>">
      <td class="playerType_<?php echo $type ?>">
        <?php echo $html->link($player['User']['nameOrEmail'], array('controller' => 'users', 'action' => 'view', $player['User']['id']), array('title' => $player['User']['email'])); ?>
      </td>
      <td class="playerType_<?php echo $type; ?>"><?php echo $type; ?></td>
      <?php if (!empty($player['Response'])): ?>
      <?php $responseType = $player['Response'][0]['ResponseType']['name']; ?>
      <td class="response_<?php echo $responseType; ?>">
        <?php
        $rsvp = $player['Response'][0]['ResponseType']['humanName'];
        if (isset($isManager) && $isManager)
        {
          echo $myAjax->linkAjax('Event', 'RSVP', $eventId, $event, array('event_id' => $eventId, 'player_id' => $player['Player']['id']), $rsvp);
        }
        else
        {
          echo $rsvp;
        }
        ?>
      </td>
      <td class="response_<?php echo $responseType; ?>"><?php echo $time->niceShort($player['Response'][0]['created']); ?></td>
      <td class="response_<?php echo $responseType; ?>"><?php echo $player['Response'][0]['comment']; ?></td>
      <?php endif; ?>
      <?php if (empty($player['Response'])): ?>
        <?php // player hasn't responded, if regular, they get default response, otherwise they are No Response ?>
        <?php if ($player['PlayerType']['name'] == 'regular'): ?>
        <td class="response_<?php echo $event['ResponseType']['name']; ?>">
          <?php
          $rsvp = Inflector::humanize($event['ResponseType']['name']);
          if (isset($isManager) && $isManager)
          {
            echo $myAjax->linkAjax('Event', 'RSVP', $eventId, $event, array('event_id' => $eventId, 'player_id' => $player['Player']['id']), $rsvp);
          }
          else
          {
            echo $rsvp;
          }
          ?>
        </td>
        <?php endif; ?>
        <?php if ($player['PlayerType']['name'] != 'regular'): ?>
          <?php
          $rsvp = 'No Response';
          if (isset($isManager) && $isManager)
          {
            echo $myAjax->linkAjax('Event', 'RSVP', $eventId, $event, array('event_id' => $eventId, 'player_id' => $player['Player']['id']), $rsvp);
          }
          else
          {
            echo $rsvp;
          }
          ?>
        <?php endif; ?>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
</table>
