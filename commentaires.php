<?php
session_start();
require('config.php');

if(!isset($_SESSION['ouvert'])){
    header("Location: $domain/index.php");
}

$feed_id = $_GET['id'];

$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];
$message = $_POST['commentaire'];

$req = $db->prepare("SELECT * FROM feed  WHERE feed_id = '{$feed_id}'  ");
$req->execute();
$donnees = $req->fetch();

$membre_id = $donnees['membre_id'];

if(isset($_POST['send'])){
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

    $type="commentaire";
    $description = $pseudo . " à commenté votre post";

    if($id != $membre_id){

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

$commentaires = $db->prepare("SELECT * FROM commentaires INNER JOIN utilisateurs ON commentaires.membre_id = utilisateurs.id where commentaires.feed_id = $feed_id order by commentaires.id  DESC");
$commentaires->execute();
$commentaires = $commentaires->fetchAll();
?>
	<?php require 'templates/header.php';?>

    <body class="bg-light mb-5" oncontextmenu="return false" style="">
    
    <ul class="nav nav-tabs p-3  text-dark" id="myTab" role="tablist">
			<div class="container">
				<div class="row">
					<div class="col-4 d-flex">
						<a href="feed.php" class="text-dark">
						<i class="fas fa-long-arrow-alt-left fa-2x"></i>
						</a>
                    </div>
                    <div class="col-4">
						Commentaires
					</div>
                    <div class="col-4 text-right">
						<a href="feed.php" class="text-dark">
						<i class="fas fa-long-arrow-alt-left fa-2x"></i>
						</a>
					</div>
				</div>
			</div>
		</ul>

		<main class="container p-0" id="content">
            <div class="d-flex p-3">
            <h5><?php echo $donnees['pseudo_membre'];?></h5> &nbsp;&nbsp; à écrit
            </div>
            <p><?php echo $donnees['description'];?></p>
            <hr>

            <div class="list-group">
            <?php foreach( $commentaires as $commentaires): ?>

            <li class="list-group-item list-group-item-action rounded-0">
                <div class="d-flex w-100 justify-content-between">
                <img src="<?php echo $commentaires['image_profil']?>" alt="Avatar" class="avatar">
                <h5 class="mb-1"><?php echo $commentaires['pseudo']?></h5>
                <small><?php echo $commentaires['date']?></small>
                </div>
                <p class="mt-3">
                <?php echo $commentaires['message']?>
                </p>
            </li>
                <?php endforeach;?>
            </div>

        </main>
        <form class="bg-white p-2 fixed-bottom col-12 d-flex w-100" method="post">
            <input type="text" class="form-control rounded-0" name="commentaire">
            <button type="submit" class="col-1 btn btn-primary rounded-0" name="send">
            <i class="fas fa-paper-plane fa"></i>
            </button>
        </form>
        <?php require_once('templates/conversation.php');?>

        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>