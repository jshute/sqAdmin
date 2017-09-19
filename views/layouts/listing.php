<? $this->layout = 'admin/layouts/main' ?>

<? if (!sq::request()->isAjax): ?>

<h2><?=
	empty($model->options['title'])
		? ucwords($model->options['name'])
		: $model->options['title']
?></h2>
<span class="sq-list-count"><?=count($model) ?> Results</span>

<?=sq::widget('pagination', [
	'model' => $model
]) ?>

<div class="sq-actions sq-list-actions">
	<? foreach ($model->options['actions'] as $key => $val):
		$action = $val;
		$display = ucwords($action);
		if (!is_numeric($key)):
			$action = $key;
			$display = $val;
		endif;
		
		$url = sq::route()->current()->append([
			'action' => $action
		])->remove('page');
		echo '<a class="sq-action sq-'.$action.'-action" href="'.$url.'">'.$display.'</a>';
	endforeach ?>
</div>

<? endif ?>

<?=$content ?>

<?=sq::widget('pagination', ['model' => $model]) ?>