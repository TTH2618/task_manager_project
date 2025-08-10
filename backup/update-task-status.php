<?php
session_start();
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Chưa đăng nhập";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "../db_connection.php";
    require_once "model/tasks.php";
    $task_id = intval($_POST['task_id'] ?? 0);
    $status = intval($_POST['status'] ?? 0);
    $manager_id = intval($_POST['manager_id'] ?? 0);
    if ($task_id > 0 && $status > 0) {
        // Cập nhật trạng thái
            $data = array($status, $task_id);
            $ok= update_task_status($conn,$data);
        if ($ok) {
            echo "success";
            if($status == 3) {
            // Gửi thông báo cho người quản lý khi công việc được hoàn thành
            include "model/notification.php";
            $notif_data = array($task_id, "Công việc bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.", $manager_id, "Công việc hoàn thành");
            insert_notification_task($conn, $notif_data);
            }
        } else {
            http_response_code(500);
            echo "Lỗi khi cập nhật";
        }
    } else {
        http_response_code(400);
        echo "Thiếu dữ liệu";
    }
}