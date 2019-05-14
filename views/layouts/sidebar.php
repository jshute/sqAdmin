<div class="sidebar">
	<header>
		<a href="<?=sq::route()->to(['module' => 'admin']) ?>" class="sq-logo <?=sq::config('admin/logo') ? 'sq-logo-image' : 'sq-logo-default' ?>">
			<? if (sq::config('admin/logo')): ?>
				<img src="<?=sq::config('admin/logo') ?>" alt="<?=sq::config('admin/title') ?>"/>
			<? elseif (sq::config('admin/title')): ?>
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

		$href = $base.'admin/'.$link;
		if (sq::request()->isCurrent($href)):
			$active = 'active';
		endif;

		if ($link[0] == '/' || strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0):
			$href = $link;
		endif;

		echo '<a class="'.$active.'" href="'.$href.'">'.$name.'</a>';
	endif;
endforeach ?>

	</nav>
</div>