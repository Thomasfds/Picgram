<?php
require_once('config.php');

$req = $db->query("SELECT COUNT(*) id FROM utilisateurs");
$totalusers = $req->fetch();
$req->closeCursor();

$total_users = $totalusers['id'];
$test =  $total_users + 1.;
$dossier = $test.'_';

if(isset($_POST['inscription'])) {
	
	$username = htmlspecialchars($_POST['pseudo']);
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars ($_POST['mdp']);
	$password= password_hash($password, PASSWORD_DEFAULT);
	
	$messageErreur = "";
	if (empty($username)) {
		$messageErreur = "Le champ nom d'utilisateur est vide.";
	}
	if (empty($email)) {
		$messageErreur .= "Le champ email est vide.";
	}
	if (empty($password)) {
		$messageErreur .= "Le champ mot de passe est vide.";
	}
	
	if (!empty($messageErreur)) {
		echo $messageErreur;
	}

	else {
	
	$reqmail = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
	$reqmail->execute(array($email));
	$mailexist = $reqmail->rowcount();
	
	if ($mailexist == 1) {
	  header("Location: $domain/inscription.php");
	  }
		else {
			
		$username=;
		$password= password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO utilisateurs (pseudo, email, mdp) VALUES (:pseudo, :email, :mdp)";
	
		$query = $db->prepare($sql);
		$query->bindparam(':pseudo', $username);
		$query->bindparam(':email', $email);
		$query->bindparam(':mdp', $password);
	
		$query->execute();

		$req = $db->query("SELECT COUNT(*) id FROM utilisateurs");
		$countnotif = $req->fetch();
		$req->closeCursor();
		$nbcountnotif = $countnotif ['id'];

		$statut="amis";
		$sql = "INSERT INTO amis (membre_id, amis_membre_id, statut) VALUES (:membre_id, :amis_membre_id, :statut)";
	
		$query = $db->prepare($sql);
		$query->bindparam(':membre_id', $nbcountnotif);
		$query->bindparam(':amis_membre_id', $nbcountnotif);
		$query->bindparam(':statut', $statut);
	
		$query->execute();

		mkdir("images/".$dossier .$_POST['pseudo']);
		mkdir("images/".$dossier .$_POST['pseudo']."/profil");
		mkdir("images/".$dossier .$_POST['pseudo']."/profil/profil");
		mkdir("images/".$dossier .$_POST['pseudo']."/profil/couverture");
		mkdir("images/".$dossier .$_POST['pseudo']."/thumb");

		header("Location: $domain/index.php");

		
		}
	}
}
?>
	<?php require 'templates/header.php';?>

	<body class="bg-light" style="margin-bottom:50px">
		<div class=" p-2 bg-white text-center" id="myTab" role="tablist">
			<h1 class="text-center">Picgram</h1>
		</div>
		<main class="container pr-0 pl-0" id="content">
		<img src="paris-1836415_1920.jpg" class="img-fluid">

			<form class="mt-3 p-3" method="post">
					<div class="form-group">
						<input type="text" class="form-control rounded-pill" id="pseudo"  name="pseudo" aria-describedby="pseudo" placeholder="Entrer votre pseudo">
					</div>
					<div class="form-group">
						<input type="password" class="form-control rounded-pill" id="mdp" name="mdp" aria-describedby="mdp" placeholder="Entrer votre mot de passe">
					</div>

					<div class="form-group">
						<input type="email" class="form-control rounded-pill" id="email" name="email" aria-describedby="email" placeholder="Entrer votre email">
					</div>
					<button type="submit" class="btn btn-primary btn-block btn-sm rounded-pill" name="inscription">Inscription</button>
					<a class="btn btn-primary btn-block btn-sm rounded-pill" href="index.php">J'ai déjà un compte</a>
			</form>

		</main>
		<div class=" p-2 bg-white text-center mt-3 fixed-bottom" id="myTab" role="tablist">
			<p>Picgram</p>
		</div>
	</body>
</html>