<section class="message">
	<h2><?php echo $message?></h2>
	<?php if (isset($description)): ?>
		<p><?php echo $description?></p>
	<?php endif ?>
	<a href="<?php echo $base.'admin/'.url::get('controller').$modelName?>">Back</a>
</section>