<?php
require('config.php');
require('fonctions/fonction.php');

require('imagethumb.php');

session_start();
if(!isset($_SESSION['ouvert'])){
    header("Location: $domain/index.php");
}
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

$req = $db->prepare("SELECT * FROM utilisateurs WHERE id = '{$id}'  ");
$req->execute();
$donnees = $req->fetch();
$page_title = "Profil de ". $pseudo;

$feed_profil = $db->prepare("SELECT * FROM feed INNER JOIN utilisateurs WHERE feed.membre_id =$id AND utilisateurs.id = $id");
$feed_profil->execute();
$feed_profil = $feed_profil->fetchAll();

$image="images/" .$id . "_" .$pseudo . "/";
$directory = glob($image."{*.jpg,*.jpeg,*.png,*.gif,*.JPG,*.JPEG,*.PNG,*.GIF}", GLOB_BRACE);
$filecount = count($directory);



if(isset($_POST['update_picture_profil'])){

if(isset($_FILES['fichier']) && !empty($_FILES['fichier']) &&
$_FILES['fichier']['error'] == 0){


    $taille_max = 10 * 1024 * 1024;  

    $type_image = array(
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png'
    );

    $extension = pathinfo(
        $_FILES['fichier']['name'], 
        PATHINFO_EXTENSION);



        if(array_key_exists(strtolower($extension), $type_image)){

            
            if(in_array($_FILES['fichier']['type'],$type_image)){

                if($_FILES['fichier']['size'] <= $taille_max){
                   
					$nouveau_nom = $id. '_'. $pseudo . 'profil.' .$extension;
					$test = 'images/'.$id. '_'. $pseudo . '/profil/profil/'. $nouveau_nom;

                    if(file_exists('images/'.$id. '_'. $pseudo .'/profil/profil/'.$id. '_'. $pseudo .'profil.'.$extension)){
						unlink('images/'.$id. '_'. $pseudo .'/profil/profil/'.$id. '_'. $pseudo .'profil.'.$extension);

						// move_uploaded_file(
						// 	$_FILES['fichier']['tmp_name'],
						// 	  'images/'.$id. '_'. $pseudo .'/profil/' .$nouveau_nom
						// 	 );

							imagethumb(
								$_FILES['fichier']['tmp_name'],
							$thumb = 'images/'.$id. '_'. $pseudo .'/profil/profil/' .$nouveau_nom,
							150
							);

						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
							$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$sql = "UPDATE utilisateurs SET image_profil=:image_profil  WHERE id=:id";
							$query = $db->prepare($sql);
							$query = $db->prepare($sql);
							$query->bindparam(':id', $id);
							$query->bindparam(':image_profil', $thumb);
							$query->execute();
							header("Location: $domain/profil.php");
                    }
                    else
                    {
                        move_uploaded_file(
                           $_FILES['fichier']['tmp_name'],
                             'images/'.$id. '_'. $pseudo .'/profil/profil/' .$nouveau_nom
                        	);
                        
						

                        // imagethumb(
                        //     'fichier/' .$nouveau_nom,
                        //     'fichier/thumb/150_'. $nouveau_nom,
                        //     150
                        // );
                          
							$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
							$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$sql = "UPDATE utilisateurs SET image_profil=:image_profil  WHERE id=:id";
							$query = $db->prepare($sql);
							$query = $db->prepare($sql);
							$query->bindparam(':id', $id);
							$query->bindparam(':image_profil', $test);
							$query->execute();
							header("Location: $domain/profil.php");	

                            }
                
                }else
                {                 
                    $msgError= "Votre image ne doit pas dépasser 1Mo !";
                }

            }
            else
            {
                $msgError= "Erreur ! Seuls le type de fichiers est autorisé";

            }

        }
        else
        {
            $msgError= "Erreur ! Seuls le type de fichiers est autorisé";
        }
    }
}
if(isset($_POST['update_couverture'])){

	if(isset($_FILES['fichier']) && !empty($_FILES['fichier']) &&
	$_FILES['fichier']['error'] == 0){
	
	
		$taille_max = 10 * 1024 * 1024;  
	
		$type_image = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png'
		);
	
		$extension = pathinfo(
			$_FILES['fichier']['name'], 
			PATHINFO_EXTENSION);
	
	
	
			if(array_key_exists(strtolower($extension), $type_image)){
	
				
				if(in_array($_FILES['fichier']['type'],$type_image)){
	
					if($_FILES['fichier']['size'] <= $taille_max){
					   
						$nouveau_nom = $id. '_'. $pseudo . 'couverture.' .$extension;
						$test = 'images/'.$id. '_'. $pseudo . '/profil/couverture/'. $nouveau_nom;
	
						if(file_exists('images/'.$id. '_'. $pseudo .'/profil/couverture/'.$id. '_'. $pseudo .'couverture.'.$extension)){

							$files = glob('images/'.$id. '_' . $pseudo.'/profil/couverture/','/*');  
							foreach($files as $file) { 
								if(is_file($file))  
								// Delete the given file 
								unlink($file);  
							} 

						unlink('images/'.$id. '_'. $pseudo .'/profil/couverture/'.$id. '_'.$pseudo.'couverture.'.$extension
						);

						move_uploaded_file(
							$_FILES['fichier']['tmp_name'],
							  'images/'.$id. '_'. $pseudo .'/profil/couverture/' .$nouveau_nom
						 );

						 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sql = "UPDATE utilisateurs SET image_couverture=:image_couverture  WHERE id=:id";
								$query = $db->prepare($sql);
								$query->bindParam(':id', $id);
								$query->bindParam(':image_couverture', $test);
								$query->execute();
								header("Location: $domain/profil.php");

						}
						else
						{
	
							move_uploaded_file(
							   $_FILES['fichier']['tmp_name'],
								 'images/'.$id. '_'. $pseudo .'/profil/couverture/' .$nouveau_nom
							);
							
							
	
							// imagethumb(
							//     'fichier/' .$nouveau_nom,
							//     'fichier/thumb/150_'. $nouveau_nom,
							//     150
							// );
							  
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sql = "UPDATE utilisateurs SET image_couverture=:image_couverture  WHERE id=:id";
								$query = $db->prepare($sql);
								$query->bindparam(':id', $id);
								$query->bindparam(':image_couverture', $test);
								$query->execute();
								header("Location: $domain/profil.php");	
	
								}
					
					}else
					{                 
						$msgError= "Votre image ne doit pas dépasser 1Mo !";
					}
	
				}
				else
				{
					$msgError= "Erreur ! Seuls le type de fichiers est autorisé";
	
				}
	
			}
			else
			{
				$msgError= "Erreur ! Seuls le type de fichiers est autorisé";
			}
		}
	}

if(isset($_POST['update'])){
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE utilisateurs SET bio=:bio  WHERE id=:id";
	$query = $db->prepare($sql);
	$query = $db->prepare($sql);
	$query->bindparam(':id', $id);
	$query->bindparam(':bio', htmlspecialchars($_POST['bio']));
	$query->execute();
	header("Location: $domain/profil.php");	
}

?>
	<?php require 'templates/header.php';?>

	<body style="padding-bottom:80px; padding-top:74px">
	<?php require('templates/menu.php');?>

	<?php if(!isset($_GET['page'])){?>

<?php }?>
<?php if($_GET['page'] === 'modifier' OR $_GET['page'] === 'modifier_photo'){?>
		<!-- Menu Mobile -->
		<ul class="nav nav-tabs p-3  text-dark" id="myTab" role="tablist">
			<div class="container">
				<div class="row">
					<div class="col-8 d-flex">
						<a href="profil.php" class="text-dark">
						<i class="fas fa-times fa-2x"></i>
						</a>
					</div>
				</div>
			</div>
		</ul>

<?php }?>

		<main class="container p-0">

			<?php if(!isset($_GET['page'])){?>
		<div id="haut-profil" class="shadow text-dark">
				<div class="p-5" style="background-image:url('<?php echo $donnees['image_couverture']?>'); background-size:cover; height:40vh;">
					<div id="profil-picture" class="mx-auto text-center">
						<img src="<?php echo $donnees['image_profil']?>" alt="..." class="shadow rounded-lg" width="85"> 
						<h3 class="text-dark mb-5">
						<?php echo  $_SESSION['Auth']['pseudo'];?>
						</h3>
					</div>
				</div>

				<p class="mt-4 p-5 text-center">
					<?php echo $donnees['bio']?>
				</p>

				<div class="container mt-n5">
					<div class="row">
						<div class="col-6 text-center">
						<a href="profil_public.php?id=<?= $id;?>" class="text-decoration-none text-dark">
							<i class="fas fa-eye fa-3x"></i>
						</a>
					
						<p class="small">Voir le profil public</p>
						</div>
						<div class="col-6 text-center">
						
						<a href="profil.php?page=modifier" class="text-decoration-none text-dark">
							<i class=" fas fa-user-edit fa-3x"></i>
						</a>
						<p class="small">Modifier le profil</p>

						</div>
					</div>
				</div>
		</div>
				<ul class="nav nav-pills mb-3 mt-3" id="pills-tab" role="tablist">
					<li class="nav-item w-50 text-center">
						<a class="nav-link active rounded-0" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Fil d'actualité</a>
					</li>
					<li class="nav-item w-50 text-center">
						<a class="nav-link rounded-0" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Photos</a>
					</li>
				</ul>
<div class="tab-content" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	<p class="text-white p-3"><?= count($feed_profil)?> publications</p>
	<?php foreach($feed_profil as $feed_profil):
		$feed_id = $feed_profil['feed_id'];

		$req = $db->query("SELECT COUNT(*) id FROM commentaires where feed_id= {$feed_id}");
		$countcom = $req->fetch();
		$req->closeCursor();
		$nbcountcom = $countcom['id'];
		
		
		$req = $db->query("SELECT COUNT(*) id FROM feed_like where feed_id= {$feed_id}");
		$countlike = $req->fetch();
		$req->closeCursor();
		$nbcountlike = $countlike['id'];
		
		$req = $db->query("SELECT COUNT(*) id FROM feed_like where feed_id= {$feed_id} AND membre_id = $id");
		$countlikeuser = $req->fetch();
		$req->closeCursor();
		$nbcountlikeuser = $countlikeuser['id'];

		if(isset($_POST['like'])){
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO feed_like (feed_id, membre_id, membre_pseudo) VALUES (:feed_id, :membre_id, :membre_pseudo)";
			$query = $db->prepare($sql);
			$query->bindValue(':feed_id', $feed_id);
			$query->bindValue(':membre_id', $id);
			$query->bindValue(':membre_pseudo', $pseudo);

			$query->execute();
			header('refresh:0');
		}
		?>
	    <!-- Carte -->	
	<div id="carte" class="col-md-6 mx-auto shadow pr-0 pl-0">

    <div class="mt-1 border-top border-bottom p-3  border-bottom-0">
        <!-- Header -->
                <div class="container">
                    <div class="row">
                        <div class="col-2 mt-3">
                        <img src="<?php echo $feed_profil['image_profil'];?>" alt="..." class="rounded-lg" width="45">
                        </div>
                        <div class="col-4 mt-3 text-left">
                        <?php echo $feed_profil['pseudo_membre'];?>
                        </div>
                       
                    </div>
                </div>
        <!-- /Header -->

        <!-- Carte corps -->
        <div class="p-2">
          <div>
          <?php if($video[1] == "mp4"){?>
        <video id="player" playsinline controls preload="none" onplay="stopActu()" onended="reprise()">
            <source src="<?php echo $feed_profil['image'];?>" type="video/mp4" />
        </video>
        <?php }else{?>
          </div>
          <?php if($feed_profil['image'] != "empty"){?>
          <div class="mt-2" id="image" style="background-image:url('<?php echo $feed_profil['image'];?>'); "></div>
        <?php }}?>

        <p class="mt-3">
        <?php if($feed_profil['description'] == ""){?>
          <br>
          <?php }else{?>
            <?php echo $feed_profil['description'];?>
        <?php }?>
        </p>
        </div>
        <!-- /Corps -->

        <!-- Footer -->
        <div class="container mb-1 ">
            <div class="row">
                <div class="col-2">
                
                <form method="post" class="d-flex"> 
                <?php if($nbcountlikeuser == 0){?>
                    <input type="hidden" value="<?php echo $feed_profil['feed_id'];?>" name="feed_id">
                    <input type="hidden" value="<?php echo $id;?>" name="id">
                    <input type="hidden" value="<?php echo $feed_profil['mid'];?>" name="membre_id">
                    <button type="submit" class="btn btn-transparent decoration-none d-flex text-dark" name="like">
                    <i class=" mt-2 far fa-heart fa-lg "></i> 
                    <span class="badge badge-transparent <?php if($nbcountlike == 0){ echo "d-none";}?>">
                    <?php echo $nbcountlike;?>
                    </span>
                    </button>
                <?php }else{?>
                    <input type="hidden" value="<?php echo $feed_profil['feed_id'];?>" name="feed_id">
                    <input type="hidden" value="<?php echo $id;?>" name="id">
                    <button type="submit" class="btn btn-transparent decoration-none text-danger" name="unlike">
                        <i class=" mt-2 fas fa-heart text-danger fa-lg"></i> 
                        <span class="badge badge-transparent text-dark d-flex <?php if($nbcountlike == 0){ echo "d-none";}?>">
                        <?php echo $nbcountlike;?>
                        </span>
                    </button>
                <?php }?>
                </form>
                </div>
                <div class="col-3 mt-2 text-left">
                <a class="text-dark text-decoration-none" href="commentaires.php?id=<?php echo $feed_profil['feed_id'];?>">
                    <i class="  mt-2 far fa-comment fa-lg"></i> 
                    <span class="badge badge-transparent">
                    <?php echo $nbcountcom;?>
                    </span>
                </a>
                </div>
               
            </div>
        </div>
      
        <p class="small">
          <?php if($nbcountlike == 0){?>
            <br>
          <?php }else{?>
        <?php if($nbcountlike <= 1){?>
        <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id;?>"><?php echo $nbcountlike;?> personne aime</a> 
        <?php }else{?>
        <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id;?>"><?php echo $nbcountlike;?> personnes aiment</a> 
        <?php }}?>
        </p>
        <!-- /Footer -->
    </div>
	</div>
	<!-- Carte -->
		<?php endforeach;?>
	</div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">.
  <div class="container p-0">
	<div class="row">
		<?php 
		foreach($directory as $filenameThumb):
		$images[]=  basename($filenameThumb);
		?>
		<div class="col-6 mb-2">
			<?php if($filenameThumb != "images/".$id."_".$pseudo."/".$id."_".$pseudo."couverture.jpg" AND $filenameThumb != "images/".$id."_".$pseudo."/".$id."_".$pseudo."profil.jpg"){?>
			<img class="lazyload" data-src="<?php echo $filenameThumb;?>" src="<?php echo $filenameThumb;?>" alt="..." width="150px" height="150px">
			<?php }?>
		</div>
		<?php endforeach;?>
	</div>
	</div>
	</div>
 
</div>


			</div>
		</div>


<?php }?>
<?php if($_GET['page'] === 'modifier'){?>
	<form method="post">
		<div class="text-center mt-5">
			<img src="<?php echo $donnees['image_profil']?>" alt="Avatar" class="avatar"><br>
			<a href="profil.php?page=modifier_photo">Modifier ma photo de profil</a>
			<br>
			<a href="profil.php?page=modifier_couverture">Modifier ma photo de couverture</a>
		</div>

		<div class="mt-5">
			<label>
				Pseudo
			</label>
			<input type="text" class="form-control" readonly value="<?php echo  $_SESSION['Auth']['pseudo'];?>">
		</div>

		<div class="mt-5">
			<label>
				Bio
			</label>
			<textarea class="form-control" name="bio">
			<?php echo $donnees['bio']?>
			</textarea>
		</div>

		<button type="submit" name="update" class="btn btn-primary btn-block mt-3">Mettre à jour</button>
	</form>



<?php }?>
<?php if($_GET['page'] === 'modifier_photo'){?>
	<form method="post" class="mt-5" enctype="multipart/form-data" novalidate>
		<input type="file" name="fichier" class="form-control"><br>
		<button class="btn btn-primary btn-block" type="submit" name="update_picture_profil">Mettre à jour</button>
	</form>
<?php }?>
<?php if($_GET['page'] === 'modifier_couverture'){?>
	<form method="post" class="mt-5" enctype="multipart/form-data" novalidate>
		<input type="file" name="fichier" class="form-control"><br>
		<button class="btn btn-primary btn-block" type="submit" name="update_couverture">Mettre à jour</button>
	</form>
<?php }?>
<?php require_once('templates/conversation.php');?>

	</main>
	<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
<script>
	lazyload();
</script>
<script src="assets/js/script.js"></script>
	</body>
</html>