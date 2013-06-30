<?php // views/elements/topics/ctp : renders topics
// $topics: topics to render
// $options: various options
?>

<?php
$baseId = rand();
if (isset($options))
{
  if (isset($options['baseId']))
  {
    $baseId = $options['baseId'];
  }
}
?>

<div id="<?php echo $baseId; ?>Topics" class="data mar10 mar20Top">
  <?php if (isset($title)): ?>
  <h3 id="<?php echo $baseId; ?>TopicsActionLeft"><?php echo $title; ?></h3>
  <?php endif; ?>

  <?php $i = 0; ?>
  <?php foreach ($topics as $topic): ?>
    <?php
    $titleClass = 'evenTopic';
    if (($i++ % 2) == 1)
    {
      $titleClass = 'oddTopic';
    }
    ?>
    <?php echo $this->element('topic', compact('topic', 'titleClass')); ?>
  <?php endforeach; ?>
</div>
