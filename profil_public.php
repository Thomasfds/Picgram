<?php
require_once('config.php');
require_once('fonctions/fonction.php');

require('imagethumb.php');

session_start();
if (!isset($_SESSION['ouvert'])) {
	header("Location: $domain/index.php");
}

$profil_id = $_GET['id'];
$id = $_SESSION['Auth']['id'];
$pseudo_user = $_SESSION['Auth']['pseudo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	ajoutAmis($db, $id, $profil_id, $pseudo_user);
	createConversation($db, $profil_id, $_POST['message']);
}

$req = $db->prepare("SELECT * FROM utilisateurs  WHERE id = {$profil_id} ");
$req->execute();
$donnees = $req->fetch();

$pseudo = $donnees['pseudo'];

$req = $db->prepare("SELECT * FROM amis  
WHERE membre_id = {$profil_id} AND amis_membre_id = {$id}
OR  
membre_id = {$id} AND amis_membre_id = {$profil_id}
");
$req->execute();
$donnees_amis = $req->fetch();


$req = $db->query("SELECT COUNT(*) demande_id FROM demande_amis  WHERE demande_sender = {$profil_id} AND demande_membre_id = {$id} OR demande_sender = {$id} AND demande_membre_id = {$profil_id}");
$countdemande = $req->fetch();
$demande = $countdemande['demande_id'];


$feed_profil = $db->prepare("SELECT * FROM feed INNER JOIN utilisateurs WHERE feed.membre_id =$profil_id AND utilisateurs.id = $profil_id");
$feed_profil->execute();
$feed_profil = $feed_profil->fetchAll();

$image = "images/" . $profil_id . "_" . $pseudo . "/";
$directory = glob($image . "{*.jpg,*.jpeg,*.png,*.gif,*.JPG,*.JPEG,*.PNG,*.GIF}", GLOB_BRACE);
$filecount = count($directory);

if (isset($_POST['annuler'])) {
	$sql = "DELETE FROM demande_amis WHERE demande_sender = $id AND demande_membre_id = $profil_id";
	$db->exec($sql);
	header("location: profil_public.php?id=$profil_id");
}

?>
<?php require 'templates/header.php'; ?>

<body style="padding-bottom:50px;">

	<?php if (!isset($_GET['page'])) { ?>
		<!-- Menu Mobile -->
		<ul class="nav nav-tabs p-3 menu" id="myTab" role="tablist">
			<div class="container">
				<div class="row">
					<div class="col-8 d-flex">
						<a href="feed.php" class="text-dark">
							<i class="fas fa-long-arrow-alt-left fa-2x "></i>
						</a>
						<h4 class="d-inline-block"> &nbsp;&nbsp; <?php echo $donnees['pseudo'] ?></h4>
					</div>
				</div>
			</div>
		</ul>

		</div>
		</div>
	<?php } ?>
	<?php if ($_GET['page'] === 'modifier' or $_GET['page'] === 'modifier_photo') { ?>
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

	<?php } ?>

	<main class="container p-0">

		<?php if (!isset($_GET['page'])) { ?>
			<div id="haut-profil" class="shadow text-dark">
				<div class="p-5" style="background-image:url('<?php echo $donnees['image_couverture'] ?>'); background-size:cover; height:40vh;">
					<div id="profil-picture" class="mx-auto text-center">
						<img src="<?php echo $donnees['image_profil'] ?>" alt="..." class="shadow rounded-lg" width="85">
						<h3 class="text-dark mb-5">
							<?php echo $donnees['pseudo'] ?>
						</h3>
					</div>
				</div>

				<p class="mt-4 p-5 text-center">
					<?php echo $donnees['bio'] ?>
				</p>
				<div class="container mt-n5">
					<div class="row">
						<div class="col-6 text-center <?php if ($profil_id == $id) : ?>d-none<?php endif; ?>">
							<?php if ($demande == 1) { ?>
								<form method="post">
									<button type="submit" name="annuler" class="btn btn-link btn-block text-dark mt-n2">
										<i class="fas fa-user-times fa-3x"></i>
									</button>
									<p class="small">Annuler la demande d'amis</p>
								</form>
							<?php } else { ?>

								<?php if (
									$donnees_amis['membre_id'] != $id and $donnees_amis['amis_membre_amis'] != $profil_id and $donnees_amis['statut'] != "amis"
								) { ?>
									<form method="post" class="d-block mt-n2">
										<button type="submit" name="demande" class="text-dark btn btn-link btn-block">
											<i class="fas fa-user-plus fa-3x"></i>
										</button>
										<p class="small">Ajouter en amis</p>
									</form>
								<?php } else { ?>

									<button class="btn btn-link btn-block text-dark mt-n2" disabled>
										<i class="fas fa-user-slash fa-3x"></i>
									</button>
									<p class="small">Retirer de la liste d'amis</p>
							<?php }
							} ?>
						</div>
						<div class="col-6 text-center <?php if ($profil_id == $id) : ?>d-none<?php endif; ?>">
							<button class="btn btn-link mt-n2" data-toggle="modal" data-target="#conversationStart">
								<i class="far fa-comment-dots fa-3x"></i>
							</button>
							<p class="small">Envoyer un message</p>

						</div>
					</div>
				</div>

				<div class='onesignal-customlink-container'></div>
				<hr>

				<ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
					<li class="nav-item col-6">
						<a class="nav-link active rounded-0" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Fil d'actualité</a>
					</li>
					<li class="nav-item col-6">
						<a class="nav-link rounded-0" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Photos</a>
					</li>
				</ul>
				<!-- TABS -->
				<div class="tab-content mt-5" id="pills-tabContent">
					<div class="tab-pane fade show active row" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

						<?php foreach ($feed_profil as $feed_profil) :
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

							if (isset($_POST['like'])) {
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

								<div class="mt-5 border-top border-bottom p-3  border-bottom-0">
									<!-- Header -->
									<div class="container">
										<div class="row">
											<div class="col-2 mt-3">
												<img src="<?php echo $feed_profil['image_profil']; ?>" alt="..." class="rounded-lg" width="45">
											</div>
											<div class="col-4 mt-3 text-left">
												<?php echo $feed_profil['pseudo_membre']; ?>
											</div>

										</div>
									</div>
									<!-- /Header -->

									<!-- Carte corps -->
									<div class="p-2">
										<div>
											<?php if ($video[1] == "mp4") { ?>
												<video id="player" playsinline controls preload="none" onplay="stopActu()" onended="reprise()">
													<source src="<?php echo $feed_profil['image']; ?>" type="video/mp4" />
												</video>
											<?php } else { ?>
										</div>
										<?php if ($feed_profil['image'] != "empty") { ?>
											<div class="mt-2" id="image" style="background-image:url('<?php echo $feed_profil['image']; ?>'); "></div>
									<?php }
											} ?>

									<p class="mt-3">
										<?php if ($feed_profil['description'] == "") { ?>
											<br>
										<?php } else { ?>
											<?php echo $feed_profil['description']; ?>
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
														<input type="hidden" value="<?php echo $feed_profil['feed_id']; ?>" name="feed_id">
														<input type="hidden" value="<?php echo $id; ?>" name="id">
														<input type="hidden" value="<?php echo $feed_profil['mid']; ?>" name="membre_id">
														<button type="submit" class="btn btn-transparent decoration-none d-flex text-dark" name="like">
															<i class=" mt-2 far fa-heart fa-lg "></i>
															<span class="badge badge-transparent <?php if ($nbcountlike == 0) {
																										echo "d-none";
																									} ?>">
																<?php echo $nbcountlike; ?>
															</span>
														</button>
													<?php } else { ?>
														<input type="hidden" value="<?php echo $feed_profil['feed_id']; ?>" name="feed_id">
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
												<a class="text-dark text-decoration-none" href="commentaires.php?id=<?php echo $feed_profil['feed_id']; ?>">
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
							</div>
							<!-- Carte -->
						<?php endforeach; ?>
					</div>
					<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">.
						<div class="container p-0">
							<div class="row">
								<?php
								foreach ($directory as $filenameThumb) :
									$images[] =  basename($filenameThumb);
								?>
									<div class="col-6 mb-2">
										<?php if ($filenameThumb != "images/" . $profil_id . "_" . $pseudo . "/" . $profil_id . "_" . $pseudo . "couverture.jpg" and $filenameThumb != "images/" . $profil_id . "_" . $pseudo . "/" . $profil_id . "_" . $pseudo . "profil.jpg") { ?>
											<img class="lazyload" data-src="<?php echo $filenameThumb; ?>" src="<?php echo $filenameThumb; ?>" alt="..." width="150px" height="150px">
										<?php } ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

				</div>
				<!-- /TABS -->
			</div>
			</div>
		<?php } ?>

		<?php require_once('templates/conversation.php'); ?>
		<form method="POST">
			<!-- Modal conversation -->
			<div class="modal fade" id="conversationStart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle">Démarrer une conversation</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<input type="text" class="form-control" name="message">
								<input type="hidden" name="user_id">
								<input type="hidden" name="destinataire_id">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
							<button type="submit" name="conversationStart" class="btn btn-primary">Envoyez</button>

						</div>
					</div>
				</div>
			</div>
		</form>

	</main>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
	<script>
		lazyload();
	</script>
	<script>
		/* Open the sidenav */
		function openNav() {
			document.getElementById("mySidenav").style.display = "block";
		}

		/* Close/hide the sidenav */
		function closeNav() {
			document.getElementById("mySidenav").style.display = "none";
		}
	</script>
</body>

</html>