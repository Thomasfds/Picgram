<?php
require('config.php');
$erreur = $_GET["invalid"];
if (!empty($_POST['pseudo']) && !empty($_POST['mdp'])) {

	$users = $db->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
	$users->bindParam(':pseudo', $_POST['pseudo']);
	$users->execute();
	$users = $users->fetch(PDO::FETCH_ASSOC);




	if (count($users) > 0 && password_verify($_POST['mdp'], $users['mdp'])) {
		session_start();
		$_SESSION['Auth'] = (array)$users;
		$_SESSION['ouvert'] = "true";
		setcookie('auth', $users['id'] . '-----' . sha1($users['pseudo'] . $users['mdp']), time() + 3600 * 24 * 3);
		header("Location: $domain/feed.php");
	} else {
		session_start();
		session_destroy();
		setcookie('auth', '', time() - 3600, '/', 'libreplay.fr', true, true);
		header("Location: $domain/connexion.php?invalid=true");
	}
}

?>
<?php require 'templates/header.php'; ?>

<body class="bg-light" style="margin-bottom:50px">
	<div class=" p-2 bg-white text-center" id="myTab" role="tablist">
		<h1 class="text-center">Picgram</h1>
	</div>
	<main class="container pr-0 pl-0" id="content">

		<div class="text-center">
			<img src="sandburg-4313223_1920.jpg" class="mt-5 mb-5" height="350px">
		</div>

		<?php if ($erreur && $erreur == true) : ?>
			<div class="alert alert-warning">
				Votre nom d'utilisateur ou mot de passe est invalide.
			</div>
		<?php endif; ?>

		<form class="mt-2 p-3 mb-3" method="post">
			<div class="form-group">
				<input type="text" class="form-control rounded-pill" id="pseudo" name="pseudo" aria-describedby="pseudo" placeholder="Entrer votre pseudo">
			</div>
			<div class="form-group">
				<input type="password" class="form-control rounded-pill" id="mdp" name="mdp" aria-describedby="mdp" placeholder="Entrer votre mot de passe">
			</div>

			<button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Connexion</button>
			<a class="btn btn-primary btn-sm btn-block rounded-pill" href="inscription.php">Inscription</a>
		</form>
	</main>
	<div class=" p-2 bg-white text-center mt-3 fixed-bottom" id="myTab" role="tablist">
		<p>Picgram</p>
	</div>
</body>

</html>