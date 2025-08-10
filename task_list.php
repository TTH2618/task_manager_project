<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/tasks.php";
	include "app/model/user.php";
	include "app/model/projects.php";
	$page_title = "Danh sách công việc";
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
				<?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager') { ?>
					<h4 class="title"><a href="create_task.php"><i class="fa fa-plus"></i> Thêm công việc mới</a></h4>
				<?php } else { ?>
					<h2 class="title">Công việc của tôi</h2>
				<?php } ?>
				<label>Tìm kiếm: </label>
				<input type="text" id="search" name="search" placeholder="Nhập thông tin công việc">
				<table class="main-table task-table">
					<tr>
						<th>#</th>
						<th class="sort-th" data-sort="title" data-order="asc">Tên công việc <i class="fa fa-sort"></i></th>
						<?php
						if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager') { ?>
							<th class="sort-th" data-sort="employee_id" data-order="asc">Giao cho <i class="fa fa-sort"></i></th>
						<?php
						}else{ ?>
							<th class="sort-th" data-sort="created_by" data-order="asc">Người giao <i class="fa fa-sort"></i></th>
						<?php
						}
						?>
						<th class="sort-th" data-sort="end_date" data-order="asc">Ngày kết thúc <i class="fa fa-sort"></i></th>
						<th class="sort-th" data-sort="time_left" data-order="asc">Thời hạn <i class="fa fa-sort"></i></th>
						<th class="sort-th" data-sort="status" data-order="asc">Trạng thái <i class="fa fa-sort"></i></th>
						<th>Hành động</th>
					</tr>
					<tbody id="TableBody">
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
					searchInputSelector: '#search',
					tableBodySelector: '#TableBody',
					sortThSelector: '.sort-th',
					ajaxUrl: 'app/get_tasks.php',
					defaultSort: 'time_left',
					defaultOrder: 'asc',
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