<? $this->layout = 'admin/layouts/main' ?>

<h2>
	<?=ucwords(sq::request()->get('action')) ?>

	<? if (sq::request()->get('where') == 'type'): ?>
		<?=ucwords(sq::request()->get('value')) ?>
	<? elseif (sq::request()->get('type')): ?>
		<?=ucwords(sq::request()->get('type')) ?>
	<? else: ?>
		<?=substr_replace(ucwords(sq::request()->get('model')), '', strrpos(ucwords(sq::request()->get('model')), 's'), 1) ?>
	<? endif ?>
</h2>

<?=$content ?>