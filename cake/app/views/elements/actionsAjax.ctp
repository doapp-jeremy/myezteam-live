<?php // views/elements/actionsAjax.ctp : renders actions with an AJAX link
//$actions: the actions to render
//$id: id base use to convert ajax call to toggle
//$options: array of ajax options
?>

<?php foreach ($actions as $title => $options): ?>
  <?php
  $ajaxOptions = array();
  if (isset($options['ajaxOptions']))
  {
    $ajaxOptions = $actions[$title]['ajaxOptions'];
  }
  ?>
  <li id="<?php echo $id . $title; ?>Action">
  <?php echo $ajax->link($title, $options['url'], $ajaxOptions); ?>
  </li>
<?php endforeach; ?>
