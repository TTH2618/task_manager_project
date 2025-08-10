<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) &&$_SESSION['role'] == "admin") {

?>

<!DOCTYPE html>
<html>
<head>
	<title>Thêm nhân viên</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="user">
			<h4 class="title">Thêm nhân viên <a href="user_list.php">Danh sách nhân viên</a></h4>
                <form class="form-add-user" method="post" action="app/add-user.php">
                    <?php if (isset($_GET['error'])) { ?>
                        <div class="danger" role="alert">
                        <?php echo stripcslashes($_GET['error']); ?>  
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET['success'])) { ?>
                        <div class="success" role="alert">
                        <?php echo stripcslashes($_GET['success']); ?>  
                        </div>
                    <?php } 
                    ?>
                    <div class="input-holder">
                        <label>Họ và tên:</label>
                        <input type="text" name="full_name" class="input-1" placeholder="Nhập họ và tên"><br>
                    </div>
                    <div class="input-holder">
                        <label>Username:</label>
                        <input type="text" name="user_name" class="input-1" placeholder="Nhập tên đăng nhập"><br>
                    </div>
                    <div class="input-holder">
                        <label>Ngày sinh:</label>
                        <input type="text" name="birthday" class="input-1" placeholder="Nhập ngày sinh"><br>
                    </div>
                    <div class="input-holder">
                        <label>Mật khẩu:</label>
                        <input type="text" name="password" class="input-1" placeholder="Nhập mật khẩu"><br>
                    </div>
                    <div class="input-holder">
                        <label>Chức vụ:</label>
                        <select name="role" class="input-1">
                            <option value="">Chọn chức vụ</option>
                            <option value="employee">Nhân viên</option>
                            <option value="manager">Quản lý</option>
                            <option value="admin">admin</option>
                        </select>
                        <br>
                     </div>
                    <button class="add-btn">Thêm nhân viên</button>
                </form>
		</section>
	</div>
<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(4)");
    active.classList.add("active")

</script>
</body>
</html>

<?php
}else {
	$em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>