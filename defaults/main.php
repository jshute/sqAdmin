<?php

/**
 * Admin module defaults
 */

return array(
	'admin' => array(
		
		// Sidebar navigation in the form of title => url. Single entries format
		// to non-link section headings.
		'nav' => array(
			'Manage',
			'Users' => 'users',
			'Logout' => 'users/logout'
		),
		
		// Require login for access to admin section. False is useful for dev
		// environments.
		'require-login' => true
	)
);

?>