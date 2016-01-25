<?
//
// login.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// The log in page.
//

$error = "";
if (isset($_REQUEST['login'])) {
	$email = (isset($_REQUEST['email']) ? trim($_REQUEST['email']) : "");
	$password = (isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "");
	
	$result = databaseQuery("SELECT * FROM users WHERE email=%s AND level!=0", $email);
	$user = databaseFetchAssoc($result);
	if ($user==NULL) {
		$error = "Invalid login credentials.";
	} else {
		$salt = substr($user['password'], 0, 12);
		$epassword = $salt.hashPassword($password,hex2bin($salt));
		if ($epassword!=$user['password']) {
			$error = "Invalid login credentials.";
		} else {
			databaseQuery("UPDATE users SET time=%d WHERE email=%s", $_MGM['time'], $email);
			setcookie("{$_MGM['CookiePrefix']}user_email", $email, $_MGM['time']+31536000, $_MGM['CookiePath'], $_MGM['CookieDomain']);
			setcookie("{$_MGM['CookiePrefix']}user_password", hash("sha512", $epassword.$_MGM['time']), $_MGM['time']+31536000, $_MGM['CookiePath'], $_MGM['CookieDomain']);
			header("location: ".generateURL("members"));
			exit();
		}
	}
}
require_once("header.php");
if (!empty($error)) {
	?><div style="color: #ff0000; font-weight: bold;"><?=$error?></div><?
}
?>
<form action="<?=generateURL("login")?>" method="POST">
<input type="hidden" name="login" value="true" />
<input type="email" placeholder="Email" name="email" /><br />
<input type="password" placeholder="Password" name="password" /><br />
<input type="submit" value="Login" class="btn" />
</form>
<?
require_once("footer.php");
exit();
?>