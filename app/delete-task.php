<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "manager")) {
    include "../db_connection.php";
	include "../app/model/tasks.php";

    if(!isset($_GET['id'])) {
        header("Location: ../task_list.php");
        exit();
    }
    $id = $_GET['id'];
	$task = get_task_by_id($conn, $id);
    
    if($task == 0) {
        header("Location: ../task_list.php");
        exit();
    }

    $data = array($id);
    delete_task($conn, $data);
    $em = "Xóa công việc thành công";
    $_SESSION['status'] = $em;
    $_SESSION['status_code'] = "success";
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../task_list.php";
    header("Location: $redirect");
    exit();
    // Kiểm tra có project_id trên URL không
    // if (isset($_GET['project_id'])) {
    //     $project_id = intval($_GET['project_id']);
    //     if ($project_id > 0) {
    //         header("Location: ../detail-project.php?id=$project_id&success=" . urlencode($em));
    //     } else {
    //         header("Location: ../task_list.php?error=" . urlencode("Invalid project ID"));
    //     }
    // } else {
    //     header("Location: ../task_list.php?success=" . urlencode($em));
    // }
    // exit();


}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>