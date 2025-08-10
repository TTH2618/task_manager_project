<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/tasks.php";
	include "app/model/user.php";
    $page_title = "Công việc của tôi";
    $sort = $_GET['sort'] ?? 'id';
	$order = $_GET['order'] ?? 'asc';
	$allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
	$allowed_order = ['asc','desc'];
	if (!in_array($sort, $allowed_sort)) $sort = 'id';
	if (!in_array($order, $allowed_order)) $order = 'asc';

	// Xử lý tìm kiếm
	if (isset($_GET['search'])) {
		$search = trim($_GET['search']);
		if ($search === '') {
			$tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
		} else {
            // Tìm user theo tên
            $users_found = find_users_by_name($conn, $search);
            if ($users_found && count($users_found) > 0) {
                // Lấy id các user tìm được
                $user_ids = array_column($users_found, 'id');
                // Lấy task theo creator_id
                $tasks = find_tasks_by_created_by_ids($conn, $user_ids, $sort, $order); 
            } else {
                // Nếu không tìm thấy user, tìm theo task như cũ
                $tasks = find_tasks($conn, $search, $sort, $order);
            }
        }
	} else {
		$tasks = get_all_tasks_by_user_id($conn, $_SESSION['id'], $sort, $order);
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
                <?php if ($_SESSION['role'] == 'manager') { ?>
                    <h4 class="title"><a href="create_task.php"><i class="fa fa-plus"></i> Thêm công việc mới</a></h4>
                <?php } else { ?>
                    <h2 class="title">Công việc của tôi</h2>
                <?php } ?>
                <form action="" method="get">
					<label>Tìm kiếm: </label>
					<input type="text" name="search" placeholder="Nhập thông tin công việc">
					<button class="search-btn">Tìm kiếm</button>
				</form>
                <?php if (isset($_GET['success'])) { ?>
                    <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?>  
                    </div>
                <?php } 
                ?>
                
                <table class="main-table">
                <tr>
					<th>
						<a href="?<?= isset($search) ? 'search='.urlencode($search).'&' : '' ?>sort=id&order=<?= ($sort=='id' && $order=='asc') ? 'desc' : 'asc' ?>">ID <i class="fa fa-sort"></i></a>
					</th>
					<th>
						<a href="?<?= isset($search) ? 'search='.urlencode($search).'&' : '' ?>sort=title&order=<?= ($sort=='title' && $order=='asc') ? 'desc' : 'asc' ?>">Tên công việc <i class="fa fa-sort"></i></a>
					</th>
					<th>
						<a href="?<?= isset($search) ? 'search='.urlencode($search).'&' : '' ?>sort=created_by&order=<?= ($sort=='created_by' && $order=='asc') ? 'desc' : 'asc' ?>">Người giao <i class="fa fa-sort"></i></a>
					</th>
					<th>
						<a href="?<?= isset($search) ? 'search='.urlencode($search).'&' : '' ?>sort=end_date&order=<?= ($sort=='end_date' && $order=='asc') ? 'desc' : 'asc' ?>">Ngày kết thúc <i class="fa fa-sort"></i></a>
					</th>
					<th>
						<a href="?<?= isset($search) ? 'search='.urlencode($search).'&' : '' ?>sort=status&order=<?= ($sort=='status' && $order=='asc') ? 'desc' : 'asc' ?>">Trạng thái <i class="fa fa-sort"></i></a>
					</th>
					<th>Hành động</th>
				</tr>
                    <?php if($tasks !=0){ ?>
                    <?php foreach ($tasks as $task){ ?>
                        <tr>
                        <td><?=$task['id']?></td>
                        <td><?=$task['title']?></td>
                        <td>
                            <?php if($users !=0){
                                foreach($users as $user) {
                                    if ($task['created_by'] == $user['id']) { ?>
                                        <?=$user['full_name']?>
                            <?php	}
                                }
                            }?>
                        </td>
                        
                        <td><?=$task['end_date']?></td>
                        <td>
                            <span class="status-badge status-<?= $task['status'] ?>">
                                            <?php
                                            $status_map = [
                                                "1" => "Chưa giải quyết",
                                                "2" => "Đang làm",
                                                "3" => "Hoàn tất",
                                                "4" => "Tạm ngưng",
                                                "5" => "Hủy bỏ"
                                            ];
                                            echo $status_map[$task['status']] ?? "Không xác định";
                                            ?>
                                        </span>
                        </td>
                        <td>
                            <a href="detail-task-employee.php?id=<?=$task['id']?>" class="edit-btn">Chi tiết</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php }else{?>               
                        <td colspan="6" class="no-data">Không có công việc nào</td>
                    <?php } ?>	
                </table>
            
            </section>
        
	</div>
<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(3)");
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