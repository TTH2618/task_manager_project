<?php
session_start();
include "../db_connection.php";
include "model/department.php";
include "model/user.php";
include "model/projects.php";

$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
$allowed_sort = ['id', 'name', 'member_id', 'project_count', 'member_count'];
$allowed_order = ['asc', 'desc'];
if (!in_array($sort, $allowed_sort)) $sort = 'id';
if (!in_array($order, $allowed_order)) $order = 'asc';

// Xử lý tìm kiếm
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    if ($search === '') {
        $departments = get_all_departments($conn, $sort, $order);
    } else {
        // Tìm phòng ban theo tên
        $departments = [];
        $all_departments = get_all_departments($conn, $sort, $order);
        foreach ($all_departments as $dept) {
            if (stripos($dept['name'], $search) !== false) {
                $departments[] = $dept;
            }
        }
    }
} else {
    $departments = get_all_departments($conn, $sort, $order);
}

// Lấy tất cả user để đếm thành viên
$users = get_all_users($conn);

// Lấy tất cả dự án để đếm số dự án theo phòng ban
$projects = get_all_projects($conn);

// Sau khi lấy $departments, $users, $projects
if ($departments != 0) {
    foreach ($departments as &$dept) {
        // Đếm số dự án
        $dept['project_count'] = 0;
        if ($projects != 0) {
            foreach ($projects as $project) {
                if ($project['department_id'] == $dept['id']) $dept['project_count']++;
            }
        }
        // Đếm số thành viên
        $dept['member_count'] = 0;
        if ($users != 0) {
            foreach ($users as $user) {
                if ($user['department_id'] == $dept['id']) $dept['member_count']++;
            }
        }
    }
    unset($dept);
}
?>
<html>
<?php if ($departments != 0) { ?>
    <?php $i = 0;
    foreach ($departments as $dept) {
    ?>
        <tr>
            <td><?= ++$i ?></td>
            <td>
                <strong><?= htmlspecialchars($dept['name']) ?></strong>
                <small><?= htmlspecialchars($dept['description']) ?></small>
            </td>
            <td><?= $dept['project_count'] ?></td>
            <td><?= $dept['member_count'] ?></td>
            <td>
                <div class="dropdown">
                    <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href="detail-department.php?id=<?= $dept['id'] ?>">Xem</a>
                        <a href="#" class="edit-department-btn"
                            data-id="<?= $dept['id'] ?>"
                            data-name="<?= htmlspecialchars($dept['name'], ENT_QUOTES) ?>"
                            data-description="<?= htmlspecialchars($dept['description'], ENT_QUOTES) ?>"
                            >Sửa</a>
                        <a href="app/delete-department.php?id=<?= $dept['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng ban này?')">Xóa</a>
                    </div>
                </div>
            </td>
        </tr>
    <?php } ?>
    </table>
<?php } elseif (isset($search) && $search !== '') { ?>
        <tr>
            <td colspan="5">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="5">Không có phòng ban nào</td>
        </tr>
    <?php } ?>

</html>