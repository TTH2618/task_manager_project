<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    if (isset($_POST['name']) && isset($_POST['description']) && $_SESSION['role'] == 'admin') {
        include "../db_connection.php";

        function validate_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $id = validate_input($_POST['id']);
        $name = validate_input($_POST['name']);
        $description = validate_input($_POST['description']);
        
        // bắt buột nhập
        if (empty($name)) {
            $em = "Bạn phải nhập tên phòng ban";
            header("Location:../create_project.php?error=$em");
            exit();
        } else {
            include "model/department.php";
            $data = array($name, $description, $id);
            $department_id = update_department($conn, $data);
            $_SESSION['status'] = "Cập nhật phòng ban thành công";
            $_SESSION['status_code'] = "success";
            header("Location: ../department_list.php");
            exit();
        }
    } else {
        header("Location: ../department_list.php?error=Bạn không có quyền truy cập");
        exit();
    }
} else {
    $em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
