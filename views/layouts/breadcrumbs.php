<ul class="breadcrumbs">
	<li id="show-nav"><a href="#show-nav">Show Nav</a></li>
	<li><a href="<?=$base ?>admin">Admin</a></li>
	<? if (sq::request()->get('action')): ?>
		<li><a href="<?=$base?>admin/<?=$modelName ?>"><?=ucwords($modelName) ?></a></li>
		<li><span><?=ucwords(sq::request()->get('action')) ?></span></li>
	<? else: ?>
		<li><span><?=ucwords($modelName) ?></span></li>
	<? endif ?>
</ul>