<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/tasks.php";
	include "app/model/user.php";
	include "app/model/projects.php";
	include "app/model/department.php";
	$page_title = "Trang chủ";
	if ($_SESSION['role'] == "admin") {
		$users = count_users($conn);
		$tasks = count_tasks($conn);
		$count_projects = count_projects($conn);
		$count_departments = count_departments($conn);
		$projects = get_all_projects($conn);
		$completed = count_complete_tasks($conn);
		$incomplete = count_incomplete_tasks($conn);
	} elseif ($_SESSION['role'] == "manager") {
		$users = count_users($conn);
		$count_projects = count_projects_by_manager($conn, $_SESSION['id']);
		$tasks = count_tasks_by_manager($conn, $_SESSION['id']);
		$projects = get_all_projects_by_manager_id($conn, $_SESSION['id']);
		$completed = count_complete_tasks_by_manager($conn, $_SESSION['id']);
		$incomplete = count_incomplete_tasks_by_manager($conn, $_SESSION['id']);
	} elseif ($_SESSION['role'] == "employee") {
		$count_projects = count_projects_by_employee($conn, $_SESSION['id']);
		$tasks = count_tasks_by_employee($conn, $_SESSION['id']);
		$projects = get_all_projects_by_employee_id($conn, $_SESSION['id']);
		$completed = count_complete_tasks_by_employee($conn, $_SESSION['id']);
		$incomplete = count_incomplete_tasks_by_employee($conn, $_SESSION['id']);
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
				<?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager') { ?>
					<div class="dashboard-h2">
						<h2>Trang chủ</h2>
					</div>
					<div class="dashboard">
						<a href="user_list.php">
							<div class="dashboard-item">
								<i class="fa fa-users"></i>
								<h2><?php echo $users; ?></h2>
								<span>Tất cả người dùng</span>
							</div>
						</a>
						<?php if ($_SESSION['role'] == 'admin') { ?>
							<a href="department_list.php">
								<div class="dashboard-item">
									<i class="fa fa-server"></i>
									<h2><?php echo $count_departments; ?></h2>
									<span>Tất cả phòng ban</span>
								</div>
							</a>
						<?php } ?>
						<a href="project_list.php">
							<div class="dashboard-item">
								<i class="fa fa-clipboard-list"></i>
								<h2><?php echo $count_projects; ?></h2>
								<span>Tất cả dự án</span>
							</div>
						</a>
						<a href="task_list.php">
							<div class="dashboard-item">
								<i class="fa fa-tasks"></i>
								<h2><?php echo $tasks; ?></h2>
								<span>Tất cả công việc</span>
							</div>
						</a>
						<a href="task_list.php">
							<div class="dashboard-item">
								<i class="fa fa-check"></i>
								<h2><?php echo $completed; ?></h2>
								<span>Công việc hoàn thành</span>
							</div>
						</a>
						<!-- <a href="task_list.php">
						<div class="dashboard-item">
							<i class="fa fa-spinner"></i>
							<h2><?php echo $incomplete; ?></h2>
							<span>Công việc chưa hoàn thành</span>
						</div>
						</a> -->
					</div>
				<?php } elseif ($_SESSION['role'] == 'employee') { ?>
					<div class="dashboard-h2">
						<h2>Trang chủ</h2>
					</div>
					<div class="dashboard">
						<a href="project_list.php">
							<div class="dashboard-item">
								<i class="fa fa-tasks"></i>
								<h2><?php echo $count_projects; ?></h2>
								<span>Tất cả dự án</span>
							</div>
						</a>
						<a href="task_list.php">
							<div class="dashboard-item">
								<i class="fa fa-clipboard"></i>
								<h2><?php echo $tasks; ?></h2>
								<span>Tất cả công việc</span>
							</div>
						</a>
						<a href="task_list.php">
							<div class="dashboard-item">
								<i class="fa fa-check"></i>
								<h2><?php echo $completed; ?></h2>
								<span>Công việc hoàn thành</span>
							</div>
						</a>
						<a href="task_list.php">
							<div class="dashboard-item">
								<i class="fa fa-spinner"></i>
								<h2><?php echo $incomplete; ?></h2>
								<span>Công việc chưa hoàn thành</span>
							</div>
						</a>
					</div>
				<?php } ?>
				<div class="dashboard">
					<h2>Thống kê dự án</h2>
					<div class="progress-table-wrapper">
						<table class="progress-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Project</th>
									<th>Progress</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<?php
							if (!is_array($projects)) {
								$projects = [];
								echo "<tr><td colspan='5' style='text-align:left;'>Không có dự án nào</td></tr>";
							}
							?>
							<tbody>
								<?php foreach ($projects as $i => $project):
									$total_tasks = get_total_tasks_by_project($conn, $project['id']);
									$completed_tasks = get_completed_tasks_by_project($conn, $project['id']);
									$progress = $total_tasks > 0 ? ($completed_tasks / $total_tasks) * 100 : 0;
								?>
									<tr>
										<td><?= $i + 1 ?></td>
										<td>
											<b><?= htmlspecialchars($project['title']) ?></b><br>
											<small>Due: <?= htmlspecialchars($project['end_date']) ?></small>
										</td>
										<td>
											<div class="project-progress-bar">
												<div class="project-progress-bar-inner" style="width:<?= $progress ?>%"></div>
											</div>
											<div class="project-progress-label">
												<?= number_format($progress, 2) ?>% Complete
											</div>
										</td>
										<td>
											<span class="status-badge status-<?= $project['status'] ?>">
												<?= get_status_name_project($project['status']) ?>
											</span>
										</td>
										<td>
											<a href="detail-project.php?id=<?= $project['id'] ?>" class="btn btn-primary"><i class="fa fa-folder"></i> View</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
			</section>
		</div>
	</body>

	</html>

<?php
} else {
	header("Location: login.php");
	exit();
}
?>