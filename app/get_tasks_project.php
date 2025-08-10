<?php
session_start();
include "../db_connection.php";
include "model/user.php";
include "model/projects.php";
include "model/tasks.php";
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
$allowed_sort = ['id', 'title', 'end_date', 'status', 'created_by'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';
if (!in_array($order, $allowed_order)) $order = 'asc';

if (!isset($_GET['id'])) {
    header("Location: project_list.php");
    exit();
}
$id = $_GET['id'];
$project = get_project_by_id($conn, $id);

if ($project == 0) {
    header("Location: project_list.php");
    exit();
}
$users = get_all_users($conn);
$tasks = get_all_tasks_by_project_id($conn, $id, $sort, $order);

?>
<html>
<?php if ($tasks != 0) { ?>
    <?php $i = 0;

    // Custom sort for "time_left"
    if ($sort === 'time_left') {
        usort($tasks, function ($a, $b) {
            $today = new DateTime();
            $a_deadline = new DateTime($a['end_date']);
            $b_deadline = new DateTime($b['end_date']);

            // Task đã quá hạn và chưa hoàn thành
            $a_overdue = ($a_deadline < $today) && ($a['status'] == 1);
            $b_overdue = ($b_deadline < $today) && ($b['status'] == 1);

            if ($a_overdue && !$b_overdue) return -1;
            if (!$a_overdue && $b_overdue) return 1;

            // Nếu cùng trạng thái (cùng overdue hoặc cùng chưa overdue)
            // Sắp xếp theo số ngày còn lại (hoặc đã quá hạn bao nhiêu ngày)
            $a_days = $today->diff($a_deadline)->days;
            $b_days = $today->diff($b_deadline)->days;

            // Nếu cùng overdue, task quá hạn lâu hơn lên trước
            if ($a_overdue && $b_overdue) {
                return $b_days <=> $a_days; // Quá hạn nhiều ngày hơn lên trước
            }
            // Nếu chưa overdue, task gần hết hạn lên trước
            return $a_days <=> $b_days;
        });

        // Nếu order là desc thì đảo ngược mảng
        if ($order === 'desc') {
            $tasks = array_reverse($tasks);
        }
    }

    foreach ($tasks as $task) { ?>
        <tr>
            <td><?= ++$i ?></td>
            <td>
                <?= $task['title'] ?>
            </td>
            <td>
                <?php
                    $employee_ids = explode(',', $task['employee_id']);
                    $member_names = [];
                    foreach ($users as $user) {
                        if (in_array($user['id'], $employee_ids)) {
                            $member_names[] = htmlspecialchars($user['full_name']);
                        }
                    } ?>
                    <?= implode(', ', $member_names) ?>
                    <?php
                ?>
            </td>
            <td><?= $task['end_date'] ?></td>
            <td>
                <?php
                $today = new DateTime();
                $deadline = new DateTime($task['end_date']);
                $interval = $today->diff($deadline);
                $days_left = (int)$interval->format('%r%a');
                if ($today->format('Y-m-d') === $deadline->format('Y-m-d')) {
                    if ($task['status'] == 2 || $task['status'] == 3) {
                        echo "<span class=\"status-badge status-3\">Hôm nay</span>";
                    } else {
                        echo "<span class=\"status-badge status-5\">Hôm nay</span>";
                    }
                } elseif ($deadline < $today) {
                    if ($task['status'] == 2 || $task['status'] == 3) {
                        echo '<span class="status-badge status-3">Đã qua ' . $interval->days . ' ngày.</span>';
                    } else {
                        echo '<span class="status-badge status-5">Đã qua ' . $interval->days . ' ngày.</span>';
                    }
                } else {
                    if ($task['status'] == 2 || $task['status'] == 3) {
                        echo '<span class="status-badge status-3">Còn lại ' . ($days_left +1) . ' ngày.</span>';
                    } else {
                        echo '<span class="status-badge status-1">Còn lại ' . ($days_left + 1) . ' ngày.</span>';
                    }
                }
                ?>
            </td>
            <td>
                <span class="status-badge status-<?= $task['status'] ?>">
                    <?= get_status_name($task['status']) ?>
                </span>
            </td>
            <!-- <td><?= $task['description'] ?></td> -->
            <td>
                <?php if ($_SESSION['role'] == 'employee') { ?>
                    <a href="detail-task-employee.php?id=<?= $task['id'] ?>" class="edit-btn">Chi tiết</a>
                <?php } else { ?>
                    <div class="dropdown">
                        <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a href="detail-task-employee.php?id=<?= $task['id'] ?>">Xem</a>
                            <a href="edit-task.php?id=<?= $task['id'] ?>">Sửa</a>
                            <a href="app/delete-task.php?id=<?= $task['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</a>
                        </div>
                    </div>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
<?php } elseif (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="10">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="10">Không có công việc nào</td>
        </tr>
    <?php } ?>

</html>