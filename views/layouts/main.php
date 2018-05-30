<?=$this->render('admin/layouts/head') ?>

<div class="container">
	<?=$this->render('admin/layouts/sidebar') ?>

	<div class="content">
		<?=$this->render('admin/layouts/breadcrumbs') ?>

		<div class="main-content">
			<section class="sq-admin-main">
				<?=$this->render('admin/layouts/flash') ?>
				<?=$content ?>
			</section>
			<footer>
				<small><?=sq::config('admin/byline') ?></small>
			</footer>
		</div>
	</div>
</div>
