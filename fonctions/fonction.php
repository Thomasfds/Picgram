<?php

// Fonction Get des feeds
function getFeed($db, $id)
{

    $feed = $db->prepare("SELECT amis.*, utilisateurs.*, feed.membre_id as mid, feed.feed_id, feed.image, feed.description, feed.pseudo_membre FROM feed 
    INNER JOIN amis ON amis.membre_id = {$id} 
    AND amis.amis_membre_id = feed.membre_id  
    OR amis.membre_id = feed.membre_id 
    AND amis.amis_membre_id = {$id} 
    INNER JOIN utilisateurs ON utilisateurs.id = feed.membre_id  
    WHERE amis.statut = 'amis' OR feed.membre_id = {$id}  ORDER BY feed.feed_id DESC");
    $feed->execute();
    $feed = $feed->fetchAll();

    return $feed;
}

// Fonction Get les personnes qui aime le post

function getLikePostPerson($db, $feed_id)
{
    $feed_like_list = $db->prepare("SELECT * FROM feed_like where feed_id = $feed_id order by id  DESC");
    $feed_like_list->execute();
    $feed_like_list = $feed_like_list->fetchAll();

    return $feed_like_list;
}

function getFeedUnique($db, $feed_id)
{
    $req = $db->prepare("SELECT * FROM feed 
    INNER JOIN utilisateurs ON utilisateurs.id = feed.membre_id  
    WHERE feed_id = {$feed_id}");
    $req->execute();
    $donnees = $req->fetch();
    $membre_id = $donnees['membre_id'];
    return $donnees;
}


// Fonction comptage Like des feeds

function getCountLikeFeed($db, $feed_id)
{
    $req = $db->query("SELECT COUNT(*) id FROM feed_like where feed_id= $feed_id");
    $countlike = $req->fetch();

    $nbcountlike = $countlike['id'];
    return $nbcountlike;
}

// Fonction comptage Like feed par user

function getCountLikeUser($db, $id, $feed_id)
{
    $req = $db->query("SELECT COUNT(*) id FROM feed_like where feed_id= {$feed_id} AND membre_id = $id");
    $countlikeuser = $req->fetch();
    $nbcountlikeuser = $countlikeuser['id'];

    return $nbcountlikeuser;
}

// Insérer un commentaire 

function insertCommentaire($db, $id, $feed_id, $pseudo, $membre_id)
{
    if (isset($_POST['send'])) {
        $message = $_POST['commentaire'];

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO commentaires (message, feed_id, membre_id,  membre_pseudo) VALUES
      (:message, :feed_id, :membre_id,  :membre_pseudo)";
        $query = $db->prepare($sql);

        $query->bindparam(':message', $message);
        $query->bindparam(':feed_id', $feed_id);
        $query->bindparam(':membre_pseudo', $pseudo);
        $query->bindparam(':membre_id', $id);
        $query->execute();

        $type = "commentaire";
        $description = $pseudo . " à commenté votre post";

        if ($id != $membre_id) {

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
        }
    }

    return $query;
}


// Fonction Get nombre de notfication
function getCountNotif($db, $id)
{
    $req = $db->query("SELECT COUNT(*) notification_id FROM notifications WHERE notification_membre_id= {$id} AND notification_lu = '0'");
    $countnotif = $req->fetch();
    $req->closeCursor();
    $nbcountnotif = $countnotif['notification_id'];

    return $nbcountnotif;
}

// Fonction Get nombre de notfication
function getCountDemandeAmis($db, $id)
{
    $req = $db->query("SELECT COUNT(*) demande_id FROM demande_amis WHERE demande_membre_id= {$id}");
    $countnotif = $req->fetch();
    $req->closeCursor();
    $nbdemande = $countnotif['demande_id'];

    return $nbdemande;
}

// Fonction Get all notification
function getNotification($db, $id)
{
    $notifs = $db->prepare("SELECT * FROM notifications 
    INNER JOIN feed ON notifications.notification_feed_id = feed.feed_id  
    INNER JOIN utilisateurs ON utilisateurs.id = notifications.notification_sender
    WHERE notifications.notification_membre_id = $id ORDER by notifications.notification_id DESC");
    $notifs->execute();
    $notifs = $notifs->fetchAll();

    return $notifs;
}

// Fonction Get demande amis
function getDemandeAmos($db, $id)
{
    $damis = $db->prepare("SELECT * FROM demande_amis 
    WHERE demande_membre_id = $id ");
    $damis->execute();
    $damis = $damis->fetchAll();

    return $damis;
}

// Marquer vue une notification
function vueNotification($db, $id)
{
    if (isset($_POST['vue'])) {
        $vue = 1;
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE notifications SET notification_lu=:notification_lu  WHERE notification_id=:notification_id";
        $query = $db->prepare($sql);
        $query->bindparam(':notification_id', $_POST['notif_id']);
        $query->bindparam(':notification_lu', $vue);

        $query->execute();
        header("Refresh:0");
        return $query;
    }
}

// Marquer vue toutes les notifications
function allvueNotification($db, $id)
{
    if (isset($_POST['allVue'])) {
        $vue = 1;
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE notifications SET notification_lu=:notification_lu WHERE notification_membre_id= $id";
        $query = $db->prepare($sql);
        $query->bindparam(':notification_lu', $vue);

        $query->execute();
        header("Refresh:0");
        return $query;
    }
}

// Insérer une publication
function insertFeed($db, $id, $pseudo, $domain)
{
    if (isset($_POST['photo'])) {
        if (
            isset($_FILES['fichier']) &&
            $_FILES['fichier']['error'] == 0
        ) {


            $taille_max = 10 * 1024 * 1024;

            $type_image = array(
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'mp4' => 'video/mp4',

            );

            $extension = pathinfo(
                $_FILES['fichier']['name'],
                PATHINFO_EXTENSION
            );



            if (array_key_exists(strtolower($extension), $type_image)) {


                if (in_array($_FILES['fichier']['type'], $type_image)) {

                    if ($_FILES['fichier']['size'] <= $taille_max) {

                        $nouveau_nom = md5(uniqid()) . '.' . $extension;

                        if (file_exists('images/' . $nouveau_nom)) {
                            $msgError = "Votre image existe déjà";
                        } else {

                            move_uploaded_file(
                                $_FILES['fichier']['tmp_name'],
                                'images/' . $id . '_' . $pseudo . '/' . $nouveau_nom
                            );

                            copy(
                                'images/' . $id . '_' . $pseudo . '/' . $nouveau_nom,
                                $feed_insert = 'images/feed/' . $nouveau_nom
                            );


                            imagethumb(
                                'images/' . $id . '_' . $pseudo . '/' . $nouveau_nom,
                                $thumb = 'images/' . $id . '_' . $pseudo . '/thumb/' . '360_' . $nouveau_nom,
                                360
                            );

                            $testee = '360_' . $nouveau_nom;

                            copy(
                                'images/' . $id . '_' . $pseudo . '/' . $nouveau_nom,
                                $feed_insert = 'images/feed/' . $testee
                            );




                            $image = $feed_insert;
                            $description = htmlspecialchars($_POST['description']);

                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "INSERT INTO feed (image, membre_id, description,  pseudo_membre) VALUES
                                    (:image, :membre_id, :description, :pseudo_membre)";
                            $query = $db->prepare($sql);

                            $query->bindparam(':image', $image);
                            $query->bindparam(':membre_id', $id);
                            $query->bindparam(':description', $description);
                            $query->bindparam(':pseudo_membre', $pseudo);


                            $query->execute();
                            header("refresh:0");


                            $msgSuccess = "Votre image à bien été télécharger";
                        }
                    } else {

                        $msgError = "Votre image ne doit pas dépasser 1Mo !";
                    }
                } else {
                    $msgError = "Erreur ! Seuls le type de fichiers est autorisé";
                }
            } else {
                $msgError = "Erreur ! Seuls le type de fichiers est autorisé";
            }
        } else {
            if (!empty($_POST['description'] and isset($_FILES['fichier']))) {

                $empty = "empty";
                $description = strip_tags($_POST['description']);

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO feed (image, membre_id, description,  pseudo_membre) VALUES
              (:image, :membre_id, :description, :pseudo_membre)";
                $query = $db->prepare($sql);

                $query->bindparam(':image', $empty);
                $query->bindparam(':membre_id', $id);
                $query->bindparam(':description', $description);
                $query->bindparam(':pseudo_membre', $pseudo);
                $query->execute();
                header("location:$domain/feed.php");
            } else {
                $msgError = "Vous devez écrire un texte.";
            }
        }
    }
}

// Ajouter un amis
function ajoutAmis($db, $id, $profil_id, $pseudo_user)
{
    if (isset($_POST['demande'])) {
        $statut = "amis";
        $sql = "INSERT INTO demande_amis (demande_membre_id, demande_sender, demande_pseudo) VALUES
        (:demande_membre_id, :demande_sender, :demande_pseudo)";
        $query = $db->prepare($sql);

        $query->bindparam(':demande_membre_id', $profil_id);
        $query->bindparam(':demande_sender', $id);
        $query->bindparam(':demande_pseudo', $pseudo_user);

        $query->execute();
        header("location: profil_public.php?id=$profil_id");
    }
}

// Faire un like
function like($db, $pseudo, $id)
{
    if (isset($_POST['like'])) {
        $type = "like";
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

        header('location:feed.php');
    }
}

// Faire un dislike

function dislike($db, $id)
{
    if (isset($_POST['unlike'])) {
        $id = $_POST['id'];
        $feed_id = $_POST['feed_id'];
        $sql = "DELETE FROM feed_like WHERE feed_id= $feed_id AND membre_id = $id";
        $db->exec($sql);

        $sql = "DELETE FROM notifications WHERE notification_feed_id= $feed_id AND notification_membre_id = $id";
        $db->exec($sql);
    }
}

function createConversation($db, $destinataire_id, $message)
{
    if (isset($_POST['conversationStart'])) {
        $user_id = $_SESSION['Auth']['id'];
        $user = $db->prepare("SELECT * FROM utilisateurs WHERE id = $destinataire_id ");
        $user->execute();
        $user = $user->fetch();

        if ($user) {

            $conversation_verification = $db->prepare("SELECT * FROM conversations WHERE conversation_membre_a = $user_id AND conversation_membre_b = $destinataire_id ");
            $conversation_verification->execute();
            $conversation_verification = $conversation_verification->fetch();
            // die(var_dump($conversation_verification));

            if (!$conversation_verification) {
                // die('oui');
                $sql_conversation = "INSERT INTO conversations (conversation_membre_a, conversation_membre_b) VALUES
                (:conversation_membre_a, :conversation_membre_b)";
                $query_conversation = $db->prepare($sql_conversation);

                $query_conversation->bindparam(':conversation_membre_a', $user_id);
                $query_conversation->bindparam(':conversation_membre_b', $destinataire_id);

                $query_conversation->execute();

                $conversation_verification = $db->prepare("SELECT * FROM conversations WHERE conversation_membre_a = $user_id AND conversation_membre_b = $destinataire_id ");
                $conversation_verification->execute();
                $conversation_verification = $conversation_verification->fetch();
                // die($conversation_verification);

                if ($conversation_verification) {
                    $conversation_id = $conversation_verification['conversation_id'];

                    $sql_message = "INSERT INTO messages (pseudo, message, membre_id, membre_sender, conv_id) VALUES
                    (:pseudo, :message, :membre_id, :membre_sender, :conv_id)";
                    $query_message = $db->prepare($sql_message);

                    $query_message->bindparam(':pseudo', $_SESSION['Auth']['pseudo']);
                    $query_message->bindparam(':membre_id', $destinataire_id);
                    $query_message->bindparam(':membre_sender', $user_id);
                    $query_message->bindparam(':message', $message);
                    $query_message->bindparam(':conv_id', $conversation_id);

                    $query_message->execute();
                    header("location: conversation.php?id=$conversation_id");
                }
            } else {
                $conversation_id = $conversation_verification['conversation_id'];
                header("location: conversation.php?id=$conversation_id");
            }
        }
    }
}
