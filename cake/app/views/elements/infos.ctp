<?php // views/elements/infos.ctp : renders info elments
//$fields : (required) : array of fields to show info for
//$model : (required) : the model to display
//$data : (required) : the data to display
//$showEmtpy : (optional) : if true, will show the header for empty fields
?>

<div class="data">
<?php foreach($fields as $field): ?>
  <?php if ((isset($data[$model][$field]) && (strlen($data[$model][$field]) > 0)) || (isset($showEmpty) && $showEmpty)): ?>
  <div class="mar10">
    <div class="header"><?php echo Inflector::humanize($field); ?></div>
    <div class="value">
      <?php
      if (isset($data[$model][$field]))
      {
        if (is_array($data[$model][$field]))
        {
          echo implode('<br>', $data[$model][$field]);
        }
        else
        {
          echo $data[$model][$field];
        }
      }
      ?>
    </div>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
</div>