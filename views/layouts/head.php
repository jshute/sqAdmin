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

self::$title = sq::config('admin/title');

?>
<script>var tinymcePath = '<?=$tinymce ?>';</script>