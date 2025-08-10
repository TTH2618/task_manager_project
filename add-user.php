<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
$page_title = "Tạo nhân viên";
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
                <h4 class="title"><a href="user_list.php">Danh sách nhân viên</a></h4>
                <div class="form-container">
                    <form method="post" action="app/add-user.php" enctype="multipart/form-data">
                        <div class="input-holder">
                        <div class="input-holder avatar-preview">
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewAvatar(event)" style="display: none;">
                            <img id="avatarPreview" src="img/user.jpg" alt="Avatar">
                        </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Họ và tên:</label>
                                <input type="text" name="full_name" placeholder="Nhập họ và tên" required>
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input type="text" name="user_name" placeholder="Nhập tên đăng nhập" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ngày sinh:</label>
                                <input type="date" name="birthday" required>
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu:</label>
                                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Chức vụ:</label>
                                <select name="role" required>
                                    <option value="">Chọn chức vụ</option>
                                    <option value="employee">Nhân viên</option>
                                    <option value="manager">Quản lý</option>
                                    <option value="admin">admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" name="email" placeholder="Nhập email" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="btn-group"><button class="add-btn1">Thêm nhân viên</button></div>
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

            <?php if (isset($_GET['error'])) { ?>
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo stripcslashes($_GET['error']); ?>',
                    showConfirmButton: true
                });
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo stripcslashes($_GET['success']); ?>',
                    showConfirmButton: true,
                });
            <?php }
            ?>
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