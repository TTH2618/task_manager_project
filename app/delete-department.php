<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../db_connection.php";
	include "../app/model/department.php";

    if(!isset($_GET['id'])) {
        header("Location: ../department_list.php");
        exit();
    }
    $id = $_GET['id'];
	$department = get_department_by_id($conn, $id);

    if($department == 0) {
        header("Location: ../department_list.php");
        exit();
    }

    $data = array($id);
    delete_department($conn, $data);
    $em = "Xóa phòng ban thành công";
    // header("Location: ../department_list.php?success=$em");
    // exit();
        $_SESSION['status'] = $em;
        $_SESSION['status_code'] = "success";
        header("Location:../department_list.php");
        exit();

}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>