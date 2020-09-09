<?php
session_start();
require('config.php');
require('fonctions/fonction.php');

if (!isset($_SESSION['ouvert'])) {
  header("Location: $domain/index.php");
}



$feed_id = $_GET['id'];
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

vueNotification($db, $id);
allvueNotification($db, $id);
$feed_like_list = getLikePostPerson($db, $feed_id);
$donnees_feed = getFeedUnique($db, $feed_id);
$nbcountlike = getCountLikeFeed($db, $feed_id);
$nbcountlikeuser = getCountLikeUser($db, $id, $feed_id);

$page_title = "Publication de " . $donnees_feed['pseudo_membre'];
$membre_id = $donnees_feed['membre_id'];

insertCommentaire($db, $id, $feed_id, $pseudo, $membre_id);




if (isset($_POST['Sendlike'])) {
  $type = "like";
  $membre_id =  $_POST['id'];


  $description = $pseudo . " à aimé votre post";
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO feed_like (feed_id, membre_id, membre_pseudo) VALUES (:feed_id, :membre_id, :membre_pseudo)";
  $query = $db->prepare($sql);
  $query->bindValue(':feed_id', $_POST['feed_id']);
  $query->bindValue(':membre_id', $membre_id);
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

  header("location:feed_unique.php?id=$feed_id");
}

if (isset($_POST['unlike'])) {
  $ide = $_POST['id'];
  $feed_id = $_POST['feed_id'];

  $sql = "DELETE FROM feed_like WHERE feed_id= $feed_id AND membre_id = $ide";
  $db->exec($sql);

  $sql = "DELETE FROM notifications WHERE notification_feed_id= $feed_id AND notification_membre_id = $ide";
  $db->exec($sql);
}

$commentaires = $db->prepare("SELECT * FROM commentaires INNER JOIN utilisateurs ON commentaires.membre_id = utilisateurs.id where commentaires.feed_id = $feed_id order by commentaires.id  DESC");
$commentaires->execute();
$commentaires = $commentaires->fetchAll();

$countcom = $db->prepare("SELECT COUNT(*) id FROM commentaires WHERE commentaires.feed_id =$feed_id");
$countcom->execute();
$countcom = $countcom->fetch();

?>
<?php require 'templates/header.php'; ?>

<body class="mb-5" style="padding-top:20px; padding-bottom:50px">
  <?php require('templates/menu.php'); ?>
  <main class="container p-0">
    <div id="carte" class="col-md-6 mx-auto shadow pr-0 pl-0">
      <!-- Carte -->
      <div class="mt-5 border-top border-bottom p-3  border-bottom-0">
        <!-- Header -->
        <div class="container">
          <div class="row">
            <div class="col-2 mt-3">
              <img src="<?php echo $donnees_feed['image_profil']; ?>" alt="..." class="rounded-lg" width="45">
            </div>
            <div class="col-4 mt-3 text-left">
              <?php echo $donnees_feed['pseudo_membre']; ?>
            </div>

          </div>
        </div>
        <!-- /Header -->

        <!-- Carte corps -->
        <div class="p-2">
          <div>
            <?php if ($video[1] == "mp4") { ?>
              <video id="player" playsinline controls preload="none" onplay="stopActu()" onended="reprise()">
                <source src="<?php echo $donnees_feed['image']; ?>" type="video/mp4" />
              </video>
            <?php } else { ?>
          </div>
          <?php if ($donnees_feed['image'] != "empty") { ?>
            <div class="mt-2" id="image" style="background-image:url('<?php echo $donnees_feed['image']; ?>'); "></div>
        <?php }
            } ?>

        <p class="mt-3">
          <?php if ($donnees_feed['description'] == "") { ?>
            <br>
          <?php } else { ?>
            <?php echo $donnees_feed['description']; ?>
          <?php } ?>
        </p>
        </div>
        <!-- /Corps -->

        <!-- Footer -->
        <div class="container mb-1 ">
          <div class="row">
            <div class="col-2">

              <form method="post" class="d-flex">
                <?php if ($nbcountlikeuser == 0) { ?>
                  <input type="hidden" value="<?php echo $feed_id; ?>" name="feed_id">
                  <input type="hidden" value="<?php echo $id; ?>" name="id">
                  <input type="hidden" value="<?php echo $donnees_feed['membre_id']; ?>" name="membre_id">
                  <button type="submit" class="btn btn-transparent decoration-none d-flex text-dark" name="Sendlike">
                    <i class=" mt-2 far fa-heart fa-lg "></i>
                    <span class="badge badge-transparent <?php if ($nbcountlike == 0) {
                                                            echo "d-none";
                                                          } ?>">
                      <?php echo $nbcountlike; ?>
                    </span>
                  </button>
                <?php } else { ?>
                  <input type="hidden" value="<?php echo $feed_id; ?>" name="feed_id">
                  <input type="hidden" value="<?php echo $id; ?>" name="id">
                  <button type="submit" class="btn btn-transparent decoration-none text-danger" name="unlike">
                    <i class=" mt-2 fas fa-heart text-danger fa-lg"></i>
                    <span class="badge badge-transparent text-dark d-flex <?php if ($nbcountlike == 0) {
                                                                            echo "d-none";
                                                                          } ?>">
                      <?php echo $nbcountlike; ?>
                    </span>
                  </button>
                <?php } ?>
              </form>
            </div>
            <div class="col-3 mt-2 text-left">
              <a class="text-dark text-decoration-none" href="commentaires.php?id=<?php echo $donnees_feed['feed_id']; ?>">
                <i class="  mt-2 far fa-comment fa-lg"></i>
                <span class="badge badge-transparent">
                  <?php echo $nbcountcom; ?>
                </span>
              </a>
            </div>

          </div>
        </div>

        <p class="small">
          <?php if ($nbcountlike == 0) { ?>
            <br>
          <?php } else { ?>
            <?php if ($nbcountlike <= 1) { ?>
              <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id; ?>"><?php echo $nbcountlike; ?> personne aime</a>
            <?php } else { ?>
              <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id; ?>"><?php echo $nbcountlike; ?> personnes aiment</a>
          <?php }
          } ?>
        </p>
        <!-- /Footer -->
      </div>
      <!-- Carte -->
    </div>
    <?php if ($countcom['id'] == '0') { ?>
      <div class="alert alert-warning mx-auto  min-vw-50 max-vw-100 rounded-0 mt-3" role="alert">
        Aucun commentaire
      </div>
    <?php } ?>
    <div class="list-group bg-light mx-auto mb-5 border-top-0">

      <?php foreach ($commentaires as $commentaires) : ?>

        <li class="list-group-item list-group-item-action rounded-0">
          <div class="d-flex w-100 justify-content-between">
            <img src="<?php echo $commentaires['image_profil'] ?>" alt="Avatar" class="avatar">
            <h5 class="mb-1"><?php echo $commentaires['pseudo'] ?></h5>
            <small><?php echo $commentaires['date'] ?></small>
          </div>
          <p class="mt-3">
            <?php echo $commentaires['message'] ?>
          </p>
        </li>
      <?php endforeach; ?>

    </div>

  </main>
  <!-- Modal like -->
  <div class="modal fade" id="feedlike_<?php echo $feed_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Les j'aimes</h5>
          <button onclick="reprise()" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group">
            <?php foreach ($feed_like_list as $feed_like_list) : ?>
              <li class="list-group-item">
                <a href="profil_public.php?id=<?php echo $feed_like_list['membre_id']; ?>" class="text-decoration-none text-dark">
                  <?php echo $feed_like_list['membre_pseudo']; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="modal-footer">
          <button onclick="reprise()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <form class="bg-white p-2 mt-n5 col-12 d-flex w-100" method="post">
    <input type="text" class="form-control rounded-0" name="commentaire">
    <button type="submit" class="col-2 btn btn-primary rounded-0" name="send">
      <i class="fas fa-paper-plane fa"></i>
    </button>
  </form>
  <?php require_once('templates/conversation.php');?>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>