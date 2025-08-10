<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../db_connection.php";
	include "../app/model/user.php";
    include "remove_query_param.php";
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
    $department_id_change= 0;
    $department_id = $_GET['department_id'];
    $data = array($department_id_change, $id);
    update_users_department_id($conn, $data);
    
    // $em = "Xóa nhân viên thành công";
    // $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../detail_department.php";
    // $redirect = remove_query_param($redirect, 'success');
    // $redirect .= (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?') . "success=" . urlencode($em);
    // header("Location: $redirect");
    // exit();

    $_SESSION['status'] = "Xóa nhân viên thành công";
    $_SESSION['status_code'] = "success";
    header("Location: ../detail-department.php?id=$department_id");
    exit();

}else {
	$em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>