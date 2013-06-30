<?php // views/elements/topicFormFields.ctp : renders form fields for a topic

?>

<?php
echo $form->input('team_id');
//echo $form->input('event_id');
echo $form->input('title');
echo $form->input('Post.text', array('type' => 'textarea', 'rows' => 10));
?>
