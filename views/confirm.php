<?php

$modelName = $model->options['name'];
$formUrl = $base.url::get('module').'/'.$modelName.'/'.url::get('action');

if (is_int(url::get('id'))) {
	$formUrl .= '/'.url::get('id');
} else {
	$formUrl .= '?id='.url::get('id');
}

?>
<section class="form confirm-form">
	<h2><?php echo ucwords(url::get('action')) ?></h2>
	<p>Are you sure you want to <?php echo url::get('action') ?> this item?</p>
	<form method="post" action="<?php echo $formUrl?>">
		<input class="destructive delete" type="submit" name="button" value="<?php echo ucwords(url::get('action')) ?>"/>
		<a class="action" href="<?php echo $base.'admin/'.url::get('controller').$modelName?>">Cancel</a>
	</form>
</section>