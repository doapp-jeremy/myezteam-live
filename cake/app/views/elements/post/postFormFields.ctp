<?php // views/elements/topicFormFields.ctp : renders form fields for a topic

?>

<?php
echo $form->input('user_id', array('type' => 'hidden'));
echo $form->input('topic_id', array('type' => 'hidden'));
echo $form->input('Post.text', array('type' => 'textarea', 'rows' => 10));
?>
