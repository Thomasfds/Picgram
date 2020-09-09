<?php
session_start();
require('config.php');
require('fonctions/fonction.php');

$page ="notification";

$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

$notif = getNotification($db, $id);
vueNotification($db, $id);
AllvueNotification($db, $id);
$nbcountnotif = getCountNotif($db, $id); 
$notifs = getNotification($db, $id);
$damis = getDemandeAmos($db, $id);


?>
	<?php require 'templates/header.php';?>
	<body  style="margin-top: 76px; padding-bottom:50px">
    <?php include('templates/menu.php');?>

		<main class="container mb-5 pr-0 pl-0">

			<?php if(empty($notifs)){?>
				<div class="alert alert-warning" role="alert">
				Aucune notification pour le moment
				</div>
			<?php }?>
		<?php foreach($notifs as $notif):?>
		<div class="container-fluid <?php if($notif['notification_lu'] == 0){ echo "bg-light";}else{echo "bg-white";}?> mb-1 p-3">
			<div class="row">
				<div class="col-1">
				<?php if($notif['image']  != "empty"){?>
				<img src="<?php echo $notif['image'];?>" alt="..." class="rounded-lg" width="35"> 
				<?php }else{?>
				<img src="<?php echo $notif['image_profil'];?>" alt="..." class="rounded-lg" width="35"> 
				<?php }?>
				</div>
				<div class="col-9 ml-3">
				<a class="text-decoration-none text-dark p-2 " href="feed_unique.php?id=<?php echo $notif['feed_id'];?>"><?php echo $notif['notification_description'];?></a>
				</div>
				<div class="col-1 ml-n5">
				<?php if($notif['notification_lu'] == 0){?>
					<form method="post" class=" d-flex ">
					<input type="hidden" value="<?php echo $notif['notification_id'];?>" name="notif_id">
					<button type="submit" class="btn btn-dark" name="vue">Vue</button>
					</form>
				<?php }?>
				</div>
			</div>
		</div>
<?php endforeach;?>

		<h5 class="text-white">Demande d'amis</h5>
		<?php if(empty($damis)){?>
			<div class="alert alert-warning" role="alert">
			Aucune demande d'amis pour le moment
			</div>
		<?php }?>
	  <?php foreach($damis as $damis):
		?>
		<p><?php echo $damis['demande_pseudo'];?> vous à envoyez une invitation</p>
		<form method="post">
			<button class="btn btn-link" type="submit" name="accept">Accepter</button>
			<input type="hidden" name="id" value="<?php echo $damis['demande_membre_id'];?>">
			<input type="hidden" name="sender" value="<?php echo $damis['demande_sender'];?>">
			<input type="hidden" name="demande" value="<?php echo $damis['demande_id'];?>">

			<button class="btn btn-link"  type="submit" name="reject">Refuser</button>
		</form>
		<?php endforeach;?>

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
$('#content').load('templates/feed.php');
$('#menu').load('menu_pc.php');

lazyload();

</script>
<script src="assets/js/script.js"></script>
	</body>
</html>