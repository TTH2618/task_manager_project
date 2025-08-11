<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/projects.php";
	include "app/model/user.php";
	$page_title = "Danh sách dự án";

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
				<?php if ($_SESSION['role'] == 'admin') { ?>
					<h4 class="title"><a href="create_project.php"><i class="fa fa-plus"></i> Thêm dự án mới</a></h4>
				<?php } else { ?>
					<h2 class="title">Dự án của tôi</h2>
				<?php } ?>
				<form action="" method="get">
					<label>Tìm kiếm: </label>
					<input type="text" id="searchProject" name="search" placeholder="Nhập thông tin dự án">
					<!-- <button class="search-btn">Tìm kiếm</button> -->
				</form>
				<table class="main-table task-table">
					<tr>
						<th>#</th>
						<th class="sort-th1" data-sort="title" data-order="asc">Tên dự án <i class="fa fa-sort"></i></th>
						<th class="sort-th1" data-sort="manager_id" data-order="asc">Người quản lý <i class="fa fa-sort"></i></th>
						<th class="sort-th1" data-sort="start_date" data-order="asc">Ngày bắt đầu <i class="fa fa-sort"></i></th>
						<th class="sort-th1" data-sort="end_date" data-order="asc">Ngày kết thúc <i class="fa fa-sort"></i></th>
						<th class="sort-th1" data-sort="status" data-order="asc">Trạng thái <i class="fa fa-sort"></i></th>
						<th>Hành động</th>
					</tr>
					<tbody id="projectTableBody">
						<!-- Kết quả AJAX sẽ render ở đây -->
					</tbody>
				</table>
			</section>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				initAjaxTable({
					openBtnSelector: null, // Không dùng modal
					modalSelector: null,
					closeBtnSelector: null,
					searchInputSelector: '#searchProject',
					tableBodySelector: '#projectTableBody',
					sortThSelector: '.sort-th1',
					ajaxUrl: 'app/get_project.php',
					defaultSort: 'status',
					defaultOrder: 'desc',
				});
			});
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