<?php

$modelName = $model->options['name'];
$formUrl = $base.sq::request()->get('module').'/'.$modelName.'/'.sq::request()->get('action');

if (is_int(sq::request()->get('id'))) {
	$formUrl .= '/'.sq::request()->get('id');
} else {
	$formUrl .= '?id='.sq::request()->get('id');
}

?>
<section class="form confirm-form">
	<h2><?php echo ucwords(sq::request()->get('action')) ?></h2>
	<p>Are you sure you want to <?php echo sq::request()->get('action') ?> this item?</p>
	<form method="post" action="<?php echo $formUrl?>">
		<input class="destructive delete" type="submit" name="button" value="<?php echo ucwords(sq::request()->get('action')) ?>"/>
		<a class="action" href="<?php echo $base.'admin/'.sq::request()->get('controller').$modelName?>">Cancel</a>
	</form>
</section>