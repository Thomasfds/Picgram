<?php
session_start();
require('../config.php');
require('../fonctions/fonction.php');

$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];
$nbcountnotif = getCountNotif($db, $id); 
$notifs = getNotification($db, $id);
$damis = getDemandeAmos($db, $id);

?>

<?php if(empty($notifs)){?>
<div class="alert alert-warning" role="alert">
  Aucune notification pour le moment
</div>
<?php }?>

<ul class="list-group" >
<?php foreach($notifs as $notif):?>	
		<li class="list-group-item d-flex mb-3 <?php if($notif['notification_lu'] == 0){ echo "active";}?>  pl-0 pr-0 pl-md-3 pl-md-3">
			<div class="col-2">
				<?php if($notif['image']  != "empty"){?>
				<img src="<?php echo $notif['image'];?>" alt="..." class="rounded-lg" width="85"> 
				<?php }else{?>
				<img src="<?php echo $notif['image_profil'];?>" alt="..." class="rounded-lg" width="85"> 
				<?php }?>
			</div>

			<div class="col-12 col-md-8 mt-4 ml-4 ml-md-3">
			<a class="text-decoration-none text-dark p-2 " href="feed_unique.php?id=<?php echo $notif['feed_id'];?>"><?php echo $notif['notification_description'];?></a>
			</div>
			<div class="col-1">
			<?php if($notif['notification_lu'] == 0){?>
			<form method="post" class="mx-auto d-flex col-6 text-right">
			<input type="hidden" value="<?php echo $notif['notification_id'];?>" name="notif_id">
			<button type="submit" class="btn btn-dark" name="vue">Vue</button>
			</form>
			<?php }?>
			</div>
			<!-- <a class="stretched-link" href="feed_unique.php?id=<?php echo $notif['feed_id'];?>"></a> -->
		</li>
<?php endforeach;?>
</ul>

<hr>
<h5>Demande d'amis</h5>
<?php if(empty($damis)){?>
<div class="alert alert-warning" role="alert">
  Aucune demande d'amis pour le moment
</div>
<?php }?>
<?php foreach($damis as $damis):?>
<p><?php echo $damis['demande_pseudo'];?> vous à envoyez une invitation</p>
<form method="post">
	<button class="btn btn-link" type="submit" name="accept">Accepter</button>
	<input type="hidden" name="id" value="<?php echo $damis['demande_membre_id'];?>">
	<input type="hidden" name="sender" value="<?php echo $damis['demande_sender'];?>">
	<input type="hidden" name="demande" value="<?php echo $damis['demande_id'];?>">

	<button class="btn btn-link"  type="submit" name="reject">Refuser</button>
</form>
<?php endforeach;?>