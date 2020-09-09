<?php
require('../config.php');
require('../fonctions/fonction.php');

    $type="like";
    $membre_id =  $_POST['membre_id'];
    $feed_id =  $_POST['feed_id'];

    $description = $pseudo . " à aimé votre post";
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO feed_like (feed_id, membre_id, membre_pseudo) VALUES (:feed_id, :membre_id, :membre_pseudo)";
    $query = $db->prepare($sql);
    $query->bindValue(':feed_id', $_POST['feed_id']);
    $query->bindValue(':membre_id', $id);
    $query->bindValue(':membre_pseudo', $pseudo);
    $query->execute();



    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO notifications (notification_type, notification_description, notification_membre_id, notification_feed_id, notification_sender) VALUES (:notification_type, :notification_description, :notification_membre_id, :notification_feed_id, :notification_sender)";
    $query = $db->prepare($sql);
    $query->bindParam(':notification_type', $type);
    $query->bindParam(':notification_description', $description);
    $query->bindParam(':notification_membre_id', $membre_id);
    $query->bindParam(':notification_feed_id', $feed_id);
    $query->bindParam(':notification_sender', $id);

    $query->execute();

    // header('location:feed.php');

    die('ok');

    ?>