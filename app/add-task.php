<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['title']) && isset($_POST['project_id']) && isset($_POST['description']) && isset($_POST['created_by']) && isset($_POST['employee_id']) && is_array($_POST['employee_id']) && isset($_POST['end_date']) && isset($_POST['status']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager')) {
        include "../db_connection.php";

        function validate_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $created_by = validate_input($_POST['created_by']);
        $project_id = validate_input($_POST['project_id']);

        // Xử lý employee_id array
        $employee_ids = $_POST['employee_id'];
        $validated_employee_ids = array();
        foreach ($employee_ids as $emp_id) {
            $validated_employee_ids[] = validate_input($emp_id);
        }
        $employee_id = implode(',', $validated_employee_ids);

        $end_date = validate_input($_POST['end_date']);
        $status = validate_input($_POST['status']);
        // bắt buột nhập 
        if (empty($title)) {
            $em = "Bạn phải nhập tên công việc";
            header("Location:../create_task.php?error=$em");
            exit();
        } else if (empty($employee_id)) {
            $em = "Bạn phải chọn nhân viên";
            header("Location:../create_task.php?error=$em");
            exit();
        } else if (empty($end_date)) {
            $em = "Vui lòng chọn ngày kết thúc";
            header("Location:../create_task.php?error=$em");
            exit();
        } else if (empty($status)) {
            $em = "Vui lòng chọn trạng thái";
            header("Location:../create_task.php?error=$em");
            exit();
        } else {
            include "model/tasks.php";
            $data = array($title, $project_id, $description, $created_by, $employee_id, $end_date, $status);

            $task_id = insert_tasks($conn, $data);
            include "model/notification.php";
            foreach ($validated_employee_ids as $emp_id) {
                $notif_data = array($task_id, "Công việc '$title' đã được giao cho bạn. Bấm vào đây để xem chi tiết.", $emp_id, "Công việc mới");
                insert_notification_task($conn, $notif_data);
            }   
            $em = "Thêm công việc thành công";
            if ($project_id == 0) {
                // header("Location:../create_task.php?success=$em");
                // exit();
                $_SESSION['status'] = $em;
                $_SESSION['status_code'] = "success";
                header("Location:../create_task.php");
                exit();
            } else {
                // header("Location:../detail-project.php?id=$project_id&success=$em");
                // exit();
                $_SESSION['status'] = $em;
                $_SESSION['status_code'] = "success";
                header("Location:../detail-project.php?id=$project_id");
                exit();
            }
        }
    } else {
        $em = "error";
        $_SESSION['status'] = $em;
        $_SESSION['status_code'] = "error";
        header("Location:../create_task.php");
        exit();
    }
} else {
    $em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
