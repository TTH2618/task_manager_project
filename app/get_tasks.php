<?php
session_start();
include "../db_connection.php";
include "model/user.php";
include "model/projects.php";
include "model/tasks.php";

$sort = $_GET['sort'] ?? 'end_date';
$order = $_GET['order'] ?? 'desc';
$allowed_sort = ['id', 'title', 'project_id', 'created_by', 'time_left', 'employee_id', 'start_date', 'end_date', 'status'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';
if (!in_array($order, $allowed_order)) $order = 'asc';

// Xử lý tìm kiếm
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if (strtolower($search) === 'tôi') {
        if ($_SESSION['role'] == 'admin') {
            $tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
        } else {
            $tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
        }
    } else if ($search === '') {
        if ($_SESSION['role'] == 'admin') {
            $tasks = get_all_tasks($conn, $sort, $order);
        } else {
            $tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
        }
    } else {
        if ($_SESSION['role'] == 'admin') {
            // Tìm user theo tên
            $users_found = find_users_by_name($conn, $search);
            $user_ids = $users_found && count($users_found) > 0 ? array_column($users_found, 'id') : [];

            // Tìm project theo tên
            $projects_found = find_projects_by_name($conn, $search);
            $project_ids = $projects_found && count($projects_found) > 0 ? array_column($projects_found, 'id') : [];

            // Tìm task theo tên công việc
            $tasks_by_title = find_tasks($conn, $search, $sort, $order);

            // Nếu tìm thấy user hoặc project
            $tasks = [];
            if (!empty($user_ids)) {
                // Tìm task theo created_by
                $tasks_by_user = find_tasks_by_created_by_ids($conn, $user_ids, $sort, $order);
                $tasks = array_merge($tasks, $tasks_by_user);
            }
            if (!empty($project_ids)) {
                // Tìm task theo project_id
                $tasks_by_project = find_tasks_by_project_ids($conn, $project_ids, $sort, $order);
                $tasks = array_merge($tasks, $tasks_by_project);
            }
            if (!empty($tasks_by_title)) {
                $tasks = array_merge($tasks, $tasks_by_title);
            }
            // Loại bỏ trùng lặp theo id
            $tasks = array_unique($tasks, SORT_REGULAR);
        } else {
            // Employee chỉ xem các công việc được giao cho mình
            // Tìm user theo tên
            $users_found = find_users_by_name($conn, $search);
            $user_ids = $users_found && count($users_found) > 0 ? array_column($users_found, 'id') : [];

            // Tìm project theo tên
            $projects_found = find_projects_by_name($conn, $search);
            $project_ids = $projects_found && count($projects_found) > 0 ? array_column($projects_found, 'id') : [];

            // Tìm task theo tên công việc
            $tasks_by_title = find_tasks_by_user_and_title($conn, $_SESSION['id'], $search, $sort, $order);

            // Nếu tìm thấy user hoặc project
            $tasks = [];
            if (!empty($user_ids)) {
                // Tìm task theo created_by
                $tasks_by_user = find_tasks_by_user_and_created_by_ids($conn, $_SESSION['id'], $user_ids, $sort, $order);
                $tasks = array_merge($tasks, $tasks_by_user);
            }
            if (!empty($project_ids)) {
                // Tìm task theo project_id
                $tasks_by_project = find_tasks_by_user_and_project_ids($conn, $_SESSION['id'], $project_ids, $sort, $order);
                $tasks = array_merge($tasks, $tasks_by_project);
            }
            if (!empty($tasks_by_title)) {
                $tasks = array_merge($tasks, $tasks_by_title);
            }
            // Loại bỏ trùng lặp theo id
            if (!empty($tasks)) {
                $tasks = array_unique($tasks, SORT_REGULAR);
            } else {
                $tasks = find_tasks_by_user_id_and_search($conn, $_SESSION['id'], $search, $sort, $order);
            }
        }
    }
} else {
    // Nếu không tìm thấy user hoặc project, tìm theo task như cũ
    if ($_SESSION['role'] == 'admin') {
        $tasks = get_all_tasks($conn, $sort, $order);
    } else {
        $tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
    }
}

$users = get_all_users($conn,);
$projects = get_all_projects($conn);

if (isset($_GET['page']) && $_GET['page'] == 'detail-task-employee' && isset($_GET['id'])) {
    include "detail-task-employee.php";
    exit();
}

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
                <small>
                    <?php if ($projects != 0) {
                        foreach ($projects as $project) {
                            if ($task['project_id'] == $project['id']) { ?>
                                Từ dự án: <?= $project['title'] ?>
                    <?php    }
                        }
                    } ?>
                </small>
            </td>
            <td>
                <?php
                if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager') {
                    $employee_ids = explode(',', $task['employee_id']);
                    $member_names = [];
                    foreach ($users as $user) {
                        if (in_array($user['id'], $employee_ids)) {
                            $member_names[] = htmlspecialchars($user['full_name']);
                        }
                    } ?>
                    <?= implode(', ', $member_names) ?>
                    <?php
                } else {
                    if ($users != 0) {
                        foreach ($users as $user) {
                            if ($task['created_by'] == $user['id']) { ?>
                                <?= $user['full_name'] ?>
                <?php    }
                        }
                    }
                }
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
<?php }elseif (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="10">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="10">Không có công việc nào</td>
        </tr>
    <?php } ?>

</html>