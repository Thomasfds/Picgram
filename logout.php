<?php
// $domain ="http://79.95.96.27/instagram";
$domain ="http://instagram.test";

session_start();
session_destroy();
setcookie('auth', '', time() -3600, '/', $domain, true, true);
header('Location: index.php');
?>
