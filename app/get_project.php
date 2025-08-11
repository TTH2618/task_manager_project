<?php
session_start();
include "../db_connection.php";
include "model/user.php";
include "model/projects.php";

$users = get_all_users($conn);
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

// Validate input
$allowed_sort = ['id', 'title', 'manager_id', 'start_date', 'end_date', 'status'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';
if (!in_array($order, $allowed_order)) $order = 'asc';

// Query
$projects = [];
if ($_SESSION['role'] == 'admin') {
    if ($search === '') {
        $projects = get_all_projects($conn, $sort, $order);
    } else {
        $projects = find_projects($conn, $search, $sort, $order);
    }
} else {
    if (isset($_GET['search'])) {
        $search = trim($_GET['search']);
        if ($search === '') {
            $projects = get_all_projects_by_user_id($conn, $_SESSION['id'], $sort, $order);
        } else {
            // Tìm user theo tên
            $users_found = find_users_by_name($conn, $search);
            if ($users_found && count($users_found) > 0) {
                // Lấy id các user tìm được
                $user_ids = array_column($users_found, 'id');
                // Lấy task theo manager_id
                $projects = find_project_by_manager_ids($conn, $user_ids, $sort, $order);
            } else {
                // Nếu không tìm thấy user, tìm theo task như cũ
                $projects = find_projects($conn, $search, $sort, $order);
            }
        }
    } else {
        $projects = get_all_projects_by_user_id($conn, $_SESSION['id'], $sort, $order);
    }
}

?>
<html>
<?php if ($projects != 0) { ?>
    <?php $i = 0;
    foreach ($projects as $project) { ?>
        <tr>
            <td><?= ++$i ?></td>
            <td>
                <strong><?= $project['title'] ?></strong>
                <small><?= $project['description'] ?></small>
            </td>
            <td>
                <?php if ($users != 0) {
                    foreach ($users as $user) {
                        if ($project['manager_id'] == $user['id']) { ?>
                            <?= $user['full_name'] ?>
                <?php    }
                    }
                } ?>
            </td>
            <td><?= $project['start_date'] ?></td>
            <td><?= $project['end_date'] ?></td>
            <td>
                <span class="status-badge status-<?= $project['status'] ?>">
                    <?= get_status_name_project($project['status']) ?>
                </span>
            </td>
            <!-- Dropdown for actions -->
            <td>
                <div class="dropdown">
                    <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href="detail-project.php?id=<?= $project['id'] ?>">Xem</a>
                        <a href="app/export_project_report.php?id=<?= $project['id'] ?>">Xuất báo cáo</a>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <a href="edit-project.php?id=<?= $project['id'] ?>">Sửa</a>
                            <a href="app/delete-project.php?id=<?= $project['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</a>
                        <?php } ?>
                    </div>
                </div>
            </td>
        </tr>
    <?php } ?>
<?php } elseif (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="7">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="7">Không có dự án nào</td>
        </tr>
    <?php } ?>

</html>