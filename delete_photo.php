<?php
session_start();
require('config.php');

$feed_id = $_GET['id'];
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

$req = $db->prepare("SELECT * FROM feed WHERE feed_id = '{$feed_id}'  ");
$req->execute();
$donnees = $req->fetch();



$explode = explode("/", $donnees['image']);
$explode_final = explode("_", $explode[2]);
$delete = $explode[0]. '/'. $id. '_' . $pseudo.  '/thumb/'. $explode[2];
$delete_final = "images/".$id."_".$pseudo."/".$explode_final[1];

// echo "<hr";
// echo "<hr";
// echo $donnees['image'];
// echo "<hr>";
// echo $delete;
// echo "<hr>";
// echo $donnees['image'];
// echo "<hr>";
// echo "unlink " .$delete_final;

 $sql = "DELETE FROM feed WHERE membre_id= $id AND feed_id = $feed_id";
 $db->exec($sql);

 $sql = "DELETE FROM notifications WHERE notification_feed_id = $feed_id";
 $db->exec($sql);

 $sql = "DELETE FROM commentaires WHERE feed_id = $feed_id";
 $db->exec($sql);

 unlink($delete);
 unlink($delete_final);
 unlink($donnees['image']);

 header('location:feed.php');
?>