<?php // views/elements/modelDrops.ctp : renders modelDrop elements
//$model : (required) : the model to render drops for
//$data : (required) : the model data

?>

<?php
$mL = low($model);
$mPL = Inflector::pluralize($mL);
$mP = Inflector::pluralize($model);
?>
<div id="<?php echo $mL; ?>List" class="leftSideBar">
  <div id="modelDrops">
  <h2><?php echo $mP; ?><span class="mar20 smallest"><?php echo $html->link('Add ' . $model, array('controller' => $mPL, 'action' => 'add')); ?></h2>
  <div class="dropActions mar10Top">
    <?php
    $useAjax = false;
    foreach ($data as ${$mL})
    {
      echo '<div id="' . $mL . ${$mL}[$model]['id'] .'DropHolder' . '" class="dropDownHolder">';
      echo $this->element($mL . '/' . $mL . 'Drop', compact($mL, 'useAjax'));
      echo '</div>';
      $useAjax = true;
    }
    ?>
    </div>
  </div>
  <div id="googleAd">
    <?php echo $this->element('ads', array('adId' => 2)); ?>
  </div>
  <div style="margin-left: 175px; padding-top: 7px;" >
    <?php echo $this->element('spottt'); ?>
  </div>
</div>
