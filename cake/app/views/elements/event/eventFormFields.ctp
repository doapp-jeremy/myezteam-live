<?php // views/elements/event/eventFormFields.ctp : renders form fields for an event

?>

<?php
echo $form->input('cal_event_id', array('type' => 'hidden'));
echo $form->input('name');
echo $form->input('start');
echo $form->input('end');
echo $form->input('location', array('type' => 'textarea', 'rows' => 2));
?>

<?php //if ($action == 'add'): // repeat section?>
  <div>
  <span>Repeats <?php echo $form->select('times', $times, null, array(), false); ?> times</span>
  <span class="mar10"><?php echo $form->select('repeats', $repeats, 'week', array(), false); ?></span>
  </div>
<?php //endif; ?>

<?php
echo $form->input('description', array('type' => 'textarea', 'rows' => 3));
echo $form->input('response_type_id', array('label' => 'Default Response'));
?>
