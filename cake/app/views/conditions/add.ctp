<?php // views/conditions/add.ctp

?>

<?php if (isset($conditions) && !empty($conditions)): ?>
<?php
$updateId = "EmailAddConditions";
$loadingId = "EmailAddConditionsLoader";
?>
<div class="mar10">
  <div class="header">Current Conditions</div>
  <div class="value" id="<?php echo $updateId; ?>">
    <?php echo $this->element('conditions', compact('updateId', 'loadingId')); ?>
  </div>
</div>
<div id="<?php echo $loadingId; ?>"></div>
<?php endif; ?>

<?php
$legend = 'Add Condition to ' . $email['Email']['title'];
echo $this->element('ajaxForm', compact('legend'));
?>
