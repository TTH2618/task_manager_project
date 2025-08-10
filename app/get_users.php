<?php
session_start();
include "../db_connection.php";
include "model/user.php";
include "model/department.php";
$sort = $_GET['sort'] ?? 'role';
$order = $_GET['order'] ?? 'asc';
$allowed_sort = ['id', 'full_name', 'username', 'email', 'role'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';
if (!in_array($order, $allowed_order)) $order = 'asc';

$departments = get_all_departments($conn);
// Lấy thông tin user hiện tại
$current_user = get_users_by_id($conn, $_SESSION['id']);
$current_department_id = $current_user['department_id'];

// Xử lý tìm kiếm
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if ($search === '') {
        $users = get_all_users($conn, $sort, $order);
    } else {
        $users = find_users($conn, $search, $sort, $order);
    }
} else {
    $users = get_all_users($conn, $sort, $order);
}

// Nếu không phải admin thì chỉ hiện user cùng phòng ban
if ($_SESSION['role'] != 'admin') {
    $users = get_all_users_by_department_id($conn, $current_department_id, $sort, $order);
}
?>
<html>
<?php if ($users != 0) { ?>
    <?php $i = 0; ?>
    <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= ++$i ?></td>
            <td><?= $user['full_name'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
                <?php if ($departments != 0) {
                    foreach ($departments as $department) {
                        if ($user['department_id'] == $department['id']) { ?>
                            <?= $department['name'] ?>
                <?php    }
                    }
                } ?>
            </td>
            <td><?= get_role_name($user['role']) ?></td>
            <td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <div class="dropdown">
                        <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a href="profile.php?id=<?= $user['id'] ?>">Xem</a>
                            <a href="edit-user.php?id=<?= $user['id'] ?>">Sửa</a>
                            <a href="app/delete-user.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <a href="profile.php?id=<?= $user['id'] ?>" class="edit-btn">Xem</a>
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
            <td colspan="10">Không có nhân viên nào</td>
        </tr>
    <?php } ?>

</html>