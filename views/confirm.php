<section class="form confirm-form">
	<h2><?php echo ucwords(sq::request()->get('action')) ?></h2>
	<p>Are you sure you want to <?php echo sq::request()->get('action') ?> this item?</p>
	<form method="post">
		<input class="destructive delete" type="submit" name="button" value="<?php echo ucwords(sq::request()->get('action')) ?>"/>
		<a class="action" href="<?php echo $base.'admin/'.sq::request()->get('controller').$modelName ?>">Cancel</a>
	</form>
</section>