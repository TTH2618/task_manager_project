<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['task_id']) && isset($_POST['manager_id']) && isset($_POST['employee_id']) && is_array($_POST['employee_id']) && isset($_POST['comment']) && isset($_POST['title'])) {

        $user_id = $_SESSION['id'];
        include "../db_connection.php";
        function validate_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $task_id = validate_input($_POST['task_id']);
        $manager_id = validate_input($_POST['manager_id']);
        $comment = validate_input($_POST['comment']);
        $title = validate_input($_POST['title']);
        // Xử lý employee_id array
        $employee_ids = $_POST['employee_id'];
        $validated_employee_ids = array();
        foreach ($employee_ids as $emp_id) {
            $validated_employee_ids[] = validate_input($emp_id);
        }
        $employee_id = implode(',', $validated_employee_ids);

        // Xử lý upload file nếu có
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "../uploads/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_name = time() . '_' . basename($_FILES['file']['name']);
            move_uploaded_file($file_tmp, $upload_dir . $file_name);
        }
        if ($task_id > 0 && $comment !== '') {
            include "model/progress.php";
            $file = isset($file_name) ? $file_name : '';
            $data = array($task_id, $user_id, $comment, $file);
            if (insert_progress($conn, $data)) {
                include "model/notification.php";
                if ($user_id != $manager_id) {
                    $notif_data = array($task_id, "Đã có cập nhật tiến độ công việc '$title' mà bạn đã giao. Bấm vào đây để xem chi tiết.", $manager_id, "Cập nhật tiến độ");
                    insert_notification_task($conn, $notif_data);
                } elseif ($user_id == $manager_id) {
                    foreach ($validated_employee_ids as $emp_id) {
                        $notif_data = array($task_id, "Quản lý đã phản hồi công việc '$title' mà bạn tham gia. Bấm vào đây để xem chi tiết.", $emp_id, "Cập nhật tiến độ");
                        insert_notification_task($conn, $notif_data);
                    }
                }
                // $em = "Thêm tiến độ công việc thành công";
                // header("Location: ../detail-task-employee.php?id=$task_id&success=$em");
                // exit();
                $_SESSION['status'] = "Thêm tiến độ công việc thành công";
                $_SESSION['status_code'] = "success";
                header("Location: ../detail-task-employee.php?id=$task_id");
                exit();
            } else {
                $em = "Lỗi khi thêm tiến độ công việc";
                header("Location: ../detail-task-employee.php?id=$task_id&error=$em");
                exit();
            }
        } else {
            $em = "Vui lòng nhập đầy đủ thông tin";
            header("Location: ../detail-task-employee.php?id=$task_id&error=$em");
            exit();
        }
    } else {
        $em = "error";
        header("Location:../create_task.php?&error=$em");
        exit();
    }
} else {
    $em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
