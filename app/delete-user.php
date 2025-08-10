<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../db_connection.php";
	include "../app/model/user.php";

    if(!isset($_GET['id'])) {
        header("Location: ../user_list.php");
        exit();
    }
    $id = $_GET['id'];
	$user = get_users_by_id($conn, $id);
    
    if($user == 0) {
        header("Location: ../user_list.php");
        exit();
    }

    $data = array($id);
    delete_users($conn, $data);
    $em = "Xóa nhân viên thành công";
    $_SESSION['status'] = $em;
    $_SESSION['status_code'] = "success";
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../user_list.php";
    // $redirect .= (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?') . "success=" . urlencode($em);
    header("Location: $redirect");
    exit();

}else {
	$em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>