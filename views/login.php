<?php

// Load checkmark image into assets folder
sq::asset('admin/checkmark.png');

self::$title = 'Admin | Login';
self::$head = '
	<link rel="apple-touch-icon-precomposed" href="'.$base.'assets/admin/touch-icon.png"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name ="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"/>
';

self::$favicon = sq::asset('admin/favicon.ico');
self::style(sq::asset('admin/login.css'));

?>
<form action="<?php echo $base?>admin/pages" method="post">
	<?php if (isset($_SESSION['sq-login-attempts']) && $_SESSION['sq-login-attempts'] > 0): ?>
		<div class="message">Your email or password is incorrect. Please try again.</div>
	<?php endif ?>
	<div class="login">
		<h2>Login</h2>
		<div class="remember">
			<input id="remember" type="checkbox" value="true" name="remember"/>
			<label for="remember">Remember me</label>
		</div>
		<input type="text" autocorrect="off" autocapitalize="off" placeholder="Username" name="username"/>
		<input type="password" placeholder="Password" name="password"/>
		<input type="hidden" name="action" value="login"/>
		<input type="submit" title="Login" name="button" value="&gt;"/>
	</div>
</form>