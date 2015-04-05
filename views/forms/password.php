<section class="form edit-form">
	<h2>Change Password</h2>
	<form method="post" action="<?php echo $base?>admin/users/password/<?php echo $model->id?>">
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
			<a class="cancel form-cancel" href="<?php echo $base?>admin/users">Cancel</a>
		</div>
	</form>
</section>