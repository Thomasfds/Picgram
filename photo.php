<?php
session_start();
require('config.php');
require('fonctions/fonction.php');

require('imagethumb.php');

if(!isset($_SESSION['ouvert'])){
    header("Location: $domain/index.php");
}

$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

if(isset($_FILES['fichier']) && !empty($_FILES['fichier']) &&
$_FILES['fichier']['error'] == 0){


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
        PATHINFO_EXTENSION);



        if(array_key_exists(strtolower($extension), $type_image)){

            
            if(in_array($_FILES['fichier']['type'],$type_image)){

                if($_FILES['fichier']['size'] <= $taille_max){
                   
                    $nouveau_nom = md5(uniqid()) . '.' .$extension;

                    if(file_exists('images/'.$nouveau_nom)){
                        $msgError= "Votre image existe déjà";
                    }
                    else
                    {

                        move_uploaded_file(
                           $_FILES['fichier']['tmp_name'],
                             'images/'.$id. '_'. $pseudo .'/' .$nouveau_nom
                        );
                        
                        copy('images/'.$id. '_'. $pseudo .'/' .$nouveau_nom,
                        $feed_insert = 'images/feed/'.$nouveau_nom);
						

                        imagethumb(
                            'images/'.$id. '_'. $pseudo .'/' .$nouveau_nom,
                            $thumb = 'images/'.$id. '_'. $pseudo .'/thumb/'. '360_' .$nouveau_nom,
                            360
                        );

                        $testee='360_' .$nouveau_nom;

                        copy('images/'.$id. '_'. $pseudo .'/' .$nouveau_nom,
                        $feed_insert = 'images/feed/'.$testee);

                     


                        $image = $feed_insert;
                        $description = $_POST['description'];
                          
                         
                          
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
                              header( "refresh:2;url=$domain/feed.php" );

                          
                            $msgSuccess= "Votre image à bien été télécharger";

                          
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
    }else{
        $msgError ="Aucun fichier sélectionné";  
}
?>
    <?php include('templates/header.php');?>

	<body class="bg-light">
    <?php include('templates/menu.php');?>


		<main class="container" >
      
        <?php if(isset($msgError)){?>
            <div class="alert alert-danger shadow" role="alert">
                <?php echo $msgError;?>
            </div>  
        <?php }?>
        <?php if(isset($msgSuccess)){?>
            <div class="alert alert-success shadow" role="alert">
                <?php echo $msgSuccess;?>
            </div>  
        <?php }?>  
              
                        <p id="apercu" style="display:none;">Un aperçu :</p>
                        <div id="card" class="w-100 mx-auto mt-1 border-top border-bottom shadow p-3 mb-5" style="display:none">
                <!-- Header -->
                            <div class="container">
                                <div class="row">
                                    <div class="col-2 mt-2">
                                    <img src="https://www.w3schools.com/howto/img_avatar.png" class="rounded-lg" width="45">
                                    </div>
                                    <div class="col-4 mt-3 text-left">
                                        Un pseudo
                                    </div>
                                </div>
                            </div>
                    <!-- /Header -->

                    <!-- Carte corps -->
                    <div class="p-2">
                    <?php if($video[1] == "mp4"){?>
                    <video id="player" playsinline controls preload="none" style="width:100%"  onplay="stopActu()" onended="reprise()">
                        <source src="<?php echo $feed['image'];?>" type="video/mp4" />
                    </video>
                    <?php }else{?>
                    <center>
                        <img id="blah" src=""  class="img-fluid w-100 h-100" style="display:none; height:40vh;   
                    background-size: cover;
                    background-position: center;
                    object-fit: fill;
                    object-position: center ;" height="288" >
                    </center>
                    <?php }?>

                    <p class="mt-3 p-3 <?php if($feed['description'] == ""){echo "d-none";}?>">
                        Une description
                    </p>
                    </div>
                    <!-- /Corps -->
                    <!-- Footer -->
                    <div class="container mb-3">
                        <div class="row">
                            <div class="col-2 mt-2"> 
                                <i class="far fa-heart"></i>                       
                            </div>
                            <div class="col-3 mt-2 text-left">
                        
                                <i class="far fa-comment"></i> 
                            </div>
                            <div class="col-7 mt-2 text-right">
                            <i id="feed_menu" class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- /Footer -->
                </div>
                <!-- Carte -->
                <img id="blah" src=""  class="img-fluid w-100 h-100" style="display:none">

                <form action="" method="post" enctype="multipart/form-data" class="mt-5 mx-auto text-center" novalidate>
                <div class="element text-center">
                <i id="upload" class="fa fa-camera mx-auto text-center"></i>
                    <input id="file" type="file" accept="image/*" name="fichier" onchange="readURL(this);">
                </div>
                    <textarea type="text" name="description" class="mt-3 form-control mb-3">Taper votre description</textarea>
					<button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
                </div>
		</main>
        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>

<script>
 function readURL(input) {
    $('#card').show();
     $('#blah').show();
     $('#apercu').show();
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

	</body>
</html>