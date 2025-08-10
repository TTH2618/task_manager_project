
<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
    include "app/model/user.php";
    $page_title = "Sửa thông tin cá nhân";
    $user = get_users_by_id($conn, $_SESSION['id']);
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
			<h4 class="title-profile">Thông tin cá nhân<a href="profile.php">Quay về</a></h4> 
            <div class="form-container">
                <form method="post" action="app/update_profile.php" enctype="multipart/form-data">
                    <input type="text" name="id" value="<?=$user['id']?>" hidden>
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
                                <label>Email:</label>
                                <input type="text" name="email" value="<?=$user['email']?>" placeholder="Nhập email" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="btn-group"><button class="add-btn1">Lưu thay đổi</button></div>
                            <div class="btn-group"><a class="cancer-btn" href="project_list.php" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy bỏ</a></div>
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