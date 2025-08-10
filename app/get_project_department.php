<?php
session_start();
include "../db_connection.php";
include "model/user.php";
include "model/projects.php";

$id = $_GET['id'] ?? '';
$users = get_all_users_by_department_id($conn, $id);
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
if ($search === '') {
    $projects = get_all_projects_by_department_id($conn, $id, $sort, $order);
} else {
    $projects = find_projects_by_department_id($conn, $id, $search, $sort, $order);
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
                        <a href="edit-project.php?id=<?= $project['id'] ?>">Sửa</a>
                        <a href="app/delete-project.php?id=<?= $project['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</a>
                    </div>
                </div>
            </td>
        </tr>
    <?php } ?>
<?php } elseif (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="10">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="10">Không có dự án nào</td>
        </tr>
    <?php } ?>
</html>