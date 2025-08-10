<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../db_connection.php";
	include "../app/model/projects.php";

    if(!isset($_GET['id'])) {
        header("Location: ../project_list.php");
        exit();
    }
    $id = $_GET['id'];
	$project = get_project_by_id($conn, $id);

    if($project == 0) {
        header("Location: ../project_list.php");
        exit();
    }

    $data = array($id);
    delete_project($conn, $data);
    $em = "Xóa dự án thành công";
    // $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../project_list.php";
    // $redirect .= (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?') . "success=" . urlencode($em);
    // header("Location: $redirect");
    // exit();
    $_SESSION['status'] = $em;
    $_SESSION['status_code'] = "success";
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../project_list.php";
    header("Location: $redirect");
    exit();

}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>