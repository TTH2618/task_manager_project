<?php
if (!isset($_SESSION)) session_start();
if (!isset($conn)) {
	include_once __DIR__ . '/../db_connection.php';
}
include_once __DIR__ . '/../app/model/user.php';
$avatar = get_users_by_id($conn, $_SESSION['id']);
?>
<nav class="side-bar">
	<div class="user-p">
			<a href="profile.php" style="text-decoration: none;" data-nav="profile">
			<img src="<?= $avatar['avatar'] ? $avatar['avatar'] : 'img/user.jpg' ?>" alt="Avatar">
			<h2><?= $avatar['full_name'] ?></h2>
			</a>
	</div>
	<?php
	if ($_SESSION['role'] == "employee") {
	?>
		<!-- thanh tác vụ của nhân viên -->
		<ul id="navList">
			<a href="index.php" data-nav="index">
				<li>
					<i class="fa fa-desktop" aria-hidden="true"></i>
					<span>Trang chủ</span>
				</li>
			</a>
			<a href="project_list.php" data-nav="project_list">
				<li>
					<i class="fa fa-tasks" aria-hidden="true"></i>
					<span>Dự án</span>

				</li>
			</a>
			<a href="task_list.php" data-nav="task_list">
				<li>
					<i class="fas fa-clipboard-list" aria-hidden="true"></i>
					<span>Công việc</span>

				</li>
			</a>
			<a href="notification.php" data-nav="notification">
				<li>
					<i class="fa fa-bell" aria-hidden="true"></i>
					<span>Thông báo</span>

				</li>
			</a>
			<a href="logout.php">
				<li>
					<i class="fa fa-power-off" aria-hidden="true"></i>
					<span>Đăng xuất</span>

				</li>
			</a>
		</ul>
	<?php
	} else if ($_SESSION['role'] == "admin") {
	?>
		<!-- thanh tác vụ của admin -->
		<ul id="navList">
			<a href="index.php" data-nav="index">
				<li>
					<i class="fa fa-desktop" aria-hidden="true"></i>
					<span>Trang chủ</span>
				</li>
			</a>
			<a href="department_list.php" data-nav="department_list">
				<li>
					<i class="fa fa-server" aria-hidden="true"></i>
					<span>Phòng ban</span>
				</li>
			</a>
			<a href="project_list.php" data-nav="project_list">
				<li>
					<i class="fa fa-clipboard-list" aria-hidden="true"></i>
					<span>Dự án</span>

				</li>
			</a>
			<a href="task_list.php" data-nav="task_list">
				<li>
					<i class="fa fa-tasks" aria-hidden="true"></i>
					<span>Công việc</span>
				</li>
			</a>
			<a href="user_list.php" data-nav="user_list">
				<li>
					<i class="fa fa-users" aria-hidden="true"></i>
					<span>Nhân viên</span>
				</li>
			</a>
			<a href="notification.php" data-nav="notification">
				<li>
					<i class="fa fa-bell" aria-hidden="true"></i>
					<span>Thông báo</span>
				</li>
			</a>
			<a href="logout.php">
				<li>
					<i class="fa fa-power-off" aria-hidden="true"></i>
					<span>Đăng xuất</span>
				</li>
			</a>
		</ul>
	<?php
	} else if ($_SESSION['role'] == "manager") {
	?>
		<!-- thanh tác vụ của manager -->
		<ul id="navList">
			<a href="index.php" data-nav="index">
				<li>
					<i class="fa fa-desktop" aria-hidden="true"></i>
					<span>Trang chủ</span>

				</li>
			</a>
			<a href="project_list.php" data-nav="project_list">
				<li>
					<i class="fa fa-tasks" aria-hidden="true"></i>
					<span>Dự án</span>
				</li>
			</a>
			<a href="task_list.php" data-nav="task_list">
				<li>
					<i class="fa fa-clipboard" aria-hidden="true"></i>
					<span>Công việc</span>
				</li>
			</a>
			<a href="user_list.php" data-nav="user_list">
				<li>
					<i class="fa fa-users" aria-hidden="true"></i>
					<span>Nhân viên</span>
				</li>
			</a>
			<a href="notification.php" data-nav="notification">
				<li>
					<i class="fa fa-bell" aria-hidden="true"></i>
					<span>Thông báo</span>
				</li>
			</a>
			<a href="logout.php" data-nav="logout">
				<li>
					<i class="fa fa-power-off" aria-hidden="true"></i>
					<span>Đăng xuất</span>
				</li>
			</a>
		</ul>
	<?php } ?>
</nav>