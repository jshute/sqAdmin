<? $this->layout = 'admin/layouts/main' ?>

<h2><?=
	empty($model->options['title'])
		? ucwords($model->options['name'])
		: $model->options['title']
?></h2>

<?=$content ?>