<?php
session_start();
require('config.php');
require('imagethumb.php');
require('includes/function_title.php');
require('fonctions/fonction.php');

// Si pas connecté
if (!isset($_SESSION['ouvert'])) {
  header("Location: $domain/index.php");
}

$page = "feed";
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];


$req = $db->prepare("SELECT * FROM utilisateurs WHERE id = '{$id}'  ");
$req->execute();
$donnees = $req->fetch();

vueNotification($db, $id);
allvueNotification($db, $id);
insertFeed($db, $id, $pseudo, $domain);
$nbcountnotif = getCountNotif($db, $id);
$notifs = getNotification($db, $id);
$damis = getDemandeAmos($db, $id);


if (isset($_POST['Sendlike'])) {
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

  // header('location:feed.php');
}


if (isset($_POST['unlike'])) {
  $id = $_POST['id'];
  $feed_id = $_POST['feed_id'];

  $sql = "DELETE FROM feed_like WHERE feed_id= $feed_id AND membre_id = $id";
  $db->exec($sql);

  $sql = "DELETE FROM notifications WHERE notification_feed_id= $feed_id AND notification_membre_id = $id";
  $db->exec($sql);
}



if (isset($_POST['accept'])) {
  $statut = "amis";
  $membre_id = $_POST['sender'];
  $amis_id = $_POST['id'];
  $demande_id = $_POST['demande'];

  $sql = "INSERT INTO amis (membre_id, amis_membre_id, statut) VALUES
	(:membre_id, :amis_membre_id, :statut)";
  $query = $db->prepare($sql);

  $query->bindparam(':membre_id', $amis_id);
  $query->bindparam(':amis_membre_id', $membre_id);
  $query->bindparam(':statut', $statut);

  $query->execute();

  $sql = "DELETE FROM demande_amis where demande_id = $demande_id";
  $db->exec($sql);
  header('refresh:0');
}
?>
<?php require_once 'templates/header.php'; ?>
<?php require('templates/menu.php'); ?>

<body class=" mb-5" style="padding-bottom:80px; padding-top:75px">

  <main role="main" class="container">

    <section class="container py-5 d-none d-md-block search-box">
      <input type="text" placeholder="Taper votre recherche" class="form-control rounded-pill mt-5">
      <div class="result"></div>
    </section>

    <?php if (isset($msgError)) { ?>
      <div class="alert alert-danger shadow" role="alert">
        <?php echo $msgError; ?>
      </div>
    <?php } ?>
    <?php if (isset($msgSuccess)) { ?>
      <div class="alert alert-success shadow" role="alert">
        <?php echo $msgSuccess; ?>
      </div>
    <?php } ?>


    <!-- Formulaire de publication -->
    <form method="post" enctype="multipart/form-data">
      <span class="d-block p-4 bg-dark text-white col-md-6  mx-auto">Publier</span>
      <textarea id="text" class="form-control col-md-6 mx-auto rounded-0" name="description" rows="4"></textarea>

      <span class="d-block p-2 bg-dark text-white col-md-6 mx-auto mb-5">
        <div class="container">
          <div class="text-center mx-auto col-6">
            <img id="blah" src="" style="display:none; ">
          </div>
          <div class="row">
            <div class="col-6 mt-2">
              <div class="element">
                <i id="upload" class="fa fa-camera"></i>
                <input id="file" type="file" onchange="readURL(this);" name="fichier">
              </div>
            </div>
            <div class="col-6 mt-2 text-right">
              <button type="submit" class="btn btn-primary btn-block" name="photo">Poster</button>
            </div>
          </div>
        </div>
      </span>


    </form>
    <!-- /Formulaire de publciation -->
    <div class="row">
      <div class="col-md-8 blog-main pr-0 pl-0" id="content"></div>

      <aside class="d-none d-md-block col-md-4 blog-sidebar">
        <div class="p-4 mb-3 rounded" id="menu"></div>
        <div class="p-4 mb-3 bg-light rounded">
          <h4 class="font-italic">Note :</h4>
          <div class="alert alert-warning mt-3 rounded-0" role="alert">
           Ce code ne sera plus mis à jour dès la date du 09/09/2020.
          </div>
        </div>

        <hr>
        <p class="text-center">
          PicGram © 2019, tout droit reservé.
        </p>
      </aside><!-- /.blog-sidebar -->

    </div><!-- /.row -->

  </main>

<?php require_once('templates/conversation.php');?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
  <script src="https://cdn.plyr.io/3.5.6/plyr.js"></script>
  <script>
    $('#content').load('templates/feed.php')
    $('#menu').load('menu_pc.php')

    lazyload();
  </script>
  <script src="assets/js/script.js"></script>

</body>

</html>