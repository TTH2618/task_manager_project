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
    $users = get_all_users_by_department_id($conn, $id, $sort, $order);
} else {
    $users = find_users_by_department_id($conn, $id, $search, $sort, $order);
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
                <?php if ($user['role'] == 'employee')
                    echo "Nhân viên";
                else if ($user['role'] == 'manager')
                    echo "Quản lý";
                else if ($user['role'] == 'admin')
                    echo "Quản trị viên";
                ?>
            </td>
            <td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <div class="dropdown">
                        <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a href="profile.php?id=<?= $user['id'] ?>">Xem</a>
                            <a href="edit-user.php?id=<?= $user['id'] ?>">Sửa</a>
                            <a href="delete-user.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <a href="profile.php?id=<?= $user['id'] ?>" class="edit-btn">Xem</a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <?php if (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="6">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } ?>
<?php } ?>

</html>