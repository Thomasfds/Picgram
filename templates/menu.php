<?php 
session_start();
require('config.php');

require_once "includes/Mobile_Detect.php";
require('includes/function_title.php');
$detect = new Mobile_Detect;

$nbcountnotif = getCountNotif($db, $id); 
$notifs = getNotification($db, $id);
$damis = getDemandeAmos($db, $id);


$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

$req = $db->prepare("SELECT * FROM utilisateurs WHERE id = '{$id}'  ");
$req->execute();
$donnees = $req->fetch();

$feed ="Fil d'actualité";
$menu ="Menu";
?>
		<!-- Menu PC TOP -->
		<ul class="d-none d-md-block nav nav-tabs p-1 shadow fixed-top menu" id="myTab " role="tablist">
		<div class="container">
				<div class="row">

				<div class="col-1 p-0 mr-3 d-none d-md-block  text-right d-flex " >
						<a class="nav-link border-0  text-white" href="feed.php">
						<i class="fas fa-home fa-2x"></i>
						</a>
					</div>

					<div class="col-1 p-0 mr-3 d-none d-md-block  text-right d-flex " >
						<a class="nav-link border-0 d-flex text-white" data-toggle="modal" data-target="#notification" onclick="stopNotif()">
							<i class="fas fa-bell fa-2x"></i> 
							<div id="notif"></div> 
						</a>
						
					</div>
					
						<div class="col-6 col-md-7 text-center mt-1">
							<a class="nav-link border-0 text-white">
								<h5><?php echo $page_title;?></h5>
							</a>
						</div>

						<?php if($page === "feed"){?>
						<div id="refresh" class="col-1 col-md-1 text-center d-md-block d-none">
							<a class="nav-link text-white border-0" href="feed.php">
								<i class="fas fa-sync fa-2x"></i>
							</a>
						</div>
						<?php }?>

						<?php if($page != "menu"){?>
					<div class="col-2 col-md-1 text-left d-md-none">
							<a class="nav-link d-flex text-white border-0" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
								<i class="fas fa-bars fa-2x"></i>	
							</a>
						</div>
					<?php }?>

						
					
						<div class="col-2 col-md-1 d-md-block d-none text-right d-flex">
							<a class="nav-link text-white border-0 d-flex" data-toggle="modal" data-target="#conversation">
								<i class="far fa-comment fa-2x "></i>
							</a>
						</div>
						
					</div>
				</div>
		  </ul>
		<!-- /Menu PC TOP -->

		<!-- Menu Mobile -->
		<ul class="d-block d-sm-none nav nav-tabs p-1 shadow fixed-top menu" id="myTab " role="tablist">
		<div class="container">
				<div class="row">
					

					<!-- <div class="col-1 p-0 mr-3 d-none d-md-block  text-right d-flex mt-2" >
						<a class="nav-link border-0  text-white" data-toggle="modal" data-target="#notification" onclick="stopNotif()">
							<i class="fas fa-search fa-2x"></i>
						</a>
					</div> -->


					
						<div class="col-8 p-0 text-center mt-2">
							<a class="nav-link border-0 text-white">
								<h5><?php echo $page_title;?></h5>
							</a>
						</div>

						<?php if($_SERVER['PHP_SELF'] != "/profil.php"){?>
						<div class="col-1 mt-2 d-none text-right d-flex">
							<a class="nav-link text-white border-0" data-toggle="modal" data-target="#conversation">
								<i class="far fa-comment fa-2x"></i>
							</a>
						</div>
						<?php }else{?>
						<div class="col-1 mt-2 d-none text-right d-flex">

							<a  id="buttonMenu" class="nav-link text-white border-0" >
								<i class="fas fa-bars fa-2x" ></i>
							</a>

							<a id="closeMenu" class="nav-link text-white border-0 " >
							<i class="fas fa-times fa-2x"></i> 
							</a>
							
						</div>
						<?php }?>
						
					</div>
				</div>
		  </ul>
		<!-- /Menu Mobile -->

				<!-- Menu Mobile -->
	<ul class="nav  d-block d-sm-none nav-tabs p-0 shadow fixed-bottom menu" id="myTab " role="tablist">
		<div class="container">
				<div class="row">

					<div class="col-4 d-none d-md-block d-flex mt-2 pt-0 pb-0" >
						<a class="nav-link border-0  text-white" href="feed.php">
						<i class="fas fa-home fa-2x"></i>
						</a>
					</div>
					
						<div class="col-4 text-center mt-2 pt-0 pb-0">
							<a class="nav-link border-0 text-white d-flex" href="notification.php">
								<i class="fas fa-bell fa-2x d-flex"></i>
								<div id="notif_2"></div> 
							</a>
						</div>

						<div class="col-4 d-md-block d-none d-flex mt-2 pt-0 pb-0 ">
							<a class="nav-link text-white border-0 text-right" href="profil.php">
								<img src="<?= $donnees['image_profil']?>" height="40" class="rounded-pill"> 
							</a>
						</div>
						
					</div>
				</div>
		  </ul>
		<!-- /Menu Bottom Mobile -->



		<!-- Modal notif -->
<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Vos notifications et demande d'amis</h5>
        <button onclick="reprise()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pl-0 pr-0">
		<form method="post" class="mx-auto d-flex col-12 text-right mb-3">
				<input type="hidden" value="<?php echo $notif['notification_id'];?>" name="notif_id">
		<button type="submit" class="btn btn-dark" name="allVue">Marquer tout comme lue</button>
		</form> 
		<div id="notification_test_b">

		</div>
  
      </div>
      <div class="modal-footer">
        <button onclick="reprise()" type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<div class="collapse" id="collapseExample">
  <div class="card card-body rounded-0">
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

</div>
</div>

		


