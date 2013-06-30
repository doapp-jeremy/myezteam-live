<?php // views/elements/players.ctp : renders a list of players
//$players : (required) : the players to render
//$title : (optional) : the title of the page
//$type : (optional) : the player type
?>

  <?php if (isset($type)): ?>
  <h1><?php Inflector::humanize($type); ?></h1>
  <?php endif; ?>
  
  <?php if (empty($players)): ?>
  There are no <?php if (isset($type)) { echo Inflector::pluralize($type); } echo 'players.'; ?>
  <?php endif; ?>
  
  <?php if (!empty($players)): ?>
    <div class="sort biggest mar20 mar10Top">
      Sort by: 
      <span>
      <?php
      //if ($paginator->sortKey() != 'first_name')
      {
        echo $paginator->sort('First Name', 'User.first_name');
      }
      ?>
      </span>
      <span class="mar10">
      <?php
      //if ($paginator->sortKey() != 'last_name')
      {
        echo $paginator->sort('Last Name', 'User.last_name');
      }
      ?>
      </span>
      <span class="mar10">
      <?php
      //if ($paginator->sortKey() != 'type')
      {
        echo $paginator->sort('Player Type', 'player_type_id');
      }
      ?>
      </span>
      <?php if (isset($isAdmin) && $isAdmin): ?>
      <span class="mar10">
        <?php echo $paginator->sort('Last Login', 'last_login'); ?>
      </span>            
      <?php endif; ?>
  </div>
    <div class="mar10Top">
    <?php foreach($players as $player): ?>
  	 <?php echo $this->element('player', compact('player')); ?>
  	<?php endforeach; ?>
  	</div>
  <?php endif; ?>
