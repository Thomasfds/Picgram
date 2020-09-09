<?php
require_once('config.php');
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
                echo '<p class="bg-white p-3 mb-3 mt-3"><a href="/profil_public.php?id='.$row['id'].'">' . $row["pseudo"] . '</a></p>';
            }
        } else{
            echo "<p>No matches found</p>";
        }
    }  
  } catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
  }
  ?>