<?php // views/elements/postForm.ctp : renders a post form

?>

<?php
$updater = 'topic' . $this->data['Post']['topic_id'] . 'PostList';
echo $this->element('ajaxForm', compact('updater'));
?>
