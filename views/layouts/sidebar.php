<div class="sidebar">
	<header>
		<a href="<?=$base ?>admin" class="sq-logo <?=sq::config('admin/logo') ? 'sq-logo-image' : 'sq-logo-default' ?>">
			<? if (sq::config('admin/logo')): ?>
				<img src="<?=sq::config('admin/logo') ?>"/>
			<? endif ?>
			<? if (sq::config('admin/title')): ?>
				<h1 class="sq-logo-text"><?=sq::config('admin/title') ?></h1>
			<? endif ?>
		</a>
	</header>
	<nav>

<? foreach (sq::config('admin/nav') as $name => $link):
	if (is_int($name)):
		echo '<span>'.$link.'</span>';
	else:
		$active = 'inactive';
		
		if ($link == $modelName):
			$active = 'active';
		endif;
		
		$href = $base.'admin/'.$link;
		if ($link[0] == '/' || strpos($link, 'http://') === 0):
			$href = $link;
		endif;
		
		echo '<a class="'.$active.'" href="'.$href.'">'.$name.'</a>';
	endif;
endforeach ?>

	</nav>
</div>