<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/tasks.php";
    include "app/model/user.php";
    $page_title = "Chỉnh sửa công việc";
    if(!isset($_GET['id'])) {
        header("Location: task_list.php");
        exit();
    }
    $id = $_GET['id'];
	$task = get_task_by_id($conn, $id);
    
    if($task == 0) {
        header("Location: task_list.php");
        exit();
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
			<h4 class="title"><a href="task_list.php">Danh sách công việc</a></h4> 
            <div class="form-container">
                <form id="projectForm" method="post" action="app/update-task.php">
                <!-- Name and Status row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Tên công việc</label>
                            <input type="text" id="name" name="title" value="<?=$task['title']?>" required>
                        </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status" required >
                            <option value="" disabled selected>Chọn trạng thái</option>
                            <option value="1" <?= $task['status'] == '1' ? 'selected' : '' ?>>Chưa hoàn thành</option>
                            <option value="2" <?= $task['status'] == '2' ? 'selected' : '' ?>>Hoàn tất</option>
                            <option value="3" <?= $task['status'] == '3' ? 'selected' : '' ?>>Hủy bỏ</option>
                        </select>
                    </div>
                    </div>

                <!-- Employee and End Date row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="teamMembers">Thành viên thực hiện công việc</label>
                            <select id="projectManagere" name="employee_id[]" class="multi-select-input" multiple="multiple" required>
                                <?php $selected_employee_ids = $task['employee_id']; ?>
                                <?php if ($users != 0) {
                                    foreach ($users as $user) {
                                        if($user['role'] != 'admin') {
                                            // Kiểm tra xem user ID có trong danh sách đã chọn không
                                            $is_selected = '';
                                            if(!empty($selected_employee_ids)) {
                                                $selected_array = explode(',', $selected_employee_ids);
                                                if(in_array($user['id'], $selected_array)) {
                                                    $is_selected = 'selected';
                                                }
                                            }
                                ?>        
                                <option value="<?=$user['id']?>" <?=$is_selected?>><?=$user['full_name']?></option> 
                                <?php }
                                    }
                                }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Ngày kết thúc</label>
                            <input type="date" id="endDate" name="end_date" placeholder="dd/mm/yyyy" value="<?=$task['end_date']?>" required>
                        </div>
                    </div>

                    <!-- Description section -->
                    <div class="form-row" style="grid-template-columns: 1fr;">
                        <div class="form-group">
                            <label for="description">Mô tả công việc</label>
                            <textarea id="description" name="description" class="description-textarea" rows="5" cols="50"><?=$task['description']?></textarea>
                        </div>
                    </div> 
                    <div class="form-row">
                        <div class="btn-group"><button class="add-btn" onclick="return confirm('Bạn có chắc chắn muốn lưu thay đổi?')">Sửa công việc</button></div>
                        <div class="btn-group"><a class="cancer-btn" href="task_list.php" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy bỏ</a></div>
                    </div>
                    <input type="text" name="id" value="<?=$task['id']?>" hidden>
                </form>
            </div>
		</section>
	</div>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
    $('.multi-select-input').select2({
        placeholder: "Chọn nhân viên"
        });
    });
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