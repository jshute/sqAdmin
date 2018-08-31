<?

// Add id to body
self::$id = 'admin';

// jQuery
self::script(sq::asset('admin/vendor/jquery/jquery-3.3.1.min.js'));
sq::asset('admin/vendor/jquery/jquery-3.3.1.min.map');

// Drag and drop sorting
self::script(sq::asset('admin/vendor/html5sortable/html5sortable.min.js'));
sq::asset('admin/vendor/html5sortable/html5sortable.min.js.map');

// Editor
sq::asset('admin/vendor/trumbowyg');
self::script(sq::asset('admin/vendor/trumbowyg/trumbowyg.min.js'));
self::style(sq::asset('admin/vendor/trumbowyg/ui/trumbowyg.min.css'));

// Main Javascript
self::script(sq::asset('admin/main.js'));

// Mail styles and fonts
self::style('//fonts.googleapis.com/css?family=Open+Sans:300italic,400,700');
self::style(sq::asset('admin/main.css'));

// Meta
self::$favicon = sq::asset('admin/favicon.ico');
self::$head = '<meta name="viewport" content="initial-scale=1.0"/>';
self::$title = sq::config('admin/title');
