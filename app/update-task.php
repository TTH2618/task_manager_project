<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['employee_id']) && is_array($_POST['employee_id']) && isset($_POST['end_date']) && isset($_POST['status']) && $_SESSION['role'] == 'admin') {
        include "../db_connection.php";

        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        
        // Xử lý employee_id array
        $employee_ids = $_POST['employee_id'];
        $validated_employee_ids = array();
        foreach($employee_ids as $emp_id) {
            $validated_employee_ids[] = validate_input($emp_id);
        }
        $employee_id = implode(',', $validated_employee_ids);
    
        $start_date = validate_input($_POST['start_date']);
        $end_date = validate_input($_POST['end_date']);
        $status = validate_input($_POST['status']);
        $id = validate_input($_POST['id']);
        // bắt buột nhập username và passpass
        if (empty($title)) {
            $em = "Bạn phải nhập tên công việc";
            header("Location:../edit-task.php?error=$em&id=$id");
            exit();
        }else if (empty($employee_id)) {
            $em = "Bạn phải chọn nhân viên";
            header("Location:../edit-task.php?error=$em&id=$id");
            exit();
        }else if (empty($end_date)) {
            $em = "Vui lòng chọn ngày kết thúc";
            header("Location:../edit-task.php?error=$em&id=$id");
            exit();
        }else if (empty($status)) {
            $em = "Vui lòng chọn trạng thái";
            header("Location:../edit-task.php?error=$em&id=$id");
            exit();
        }else {
            include "model/tasks.php";
            $data = array($title, $description, $employee_id, $end_date, $status, $id);
            update_task($conn,$data);

            $em = "Sửa công việc thành công";
            // header("Location:../edit-task.php?success=$em&id=$id");
            $_SESSION['status'] = $em;
            $_SESSION['status_code'] = "success";
            header("Location: ../edit-task.php?id=$id");
            exit();
        }
    }else {
        $em = "error";
        header("Location:../edit-task.php?error=$em&id=$id");
        exit();
    }

}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>

