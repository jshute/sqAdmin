<?

$tinymce = sq::asset('admin/tinymce');

self::$id = 'admin';
self::style('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,700');
self::style(sq::asset('admin/main.css'));

self::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
self::script($tinymce.'/jquery.tinymce.min.js');
self::script(sq::asset('admin/main.js'));

self::$favicon = sq::asset('admin/favicon.ico');
self::$head = '<meta name="viewport" content="initial-scale=1.0"/>';

?>
<script>var tinymcePath = '<?=$tinymce ?>';</script>
<div class="container">
	<div class="sidebar">
		<header>
			<a href="<?=$base ?>admin" class="sq-logo">
				<h1>SQ Framework CMS</h1>
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
			if ($link[0] == '/'):
				$href = $link;
			endif;
			
			echo '<a class="'.$active.'" href="'.$href.'">'.$name.'</a>';
		endif;
	endforeach ?>
		</nav>
	</div>
	<div class="content">
		<ul class="breadcrumbs">
			<li id="show-nav"><a href="#show-nav">Show Nav</a></li>
			<li><a href="<?=$base?>admin">Admin</a></li>
			<? if (sq::request()->get('action')): ?>
				<li><a href="<?=$base?>admin/<?=$modelName?>"><?=ucwords($modelName) ?></a></li>
				<li><span><?=ucwords(sq::request()->get('action')) ?></span></li>
			<? else: ?>
				<li><span><?=ucwords($modelName) ?></span></li>
			<? endif ?>
		</ul>
		<div class="main-content">
			<?=$content ?>
			<footer>
				<small>SQ Framework CMS. Part of the SQ Framework.</small>
			</footer>
		</div>
	</div>
</div>
