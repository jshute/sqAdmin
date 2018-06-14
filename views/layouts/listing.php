<? $this->layout = 'admin/layouts/main' ?>

<? if (!sq::request()->isAjax): ?>

<h2><?=$model->getTitle(null, sq::request()->get('type')) ?></h2>
<span class="sq-list-count">
	<?=count($model) ?>
	<?=view::pluralize(count($model), 'Item') ?>
</span>

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
		])->remove(['page', 'where', 'value']);

		if (sq::request()->get('type')):
			$url->append(['type' => sq::request()->get('type')]);
		endif;

		echo '<a class="sq-action sq-'.$action.'-action" href="'.$url.'">'.$display.'</a>';
	endforeach ?>
</div>

<? endif ?>

<?=$content ?>

<?=sq::widget('pagination', ['model' => $model]) ?>