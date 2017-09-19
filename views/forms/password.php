<h2>Change Password</h2>
<?=form::flash() ?>
<?=form::open() ?>
	<div class="form-block">
		<label for="admin-new-password">New Password</label>
		<input id="admin-new-password" type="password" name="password"/>
	</div>
	<div class="form-block">
		<label for="admin-confirm-password">Confirm Password</label>
		<input id="admin-confirm-password" type="password" name="confirm"/>
	</div>
	<div class="actions global-actions">
		<input type="submit" name="button" value="Save"/>
		<a class="sq-cancel" href="<?=$base ?>admin/users">Cancel</a>
	</div>
<?=form::close() ?>