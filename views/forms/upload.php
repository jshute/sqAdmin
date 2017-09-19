<h2>Upload File</h2>
<?=form::open(['enctype' => 'multipart/form-data']) ?>
	<?=form::hidden('path', sq::request()->get('path')) ?>
	<input name="file" type="file"/>
	
	<div class="sq-actions sq-form-actions">
		<input class="sq-action sq-upload-action" type="submit" value="Upload"/>
		<a class="sq-cancel" href="<?=sq::route()->to([
			'module' => 'admin',
			'controller' => 'files',
			'model' => 'files'
		]) ?>">Cancel</a>
	</div>
<?=form::close() ?>