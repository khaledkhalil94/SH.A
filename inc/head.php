<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Khaled Khalil">
	<title><?= isset($pageTitle) ? $pageTitle : "Sha"; ?></title>

	<link rel="shortcut icon" href="<?= BASE_URL; ?>images/SH.A_Logo-icon.jpg">
	<link rel="icon" type="image/png" href="<?= BASE_URL; ?>images/SH.A_Logo-icon.jpg" sizes="192x192">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL; ?>images/SH.A_Logo-icon.jpg">

	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/site.css" />
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/1.6.50/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/mainstylesheet.css" />
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/questions.css"/>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/user-profile.css"/>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/msg.styles.css"/>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>styles/admin.styles.css"/>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.js"></script>
	<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<script type='text/javascript' src="http://momentjs.com/downloads/moment-timezone-with-data-2010-2020.js"></script>
	<script type='text/javascript' src="<?= BASE_URL; ?>scripts/main-scripts.js"></script>
	<script type='text/javascript' src="<?= BASE_URL; ?>scripts/msgsjs.js"></script>
  <noscript>
      <meta http-equiv="refresh" content="0; url='/err.php?javascript=no'">
  </noscript>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-86727005-1', 'auto');
	  ga('send', 'pageview');
	</script>
</head>
<?php require_once("navbar.php"); ?>