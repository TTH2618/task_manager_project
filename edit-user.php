<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "db_connection.php";
	include "app/model/user.php";
    $page_title = "Chỉnh sửa nhân viên";
    if(!isset($_GET['id'])) {
        header("Location: user_list.php");
        exit();
    }
    $id = $_GET['id'];
	$user = get_users_by_id($conn, $id);
    
    if($user == 0) {
        header("Location: user_list.php");
        exit();
    }
?>
    <!DOCTYPE html>
    <html>
    <?php include "inc/head.php"; ?>
    <body>
        <input type="checkbox" id="checkbox">
        <?php include "inc/header.php" ?>
        <div class="body">
            <?php include "inc/nav.php" ?>
            <section class="user">
                <h4 class="title"><a href="#" onclick="history.go(-1)">Danh sách nhân viên</a></h4>
                <div class="form-container">
                    <form method="post" action="app/update_user.php" enctype="multipart/form-data">
                        <div class="input-holder">
                        <div class="input-holder avatar-preview">
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewAvatar(event)" style="display: none;">
                            <img id="avatarPreview" src="<?= $user['avatar'] ? $user['avatar'] : 'img/user.jpg' ?>" alt="Avatar">
                            <input type="hidden" name="old_avatar" value="<?= $user['avatar'] ?>">
                            <input type="hidden" name="id" value="<?=$user['id']?>">
                        </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Họ và tên:</label>
                                <input type="text" name="full_name" value="<?=$user['full_name']?>" placeholder="Nhập họ và tên" required>
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input type="text" name="user_name" value="<?=$user['username']?>" placeholder="Nhập tên đăng nhập" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ngày sinh:</label>
                                <input type="date" name="birthday" value="<?=$user['birthday']?>" required>
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu:</label>
                                <input type="password" name="password" placeholder="Để trống nếu không muốn thay đổi">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Chức vụ:</label>
                                <select name="role" required>
                                    <option value="">Chọn chức vụ</option>
                                    <option value="employee" <?= $user['role'] == 'employee' ? 'selected' : '' ?>>Nhân viên</option>
                                    <option value="manager" <?= $user['role'] == 'manager' ? 'selected' : '' ?>>Quản lý</option>
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" name="email" value="<?=$user['email']?>" placeholder="Nhập email" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="btn-group"><button class="add-btn1">Lưu thay đổi</button></div>
                            <div class="btn-group"><a class="cancer-btn" href="javascript:history.back()" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy bỏ</a></div>
                        </div>
                    </form>
            </section>
        </div>
        <script type="text/javascript">
           
            //avatar
            document.getElementById('avatarPreview').onclick = function() {
                document.getElementById('avatarInput').click();
            };
            function previewAvatar(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('avatarPreview');
                    output.src = reader.result;
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        </script>
    </body>

    </html>

<?php
} else {
    $em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>