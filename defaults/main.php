<?php

/**
 * Admin module defaults
 */

return [
	'admin' => [
		
		// Sidebar navigation in the form of title => url. Single entries format
		// to non-link section headings.
		'nav' => [
			'Manage',
			'Users' => 'users',
			'Logout' => 'users/logout'
		],
		
		// Title and logo shown in admin
		'title' => 'SQ Framework CMS',
		'logo' => false,
		
		// Require login for access to admin section. False is useful for dev
		// environments.
		'require-login' => true
	]
];

?>