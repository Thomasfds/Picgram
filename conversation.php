<?php
session_start();
require('config.php');

if(!isset($_SESSION['ouvert'])){
    header("Location: $domain/index.php");
}

$conversation_id = $_GET['id'];
$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];


$query = $db->prepare("SELECT * FROM messages WHERE conv_id = :id");
$query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$query->execute();

$messages = $query->fetchAll();


if(isset($_POST['send'])){
$message = $_POST['message'];

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO messages (membre_id, pseudo, message,  conv_id, membre_sender) VALUES
  (:membre_id, :pseudo, :message, :conv_id, :membre_sender)";
        $query = $db->prepare($sql);

        $query->bindparam(':membre_id', $_POST['sendto']);
        $query->bindparam(':membre_sender', $id);
        $query->bindparam(':pseudo', $pseudo);
        $query->bindparam(':message', $message);

        $query->bindparam(':conv_id', $conversation_id);
        $query->execute();

        header("location: conversation.php?id=$conversation_id");
}

?>
	<?php require 'templates/header.php';?>

    <body class="bg-light mb-5"  style="padding-bottom:60px;">
    
    <ul class="nav nav-tabs p-3  text-dark" id="myTab" role="tablist">
			<div class="container">
				<div class="row">
					<div class="col-4 d-flex">
						<a href="feed.php" class="text-dark">
						<i class="fas fa-long-arrow-alt-left fa-2x"></i>
						</a>
                    </div>
                    <div class="col-4">
						Message
					</div>
                    <div class="col-4 text-right">
						<a href="feed.php" class="text-dark">
						<i class="fas fa-user fa-2x"></i>
						</a>
					</div>
				</div>
			</div>
		</ul>

		<main class="container p-0 mt-5" id="content">
            
            <div class="list-group">
               

            <?php foreach( $messages as $messages){ ?>
             
                
            <?php if($messages['membre_id'] != $id){?>
            <li  class="list-group-item list-group-item-action rounded-0" style="border-left: 5px solid blue;">
                <div class="d-flex w-100 justify-content-between">
                
                <h5 class="mb-1"><?php echo $messages['pseudo']?></h5>
                </div>
                <p class="mt-3">
                <?php echo $messages['message']?>
                </p>
            </li>
            <?php }?>

            <?php if($messages['membre_sender'] != $id)  {?>
            <li  class="list-group-item list-group-item-action rounded-0" style="border-left: 5px solid red;">
                <div class="d-flex w-100 justify-content-between">
                
                <h5 class="mb-1"><?php echo $messages['pseudo']?></h5>
                </div>
                <p class="mt-3">
                <?php echo $messages['message']?>
                </p>
            </li>
            <?php }}?>
            </div>
        </main>
        <form class="bg-white p-2 fixed-bottom d-flex w-100" method="post">
            <input type="text" class="form-control col-10 rounded-0" name="message">
            <?php if($messages['membre_id'] != $id){?>
            <input type="hidden" value="<?php echo $messages['membre_id'];?>" name="sendto">
            <?php }?>
            <?php if($messages['membre_sender'] != $id){?>
            <input type="hidden" value="<?php echo $messages['membre_sender'];?>" name="sendto">
            <?php }?>
            <button type="submit" class="col-2 btn btn-primary rounded-0" name="send">
            <i class="fas fa-paper-plane fa"></i>
            </button>
        </form>
        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>