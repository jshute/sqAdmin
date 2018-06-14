<? $this->layout = 'admin/layouts/main' ?>

<header class="form-header">
	<h2>
		<?=ucwords(sq::request()->get('action')) ?>
		<?=$content->getTitle('singular', sq::request()->get('type')) ?>
	</h2>
	<? if (sq::request()->any('action') != 'create' && isset($content->options['preview'])): ?>
    	<a class="preview-link" href="<?=$content->getPreviewURL() ?>" target="_blank">Preview</a>
	<? endif ?>
</header>

<div class="sq-form-page sq-<?=$content->options['name'] ?>-form-page">
	<?=$content ?>
</div>