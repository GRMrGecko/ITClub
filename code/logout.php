<?
//
// logout.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// The log out page.
//

databaseQuery("UPDATE users SET time=%d WHERE docid=%s", $_MGM['time'], $_MGM['user']['docid']);
setcookie("{$_MGM['CookiePrefix']}user_email", "", $_MGM['time'], $_MGM['CookiePath'], $_MGM['CookieDomain']);
setcookie("{$_MGM['CookiePrefix']}user_password", "", $_MGM['time'], $_MGM['CookiePath'], $_MGM['CookieDomain']);
header("location: ".generateURL("login"));
exit();
?>