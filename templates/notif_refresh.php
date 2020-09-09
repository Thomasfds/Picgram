<?php 
session_start();
require('../config.php');
require('../fonctions/fonction.php');

$id = $_SESSION['Auth']['id'];
$pseudo = $_SESSION['Auth']['pseudo'];

$nbcountnotif = getCountNotif($db, $id); 
$nbdemande = getCountDemandeAmis($db, $id); 


// $sql = "DELETE FROM notifications";
// $db->exec($sql);

?>
<?php if($nbcountnotif != 0){?>
    <span class="badge badge-danger ml-2"><?php echo $nbcountnotif;?></span>
<?php }?>

<?php if($nbdemande != 0){?>
    <span class="badge badge-primary ml-2 h-100"><i class="fas fa-users fa-sm"></i> 1</span>
<?php }?>