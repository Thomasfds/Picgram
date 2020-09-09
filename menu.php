<?php
session_start();
require('config.php');
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];
$page="menu";

$req = $db->prepare("SELECT * FROM utilisateurs WHERE id = '{$id}'  ");
$req->execute();
$donnees = $req->fetch();
?>
	<?php require 'templates/header.php';?>

	<body  style="margin-bottom:50px">
		<main class="container pr-0 pl-0">
        <div class="py-5" style="background-image:url('<?php echo $donnees['image_couverture'];?>'); background-size:cover; height:30vh;">
            <div class="mx-auto text-center">
                <img src="<?php echo $donnees['image_profil'];?>" alt="..." class="rounded-lg" width="85"> 
                <h2 class="text-white">
                <?php echo  $_SESSION['Auth']['pseudo'];?>
                </h2>
            </div>
        </div>
        <div class="list-group shadow">
            <a href="feed.php" class="list-group-item list-group-item-action">
               Fil d'actualité
            </a>
            <a href="profil.php" class="list-group-item list-group-item-action">
               Mon profil
            </a>
            <a href="notification.php" class="list-group-item list-group-item-action d-flex">
               Mes notifications 
               <div id="notif_2"></div>         
            </a>
            <a href="logout.php" class="list-group-item list-group-item-action d-flex text-danger">
               Déconnexion       
            </a>
        </div>
		</main>
        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>

<script src="https://cdn.plyr.io/3.5.6/plyr.js"></script>
<script>
const player = new Plyr(document.getElementById('player'));
	lazyload();
</script>
<script src="assets/js/script.js"></script>
	</body>
</html>