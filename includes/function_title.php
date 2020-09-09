<?php
if($_SERVER['PHP_SELF'] === "/feed.php"){
    $page_title ="Fil d'actualité";
}

if($_SERVER['PHP_SELF'] === "/menu.php"){
    $page_title ="Menu";
}

if($_SERVER['PHP_SELF'] === "/notification.php"){
    $page_title ="Notification";
}
?>