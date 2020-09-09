<?php
session_start();
require('config.php');
require('fonctions/fonction.php');

if(!isset($_SESSION['ouvert'])){
    header("Location: $domain/index.php");
}



$feed_id = $_GET['id'];
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

vueNotification($db, $id);
allvueNotification($db, $id);


$page_title = "Publication de " .$donnees_feed['pseudo_membre'];
$membre_id = $donnees_feed['membre_id'];

try{
  if(isset($_REQUEST["term"])){
      // create prepared statement
      $sql = "SELECT * FROM utilisateurs WHERE pseudo LIKE :term";
      $stmt = $db->prepare($sql);
      $term = $_REQUEST["term"] . '%';
      // bind parameters to statement
      $stmt->bindParam(":term", $term);
      // execute the prepared statement
      $stmt->execute();
      if($stmt->rowCount() > 0){
          while($row = $stmt->fetch()){
              echo "<p><a href=''>" . $row["pseudo"] . "</a></p>";
          }
      } else{
          echo "<p>No matches found</p>";
      }
  }  
} catch(PDOException $e){
  die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
?>
	<?php require 'templates/header.php';?>

    <body class="mb-5" style="padding-top:20px; padding-bottom:50px">
    <?php require('templates/menu.php');?>
    <main class="container py-5">
        <div class="search-box mt-3">
            <input type="text" autocomplete="off" placeholder="Search country..." class="form-control" >
            <div class="result"></div>
        </div>
    </main>

        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>
	</body>
</html>