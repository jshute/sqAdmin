<?php

self::$id = 'admin';
self::style('http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,700');
self::style(asset::load('admin/main.css'));
self::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
self::script(asset::load('admin/tinymce').'/jquery.tinymce.min.js');
self::script(asset::load('admin/main.js'));

self::$favicon = asset::load('admin/favicon.ico');
self::$head = '<meta name="viewport" content="initial-scale=1.0"/>';

if (url::request('controller')):
	$modelName = url::request('controller');
endif;

?>
<script>var tinymcePath = "<?php echo asset::path('admin/tinymce') ?>";</script>
<div class="container">
	<div class="sidebar">
		<header>
			<a href="<?php echo $base ?>admin" class="sq-logo">
				<h1>SQ Framework CMS</h1>
			</a>
		</header>
		<nav>
	<?php foreach (sq::config('admin/nav') as $name => $link):
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
			<li><a href="<?php echo $base?>admin">Admin</a></li>
			<?php if (url::get('action')): ?>
				<li><a href="<?php echo $base?>admin/<?php echo $modelName?>"><?php echo ucwords($modelName)?></a></li>
				<li><span><?php echo ucwords(url::get('action'))?></span></li>
			<?php else: ?>
				<li><span><?php echo ucwords($modelName)?></span></li>
			<?php endif ?>
		</ul>
		<div class="main-content">
			<?php echo $content?>
			<footer>
				<small>SQ Framework CMS. Part of the SQ Framework.</small>
			</footer>
		</div>
	</div>
</div>