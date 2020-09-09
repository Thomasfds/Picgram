<?php
// Gestion des erreurs
ini_set('display_errors',0);
ini_set('display_startup_errors', 0);

// Nom de domaine
$domain ="http://instagram.teste";

// Base de données
define('HOST', 'localhost');
define('BDD', 'picgram');
define('USERNAME', 'root');
define('PASSWORD', '');

try
{
	$db = new PDO('mysql:host='. HOST .';dbname='.BDD.'', USERNAME, PASSWORD, array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
	));
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

?>