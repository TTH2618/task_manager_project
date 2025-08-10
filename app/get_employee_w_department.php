<?php
session_start();
include "../db_connection.php";
include "model/user.php";

$id = $_GET['id'] ?? '';
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'role';
$order = $_GET['order'] ?? 'asc';

// Validate input
$allowed_sort = ['id', 'full_name', 'username', 'email', 'role'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'full_name';
if (!in_array($order, $allowed_order)) $order = 'asc';

// Query
$users = [];
if ($search === '') {
    $users = get_all_users_by_department_id($conn,$id, $sort, $order);
} else {
    $users = find_users_by_department_id($conn,$id, $search, $sort, $order);
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
            <td><?= get_role_name($user['role']) ?></td>
            <td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <div class="dropdown">
                        <button class="dropbtn" type="button">Hành động <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a href="profile.php?id=<?= $user['id'] ?>">Xem</a>
                           <a href="app/delete_user_dept.php?id=<?= $user['id'] ?>&department_id=<?=$id?>" onclick="return confirm('Bạn có chắc chắn muốn loại nhân viên này ra khỏi phòng ban?')">Xóa</a>
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
