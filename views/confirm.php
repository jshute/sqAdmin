<h2><?=ucwords(sq::request()->get('action')) ?></h2>
<p>Are you sure you want to <?=sq::request()->get('action') ?> this item?</p>
<form method="post">
	<div class="sq-actions sq-form-actions">
		<input class="sq-action sq-confirm-action" type="submit" value="<?=ucwords(sq::request()->get('action')) ?>"/>
	<a class="sq-cancel" href="<?=sq::route()->to([
		'module' => 'admin',
		'model' => $model->options['name']
	]) ?>">Cancel</a>
</form>