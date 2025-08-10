<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/Notification.php";


    $notifications = get_all_my_notifications_not_read($conn, $_SESSION['id']);
    if ($notifications == 0) { ?>
        <li>
        <a href="#">
           Bạn không có thông báo nào.
        </a>
        </li>
       
    <?php }else{
    foreach ($notifications as $notification) {
 ?>
    <li>
    <a href="app/notification-read.php?notification_id=<?=$notification['id']?>">
        <?php  echo "<mark>".$notification['type']."</mark>: "; ?>
        <?=$notification['message']?>
        &nbsp;&nbsp;<small><?=$notification['date']?></small>
    </a>
    </li>
 <?php
 }
 }
}else{ 
  echo "";
}
 ?>
