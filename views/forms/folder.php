<h2>Create Folder</h2>
<?=form::flash() ?>
<?=form::open(['enctype' => 'multipart/form-data']) ?>
	<?=form::hidden('path', sq::request()->get('path')) ?>
	<?=form::label('folder', 'Folder Name') ?>
	<?=form::text('folder') ?>

	<div class="sq-actions">
		<input class="sq-action sq-folder-action" type="submit" value="Create Folder"/>
		<a class="sq-cancel" href="<?=sq::route()->to([
			'controller' => 'files',
			'model' => 'files'
		]) ?>">Cancel</a>
	</div>
<?=form::close() ?>