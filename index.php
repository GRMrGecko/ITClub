<?
//
// index.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// The main code of the site.
//

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);

$_MGM = array();
$_MGM['version'] = "1";
$_MGM['title'] = "IT Club";
$_MGM['DBType'] = "MYSQLPDO"; // MYSQL, SQLITE.
$_MGM['DBPersistent'] = NO;
$_MGM['DBHost'] = "localhost";
$_MGM['DBUser'] = "itclub";
$_MGM['DBPassword'] = "";
$_MGM['DBName'] = "itclub"; // File location for SQLite.
$_MGM['DBPort'] = 0; // 3306 = MySQL Default, 5432 = PostgreSQL Default.
$_MGM['DBPrefix'] = "";
$_MGM['adminEmail'] = "admin@example.com";
require_once("db{$_MGM['DBType']}.php");

$_MGM['apiAuthentication'] = "putrandomstring";

putenv("TZ=US/Central");
$_MGM['time'] = time();
$_MGM['domain'] = $_SERVER['HTTP_HOST'];
$_MGM['domainname'] = str_replace("www.", "", $_MGM['domain']);
$_MGM['port'] = $_SERVER['SERVER_PORT'];
$_MGM['ssl'] = ($_MGM['port']==443);

if ($_SERVER['REMOTE_ADDR'])
	$_MGM['ip'] = $_SERVER['REMOTE_ADDR'];
if ($_SERVER['HTTP_PC_REMOTE_ADDR'])	
	$_MGM['ip'] = $_SERVER['HTTP_PC_REMOTE_ADDR'];
if ($_SERVER['HTTP_CLIENT_IP'])
	$_MGM['ip'] = $_SERVER['HTTP_CLIENT_IP'];
if ($_SERVER['HTTP_X_FORWARDED_FOR'])
	$_MGM['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];

$_MGM['installPath'] = substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME'])-strlen(end(explode("/", $_SERVER['SCRIPT_NAME']))));
if (!isset($_GET['d'])) {
	$tmp = explode("?", substr($_SERVER['REQUEST_URI'], strlen($_MGM['installPath'])));
	$tmp = urldecode($tmp[0]);
	if (substr($tmp, 0, 9)=="index.php")
		$tmp = substr($tmp, 10, strlen($tmp)-10);
	$_MGM['fullPath'] = $tmp;
} else {
	$tmp = $_GET['d'];
	if (substr($tmp, 0, 1)=="/")
		$tmp = substr($tmp, 1, strlen($tmp)-1);
	$_MGM['fullPath'] = $tmp;
}
if (strlen($_MGM['fullPath'])>255) error("The URI you entered is to large");
$_MGM['path'] = explode("/", strtolower($_MGM['fullPath']));

$_MGM['CookiePrefix'] = "";
$_MGM['CookiePath'] = $_MGM['installPath'];
$_MGM['CookieDomain'] = ".".$_MGM['domainname'];

function generateURL($path) {
	global $_MGM;
	return "http".($_MGM['ssl'] ? "s" : "")."://".$_MGM['domain'].(((!$_MGM['ssl'] && $_MGM['port']==80) || ($_MGM['ssl'] && $_MGM['port']==443)) ? "" : ":{$_MGM['port']}").$_MGM['installPath'].$path;
}

function hashPassword($password, $salt) {
	$hashed = hash("sha512", $salt.$password);
	for ($i=0; $i<10000; $i++) {
		$hashed = hash("sha512", $salt.hex2bin($hashed));
	}
	return $hashed;
}
function error($error) {
	echo $error."<br />\n";
}

connectToDatabase();

function getSetting($name) {
	$results = databaseQuery("SELECT value FROM settings WHERE name=%s", $name);
	if ($results==NULL) {
		return "";
	}
	$result = databaseFetchAssoc($results);
	return $result['value'];
}
function setSetting($name, $value) {
	$results = databaseQuery("SELECT value FROM settings WHERE name=%s", $name);
	if ($results==NULL || databaseRowCount($results)==0) {
		databaseQuery("INSERT INTO settings (name,value) VALUES (%s,%s)", $name, $value);
	} else {
		databaseQuery("UPDATE settings SET value=%s WHERE name=%s", $value, $name);
	}
}

if (isset($_COOKIE["{$_MGM['CookiePrefix']}user_email"])) {
	$result = databaseQuery("SELECT * FROM users WHERE email=%s AND level!=0", $_COOKIE["{$_MGM['CookiePrefix']}user_email"]);
	$user = databaseFetchAssoc($result);
	if ($user!=NULL && hash("sha512", $user['password'].$user['time'])==$_COOKIE["{$_MGM['CookiePrefix']}user_password"]) {
		$_MGM['user'] = $user;
	}
}

if (!isset($_MGM['user']) && $_MGM['path'][0]=="login") {
	require("code/login.php");
}
if (isset($_MGM['user']) && $_MGM['path'][0]=="logout") {
	require("code/logout.php");
}

if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][0]=="sidebar") {
	require("code/sidebar.php");
} else if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][0]=="users") {
	require("code/users.php");
} else if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][0]=="settings") {
	require("code/settings.php");
} else if (isset($_MGM['user']) && $_MGM['path'][0]=="members") {
	require("code/members.php");
} else if (isset($_MGM['user']) && $_MGM['path'][0]=="meetings") {
	require("code/meetings.php");
} else if (isset($_MGM['user']) && $_MGM['path'][0]=="announcements") {
	require("code/announcements.php");
} else if ($_MGM['path'][0]=="rsvp") {
	require("code/rsvp.php");
} else if ($_MGM['path'][0]=="api") {
	require("code/api.php");
}

$page = str_replace("..", "", $_MGM['fullPath']);
if ($page=="" || substr($page, strlen($page)-1, 1)=="/") {
	$page .= "index";
}

if (!file_exists("pages/".$page.".html")) {
	header("HTTP/1.0 404 Not Found");
	require_once("header.php");
	readfile("pages/404.html");
	require_once("footer.php");
	exit();
}

require_once("header.php");
readfile("pages/".$page.".html");
require_once("footer.php");
?>