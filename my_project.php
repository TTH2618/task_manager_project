<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/projects.php";
	include "app/model/user.php";
	 $page_title = "Danh sách dự án";
	$sort = $_GET['sort'] ?? 'id';
	$order = $_GET['order'] ?? 'asc';
	$allowed_sort = ['id', 'title', 'manager_id', 'start_date', 'end_date', 'status'];
	$allowed_order = ['asc', 'desc'];
	if (!in_array($sort, $allowed_sort)) $sort = 'id';
	if (!in_array($order, $allowed_order)) $order = 'asc';
	
		// Xử lý tìm kiếm
		if (isset($_GET['search'])) {
			$search = trim($_GET['search']);
			if ($search === '') {
				$projects = get_all_projects_by_user_id($conn,$_SESSION['id'], $sort, $order);
			} else {
				// Tìm user theo tên
				$users_found = find_users_by_name($conn, $search);
				if ($users_found && count($users_found) > 0) {
					// Lấy id các user tìm được
					$user_ids = array_column($users_found, 'id');
					// Lấy task theo manager_id
					$projects = find_project_by_manager_ids($conn, $user_ids, $sort, $order);
				} else {
					// Nếu không tìm thấy user, tìm theo task như cũ
					$projects = find_projects($conn, $search, $sort, $order);
				}
			}
		} else {
			$projects = get_all_projects_by_user_id($conn,$_SESSION['id'], $sort, $order);
		}
	$users = get_all_users($conn);
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
				<form action="" method="get">
					<label>Tìm kiếm: </label>
					<input type="text" name="search" placeholder="Nhập thông tin dự án">
					<button class="search-btn">Tìm kiếm</button>
				</form>
				<table class="main-table">
					<tr>
						<th>#</th>
						<?php
						render_sort_th('Tên dự án', 'title', $sort, $order, $search ?? '');
						render_sort_th('Người quản lý', 'manager_id', $sort, $order, $search ?? '');
						render_sort_th('Ngày bắt đầu', 'start_date', $sort, $order, $search ?? '');
						render_sort_th('Ngày kết thúc', 'end_date', $sort, $order, $search ?? '');
						render_sort_th('Trạng thái', 'status', $sort, $order, $search ?? '');
						?>
						<th>Hành động</th>
					</tr>
					<?php if ($projects != 0) { ?>
						<?php $i = 0;
						foreach ($projects as $project) { ?>
							<tr>
								<td><?= ++$i ?></td>
								<td>
									<strong><?= $project['title'] ?></strong>
									<small><?= $project['description'] ?></small>
								</td>
								<td>
									<?php if ($users != 0) {
										foreach ($users as $user) {
											if ($project['manager_id'] == $user['id']) { ?>
												<?= $user['full_name'] ?>
									<?php	}
										}
									} ?>
								</td>
								<td><?= $project['start_date'] ?></td>
								<td><?= $project['end_date'] ?></td>
								<td>
									<span class="status-badge status-<?= $project['status'] ?>">
                                            <?php
                                            $status_map = [
                                                "1" => "Chưa giải quyết",
                                                "2" => "Đang làm",
                                                "3" => "Hoàn tất",
                                                "4" => "Tạm ngưng",
                                                "5" => "Hủy bỏ"
                                            ];
                                            echo $status_map[$project['status']] ?? "Không xác định";
                                            ?>
                                        </span>
								</td>
								<!-- Dropdown for actions -->
								<td>
									<div class="dropdown">
										<button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
										<div class="dropdown-content">
											<a href="detail-project.php?id=<?= $project['id'] ?>">Xem</a>
											
										</div>
									</div>
								</td>
							</tr>
						<?php } ?>
				</table>
			<?php } else if (isset($search) && $search !== '') { ?>
				<tr>
					<td colspan="8">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
				</tr>
			<?php } ?>
			</section>
		</div>
		<script type="text/javascript">

			document.querySelectorAll('.dropbtn').forEach(function(btn) {
				btn.addEventListener('click', function(e) {
					e.stopPropagation();
					// Đóng tất cả dropdown khác
					document.querySelectorAll('.dropdown').forEach(function(dd) {
						if (dd !== btn.parentElement) dd.classList.remove('open');
					});
					// Toggle dropdown hiện tại
					btn.parentElement.classList.toggle('open');
				});
			});
			// Đóng dropdown khi click ra ngoài
			document.addEventListener('click', function() {
				document.querySelectorAll('.dropdown').forEach(function(dd) {
					dd.classList.remove('open');
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