<?php
require_once('../config.php');
require_once('../fonctions/fonction.php');

session_start();
$id = $_SESSION['Auth']['id'];

$feed = getFeed($db, $id);
vueNotification($db, $id);
AllvueNotification($db, $id);


?>
<?php if(empty($feed)){?>
<div class="alert alert-warning" role="alert">
  Votre fil d'actualité est vide.
</div>
<?php }else{?>

<?php foreach($feed as $feed): 

$feed_id = $feed['feed_id'];
$feed_membre_id = $feed['mid'];

$video = explode(".", $feed['image']);

$feed_like_list = $db->prepare("SELECT * FROM feed_like where feed_id = $feed_id order by id  DESC");
$feed_like_list->execute();
$feed_like_list = $feed_like_list->fetchAll();

$req = $db->query("SELECT COUNT(*) id FROM commentaires where feed_id= {$feed_id}");
$countcom = $req->fetch();
$req->closeCursor();
$nbcountcom = $countcom['id'];


$nbcountlike = getCountLikeFeed($db, $feed_id);
$nbcountlikeuser = getCountLikeUser($db, $id, $feed_id);

  ?>

    <div id="carte" class="col-md-12 shadow pr-0 pl-0">
    <!-- Carte -->
    <div id="carte-body" class="mt-1 border-top border-bottom overflow-hidden">
        <!-- Header -->
                <div class="container">
                    <div class="row">
                        <div class="col-2 mt-3">
                        <img src="<?php echo $feed['image_profil'];?>" alt="..." class="shadow rounded-lg" width="45">
                        </div>
                        <div class="col-4 mt-3 text-left">
                          <?php echo $feed['pseudo_membre'];?>
                        </div>
                    </div>
                </div>
        <!-- /Header -->

        <!-- Carte corps -->
        <div class="p-2">
          <div>
          <?php if($video[1] == "mp4"){?>
        <video id="player" playsinline controls preload="none" onplay="stopActu()" onended="reprise()">
            <source src="<?php echo $feed['image'];?>" type="video/mp4" />
        </video>
        <?php }else{?>
          </div>
          <?php if($feed['image'] != "empty"){?>
          <div class="mt-2 rounded" id="image" style="background-image:url('<?php echo $feed['image'];?>'); "></div>
        <?php }}?>

        <p class="mt-3">
        <?php if($feed['description'] == ""){?>
          <br>
          <?php }else{?>
            <?php echo $feed['description'];?>
        <?php }?>
        </p>
        </div>
        <!-- /Corps -->

        <!-- Footer -->
        <div class="container mb-1 ">
            <div class="row">
                <div class="col-2">
                <?php if($nbcountlikeuser == 0){?>
                <form method="post" action="../feed.php" class="d-flex"> 
                    <input type="hidden" id="feed_id" value="<?php echo $feed['feed_id'];?>" name="feed_id">
                    <input type="hidden" id="id" value="<?php echo $id;?>" name="id">
                    <input type="hidden" id="membre_id" value="<?php echo $feed['mid'];?>" name="membre_id">
                    <button type="submit" class="btn btn-transparent text-decoration-none text-dark" name="Sendlike">
                      <i class=" mt-2 far fa-heart fa-lg"></i> 
                      <span class="badge badge-transparent <?php if($nbcountlike == 0){ echo "d-none";}?>">
                      <?php echo $nbcountlike;?>
                      </span>
                    </button>
                </form>
                <?php }else{?>
                  <form method="post" action="../feed.php">
                    <input type="hidden" value="<?php echo $feed['feed_id'];?>" name="feed_id">
                    <input type="hidden" value="<?php echo $id;?>" name="id">
                    <button type="submit" class="btn btn-transparent decoration-none text-danger" name="unlike">
                        <i class=" mt-2 fas fa-heart text-danger fa-lg"></i> 
                        <span class="badge badge-transparent text-dark <?php if($nbcountlike == 0){ echo "d-none";}?>">
                        <?php echo $nbcountlike;?>
                        </span>
                    </button>
                </form>
                <?php }?>
                </div>
                <div class="col-3 mt-2 text-left">
                <a class="text-dark text-decoration-none" href="feed_unique.php?id=<?php echo $feed['feed_id'];?>">
                    <i class="  mt-2 far fa-comment fa-lg"></i> 
                    <span class="badge badge-transparent">
                    <?php echo $nbcountcom;?>
                    </span>
                </a>
                </div>
                <div class="col-7 mt-2 text-right">
                <i id="feed_menu" onclick="stopActu()" data-toggle="modal" data-target="#image_<?php echo $feed['feed_id'];?>_<?php echo $feed['membre_id'];?>" class="mt-2 fas fa-ellipsis-v fa-lg"></i>
                </div>
            </div>
        </div>
      
        <p class="small">
          <?php if($nbcountlike == 0){?>
          <?php }else{?>
        <?php if($nbcountlike <= 1){?>
        <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id;?>"><?php echo $nbcountlike;?> personne aime</a> 
        <?php }else{?>
        <a onclick="stopActu()" data-toggle="modal" data-target="#feedlike_<?php echo $feed_id;?>"><?php echo $nbcountlike;?> personnes aiment</a> 
        <?php }}?>
        </p>
        <!-- /Footer -->
        <!-- <a class="stretched-link" href="feed_unique.php?id=<?php echo $feed_id;?>"></a> -->
    </div>
    <!-- Carte -->
    </div>

<!-- Modal like -->
<div class="modal fade p-0" id="feedlike_<?php echo $feed_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Les j'aimes</h5>
        <button onclick="reprise()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <ul class="list-group ">
      <?php foreach( $feed_like_list as $feed_like_list):?>
        <li class="list-group-item">
            <a href="profil_public.php?id=<?php echo $feed_like_list['membre_id'];?>" class="text-decoration-none text-dark">
            <?php echo $feed_like_list['membre_pseudo'];?>
          </a>
        </li>
        <?php endforeach;?>
      </ul>
      </div>
      <div class="modal-footer">
        <button onclick="reprise()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal menu-->
<div class="modal fade" id="image_<?php echo $feed['feed_id'];?>_<?php echo $feed['membre_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered border-0" role="document">
    <div class="modal-content">
      <div class="modal-header  border-0">
        <button onclick="reprise()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="list-group">
        <?php if($feed['membre_id'] === $_SESSION['Auth']['id']){?>
          <form method="post">
          <a name="test" href="delete_photo.php?id=<?php echo $feed['feed_id'];?>" class="list-group-item list-group-item-action rounded-0 text-danger">
            Supprimer
          </a>
          </form>
        <?php }?>
           </div>
      </div>
    </div>
  </div>
</div>


<?php endforeach;}?>