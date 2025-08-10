<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once "../db_connection.php";
        require_once "model/tasks.php";
        $task_id = intval($_POST['task_id'] ?? 0);
        $status = intval($_POST['status'] ?? 0);
        $manager_id = intval($_POST['manager_id'] ?? 0);
        $employee_ids = $_POST['employee_id'] ?? [];
        if (is_array($employee_ids)) {
            $validated_employee_ids = array_map('intval', $employee_ids);
            $employee_id = implode(',', $validated_employee_ids);
        } else {
            $employee_id = '';
        }
        $title = $_POST['title'] ?? '';
        if ($task_id > 0 && $status > 0) {
            // Cập nhật trạng thái
            $data = array($status, $task_id);
            $ok = update_task_status($conn, $data);
            if ($ok) {
                echo "success";
                if ($status == 2) {
                    // Gửi thông báo cho người quản lý khi công việc được hoàn thành
                    include "model/notification.php";
                    $notif_data = array($task_id, "Công việc '$title' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.", $manager_id, "Công việc hoàn thành");
                    insert_notification_task($conn, $notif_data);
                    $_SESSION['status'] = "Cập nhật thành công";
                    $_SESSION['status_code'] = "success";
                    header("Location: ../detail-task-employee.php?id=$task_id");
                    exit();
                } elseif ($status != 2) {
                    include "model/notification.php";
                    foreach ($validated_employee_ids as $emp_id) {
                        $notif_data = array($task_id, "Công việc '$title' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.", $emp_id, "Công việc chưa hoàn thành");
                        insert_notification_task($conn, $notif_data);
                    }
                    $_SESSION['status'] = "Cập nhật thành công";
                    $_SESSION['status_code'] = "success";
                    header("Location: ../detail-task-employee.php?id=$task_id");
                    exit();
                }
            } else {
                $_SESSION['status'] = "Cập nhật không thành công";
                $_SESSION['status_code'] = "error";
                header("Location: ../detail-task-employee.php?id=$task_id");
                exit();
            }
        } else {
            $_SESSION['status'] = "Cập nhật không thành công";
            $_SESSION['status_code'] = "error";
            header("Location: ../detail-task-employee.php?id=$task_id");
            exit();
        }
    }
} else {
    $em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
