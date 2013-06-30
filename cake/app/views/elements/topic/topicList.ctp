<?php // views/elements/topic/topicList.ctp : renders a list of topics

?>

<?php
if (!isset($topics))
{
  $topics = $topic;
}

if (!empty($topics['Topic']))
{
  $url = array('controller' => 'topics', 'action' => 'index', $topics['Team']['id']);
  $elementName = 'topics';
  $elementOptions = array('topics' => $topics['Topic']);
  echo $this->element('paginatorView', compact('url', 'elementName', 'elementOptions'));
}
else
{
  echo 'There are no topics';
}
?>
