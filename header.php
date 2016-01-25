<?
//
// index.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// The site header.
//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Calhoun IT Club</title>
	<link rev="made" href="mailto:james@coleman.io" />
	<meta name="Copyright" content="Copyright (c) 2015, Mr. Gecko's Media (James Coleman)" />
	<meta name="Author" content="James Coleman" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="<?=$_MGM['installPath']?>css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?=$_MGM['installPath']?>css/bootstrap-responsive.min.css" rel="stylesheet" />
	<script type="text/javascript" src="<?=$_MGM['installPath']?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=$_MGM['installPath']?>js/bootstrap.min.js"></script>
	
	<style>
	body {
		margin: 0px;
		padding-right: 0px;
		padding-left: 0px;
		background: #f8f8f8;
	}
	#wrapper {
		padding-left: 350px;
		transition: all 0.4s ease 0s;
	}
	#page-content-wrapper {
	  width: 100%;
	}
	#page-content-wrapper p {
		margin-left: 10px;
	}
	#sidebar-wrapper {
		margin-left: -350px;
		left: 350px;
		width: 350px;
		background: #e6e6e6;
		position: fixed;
		height: 100%;
		overflow-y: auto;
		z-index: 1000;
		transition: all 0.4s ease 0s;
	}
	.sidebar-nav {
		position: absolute;
		top: 0px;
		width: 350px;
		list-style: outside none none;
		margin: 0px;
		padding: 0px;
	}
	#sidebar-logo {
		width: 321px;
		height: 165px;
	}
	.sidebar-link {
		padding-top: 5px;
		padding-bottom: 5px;
		background: none;
		width: 100%;
	}
	.sidebar-link:hover {
		background: #dfdfdf;
	}
	.sidebar-link a {
		color: #000;
		font-size: 18pt;
		margin-left: 20px;
	}
	.sidebar-link a:link {
		text-decoration: none;
	}

	.sidebar-link a:visited {
		text-decoration: none;
	}

	.sidebar-link a:hover {
		text-decoration: underline;
	}

	.sidebar-link a:active {
		text-decoration: underline;
	}
	.content-header {
		height: auto;
		background-color: #ededed;
	}
	.content-header h1 {
		font-size: 32px;
		color: #000;
		display: block;
		margin: 0px 20px 20px;
		line-height: normal;
		border-bottom: medium none;
	}
	@media only screen and (max-device-width : 800px) {
		#wrapper {
			padding-left: 0px;
		}
		#sidebar-wrapper {
			margin-left: 0px;
			left: 0px;
			width: 100%;
			background: #e6e6e6;
			position: relative;
			height: auto;
			overflow-y: visible;
			z-index: auto;
			transition: all 0.4s ease 0s;
		}
		.sidebar-nav {
			position: relative;
			top: 0px;
			width: 100%;
			list-style: outside none none;
			margin: 0px;
			padding: 0px;
		}
		#sidebar-logo {
			max-width: 100%;
			width: auto;
			height: auto;
		}
		.sidebar-link a {
			font-size: 14pt;
			margin-left: 10px;
		}
		.content-header h1 {
			font-size: 22px;
		}
	}
	</style>
</head>

<body>
<div id="wrapper">
	<nav id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li class="sidebar-brand">
				<a href="<?=$_MGM['installPath']?>"><img src="<?=$_MGM['installPath']?>logo.png" alt="logo" id="sidebar-logo" /></a>
			</li>
			<?
			$results = databaseQuery("SELECT * FROM `sidebar` ORDER BY `order`");
			while ($result = databaseFetchAssoc($results)) {
				?><li class="sidebar-link"><a <?=(substr($result['url'], 0, 1)=="/" ? "" : "target=\"_blank\"")?> href="<?=htmlspecialchars($result['url'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?>"><?=htmlspecialchars($result['title'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></a></li><?
			}
			?>
			<?if (isset($_MGM['user'])) {?>
				<li><h3>Admin Stuff</h3></li>
				<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>members">Members</a></li>
				<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>meetings">Meetings</a></li>
				<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>announcements">Announcements</a></li>
				<?if ($_MGM['user']['level']==1) {?>
					<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>users/">User Management</a></li>
					<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>sidebar/">Sidebar Links</a></li>
					<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>settings/">Settings</a></li>
				<?}?>
				<li class="sidebar-link"><a href="<?=$_MGM['installPath']?>logout">Logout</a></li>
			<?}?>
		</ul>
	</nav>
	<div id="page-content-wrapper">