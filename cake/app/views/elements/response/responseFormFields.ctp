<?php // views/elements/response/responseFormFields.ctp : renders form fields for a reesponse

?>

<?php
echo $form->input('event_id', array('type' => 'hidden'));
echo $form->input('player_id', array('type' => 'hidden'));
echo $form->input('ip', array('type' => 'hidden'));
echo $form->input('response_type_id', array('type' => 'select', 'label' => 'RSVP'));
echo $form->input('comment', array('type' => 'textarea', 'rows' => 3));
?>