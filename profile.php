<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/user.php";
    // Nếu có tham số id thì lấy thông tin nhân viên đó, không thì lấy thông tin của chính mình
    $view_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id'];
    $user = get_users_by_id($conn, $view_id);
    $page_title = ($view_id == $_SESSION['id']) ? "Thông tin cá nhân" : "Chi tiết nhân viên";
?>
<!DOCTYPE html>
<html>
<?php include "inc/head.php"; ?>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="body-profile">
            <h4 class="title"><a href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Quay lại</a></h4>
            <h4 class="title-profile">
                <?= $page_title ?>
                <?php if ($view_id == $_SESSION['id']) { ?>
                    <a href="edit_profile.php">Chỉnh sửa</a>
                <?php } else if ($_SESSION['role'] == 'admin') { ?>
                    <a href="edit-user.php?id=<?= $user['id'] ?>">Chỉnh sửa</a>
                <?php } ?>
            </h4>
            <div class="form-container-profile big-profile-form">
                <form>
                    <div class="input-holder">
                        <div class="input-holder avatar-preview">
                            <img id="avatarPreview" src="<?= $user['avatar'] ? $user['avatar'] : 'img/user.jpg' ?>" alt="Avatar">
                        </div>
                    </div>
                    <div class="form-group-profile">
                        <label for="id">Mã số nhân viên</label>
                        <input type="text" name="id" value="<?= htmlspecialchars($user['id']) ?>" disabled>
                    </div>
                    <div class="form-group-profile">
                        <label for="full_name">Họ và tên</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" disabled>
                    </div>
                    <div class="form-group-profile">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                    </div>
                    <div class="form-group-profile">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div class="form-group-profile">
                        <label for="birthday">Ngày sinh</label>
                        <input type="text" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" disabled>
                    </div>
                    <div class="form-group-profile">
                        <label for="role">Chức vụ</label>
                        <input type="text" name="role" value="<?php
                            if ($user['role'] == 'employee') echo 'Nhân viên';
                            else if ($user['role'] == 'manager') echo 'Quản lý';
                            else echo htmlspecialchars($user['role']);
                        ?>" disabled>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
<?php
} else {
    $em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>