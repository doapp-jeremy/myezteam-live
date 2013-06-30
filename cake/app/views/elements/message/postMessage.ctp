<?php // views/elements/message/postMessage.ctp
//$post : (required) :

?>

<?php
if (!isset($post['Post']))
{
  $post = array('Post' => $post);
}
?>

<?php
$title = '<span class="userMessageTitle">' . $post['Post']['User']['nameOrEmail'] . '</span> posted to ';
$title .= '<span class="topicMessageTitle">';
$title .= $html->link($post['Post']['Topic']['title'], array('controller' => 'topics', 'action' => 'posts', $post['Post']['topic_id']));
$title .= '</span>';
$title .= '<span class="mar10 date smallest">' . $time->niceShort($post['Post']['created']) . '</span>';

$style = array('width' => '800px');

$data = nl2br($post['Post']['text']);
$maxPostSize = 500;
if (strlen($data) > $maxPostSize)
{
  $data = nl2br(substr($data, 0, $maxPostSize)) . '...' . $html->link('View the rest of the post', array('controller' => 'posts', 'action' => 'posts', $post['Post']['id']));
}

$options = compact('title', 'data', 'style');

echo $this->element('dropDown', compact('options'));
?>
