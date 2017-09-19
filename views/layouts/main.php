<?=$this->render('admin/layouts/head') ?>

<div class="container">
	<?=$this->render('admin/layouts/sidebar') ?>
	
	<div class="content">
		<?=$this->render('admin/layouts/breadcrumbs') ?>
		
		<div class="main-content">
			<section class="sq-admin-main">
				<?=$content ?>
			</section>
			<footer>
				<small>SQ Framework CMS. Part of the SQ Framework.</small>
			</footer>
		</div>
	</div>
</div>
