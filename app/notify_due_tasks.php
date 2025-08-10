<?php
include "../db_connection.php";
include "model/tasks.php";
include "model/notification.php";
include "model/user.php";

$today = new DateTime();
$tomorrow = (clone $today)->modify('+1 day')->format('Y-m-d');

$tasks = get_all_tasks($conn);

foreach ($tasks as $task) {
    $task_end_date = new DateTime($task['end_date']);
    // Nếu task chưa hoàn thành (status == 1) và end_date là ngày mai
    if ($task['status'] == 1 && $task['end_date'] == $tomorrow) {
        // Gửi thông báo cho từng employee
        $employee_ids = explode(',', $task['employee_id']);
        foreach ($employee_ids as $emp_id) {
            $message = "Công việc '{$task['title']}' sẽ hết hạn vào ngày mai ({$task['end_date']}). Vui lòng xem và hoàn thành trước hạn.";
            // Kiểm tra đã có thông báo chưa (tránh gửi trùng)
            $sql = "SELECT id FROM notifications WHERE task_id=? AND recipient=? AND type='Sắp đến hạn'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$task['id'], $emp_id]);
            if ($stmt->rowCount() == 0) {
                insert_notification_task($conn, [
                    $task['id'],
                    $message,
                    $emp_id,
                    'Sắp đến hạn'
                ]);
            }
        }
    } elseif ($task['status'] == 1 && $task_end_date->format('Y-m-d') == $today->format('Y-m-d')) {
        // Gửi thông báo cho từng employee
        $employee_ids = explode(',', $task['employee_id']);
        foreach ($employee_ids as $emp_id) {
            $message = "Công việc '{$task['title']}' sẽ hết hạn vào hôm nay ({$task['end_date']}). Vui lòng xem và hoàn thành ngay.";
            // Kiểm tra đã có thông báo chưa (tránh gửi trùng)
            $sql = "SELECT id FROM notifications WHERE task_id=? AND recipient=? AND type='Sắp đến hạn'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$task['id'], $emp_id]);
            if ($stmt->rowCount() == 0) {
                insert_notification_task($conn, [
                    $task['id'],
                    $message,
                    $emp_id,
                    'Sắp đến hạn'
                ]);
            }
        }
    } elseif ($task['status'] == 1 && $task_end_date < $today) {
        // Gửi thông báo cho từng employee
        $employee_ids = explode(',', $task['employee_id']);
        foreach ($employee_ids as $emp_id) {
            $message = "Công việc '{$task['title']}' đã quá hạn. Vui lòng xem và hoàn thành ngay.";
            // Kiểm tra đã có thông báo chưa (tránh gửi trùng)
            $sql = "SELECT id FROM notifications WHERE task_id=? AND recipient=? AND type='Đã quá hạn'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$task['id'], $emp_id]);
            if ($stmt->rowCount() == 0) {
                insert_notification_task($conn, [
                    $task['id'],
                    $message,
                    $emp_id,
                    'Đã quá hạn'
                ]);
            }
        }
    }
}
echo "Done.\n";
?>