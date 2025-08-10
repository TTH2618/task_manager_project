<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/Notification.php";

   if (isset($_GET['notification_id'])) {
       $notification_id = $_GET['notification_id'];
       notification_make_read($conn, $_SESSION['id'], $notification_id);

       // Lấy thông tin notification
       $notification = get_notification_by_id($conn, $notification_id);

       if ($notification && isset($notification['type']) && $notification['type'] == "Dự án mới") {
           // Nếu là dự án mới, chuyển đến trang detail-project.php
           $project_id = get_project_id_by_notification($conn, $notification_id);
           header("Location: ../detail-project.php?id=" . $project_id);
           exit();
       } else {
           // Nếu không, chuyển đến trang detail-task-employee.php
           $task_id = get_task_id_by_notification($conn, $notification_id);
           header("Location: ../detail-task-employee.php?id=" . $task_id);
           exit();
       }

     }else {
       header("Location: index.php");
       exit();
     }
}else{ 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
 ?>