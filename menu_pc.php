<?php
session_start();
require('config.php');
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];


$req = $db->prepare("SELECT * FROM utilisateurs WHERE id = '{$id}'  ");
$req->execute();
$donnees = $req->fetch();
?>

        <div class="py-5" style="background-image:url('<?php echo $donnees['image_couverture'];?>'); background-size:cover; height:30vh;">
            <div class="mx-auto text-center">
                <img src="<?php echo $donnees['image_profil'];?>" alt="..." class="rounded-lg" width="85"> 
                <h2 class="text-white">
                <?php echo  $_SESSION['Auth']['pseudo'];?>
                </h2>
            </div>
        </div>
        <div class="list-group shadow">
            <a href="feed.php" class="list-group-item list-group-item-action rounded-0">
               Fil d'actualité
            </a>
            <a href="profil.php" class="list-group-item list-group-item-action rounded-0">
               Mon profil
            </a>
            <a href="logout.php" class="list-group-item list-group-item-action d-flex text-danger rounded-0">
               Déconnexion       
            </a>
        </div>
		