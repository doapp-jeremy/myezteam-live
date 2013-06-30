<?php // views/elements/modelIndex.ctp : renders the index page for a model
//$model : (required) : the model to render the index page for
//$isAjax : (optional) : if true, won't render the dropDown list
?>

<?php
$mL = low($model);
$mPL = Inflector::pluralize($mL);
$mP = Inflector::pluralize($model);
?>

<div class="<?php echo $mPL; ?> index">
	<?php if(!isset($isAjax) || ($isAjax === false)): ?>
	 <?php echo $this->element('modelDrops', compact('model', 'data')); ?>
	<?php endif; ?>
	<?php echo $this->element('modelMain', compact('model', 'data')); ?>
</div>
