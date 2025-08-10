<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "db_connection.php";
	include "app/model/projects.php";
    include "app/model/user.php";
    include "app/model/department.php";
    $page_title = "Chỉnh sửa dự án";
    if(!isset($_GET['id'])) {
        header("Location: task_list.php");
        exit();
    }
    $id = $_GET['id'];
	$project = get_project_by_id($conn, $id);

    if($project == 0) {
        header("Location: task_list.php");
        exit();
    }
    $users = get_all_users($conn);
    $departments = get_all_departments($conn);
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
			<h4 class="title"><a href="project_list.php">Danh sách dự án</a></h4> 
            <div class="form-container">
                <form id="projectForm" method="post" action="app/update-project.php">
                <!-- Name and Status row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Tên dự án</label>
                            <input type="text" id="name" name="title" value="<?=$project['title']?>" required>
                        </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select id="status" name="status" required >
                            <option value="" disabled selected>Chọn trạng thái</option>
                            <option value="1" <?= $project['status'] == "1" ? 'selected' : '' ?>>Đang thực hiện</option>
                            <option value="2" <?= $project['status'] == "2" ? 'selected' : '' ?>>Hoàn tất</option>
                            <option value="3" <?= $project['status'] == "3" ? 'selected' : '' ?>>Tạm ngưng</option>
                            <option value="4" <?= $project['status'] == "4" ? 'selected' : '' ?>>Hủy bỏ</option>
                        </select>
                    </div>
                    </div>

                <!-- Start Date and End Date row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Ngày bắt đầu</label>
                            <input type="date" id="startDate" name="start_date" placeholder="dd/mm/yyyy" value="<?=$project['start_date']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Ngày kết thúc</label>
                            <input type="date" id="endDate" name="end_date" placeholder="dd/mm/yyyy" value="<?=$project['end_date']?>" required>
                        </div>
                    </div>

                <!-- Project Manager and Project Team Members row -->
                    <div class="form-row">
                        <div class="form-group">
                                <label for="department">Phòng ban</label>
                                <select id="department" name="department_id" required>
                                    <option value="" disabled selected>Chọn phòng ban</option>
                                    <?php if ($departments != 0) {
                                        foreach ($departments as $department) {
                                            if($project['department_id'] == $department['id']){ ?>
                                                <option selected value="<?=$department['id']?>"><?=$department['name']?></option>
                                    <?php } else { ?>
                                            <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
                                    <?php }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <div class="form-group">
                            <label for="projectManager">Quản lý dự án</label>
                            <select id="projectManager" name="manager_id" required>
                                <option value="" disabled selected>Chọn quản lý</option>
                                <?php if ($users !=0) {
                                    foreach ($users as $user) {
                                        if($user['role'] == 'manager') {
                                            if($project['manager_id'] == $user['id']){ ?>
                                                <option selected value="<?=$user['id']?>"><?=$user['full_name']?></option>
                                      <?php }else{  ?> 
                                                <option 
                                                    value="<?= $user['id'] ?>"
                                                    data-department-id="<?= $user['department_id'] ?>">
                                                    <?= $user['full_name'] ?>
                                                </option>
                                        <?php 
                                            }
                                        }
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="teamMembers">Thành viên thực hiện dự án</label>
                            <select id="employee" name="employee_id[]" class="multi-select-input" multiple="multiple" required>
                                <?php $selected_employee_ids = $project['employee_id']; ?>
                                <?php if ($users != 0) {
                                    foreach ($users as $user) {
                                        if($user['role'] == 'employee') {
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
                    </div>

                     <!-- Description section -->
                    <div class="form-row" style="grid-template-columns: 1fr;">
                        <div class="form-group">
                            <label for="description">Mô tả công việc</label>
                            <textarea id="description" name="description" class="description-textarea" rows="5" cols="50"><?=$project['description']?></textarea>
                        </div>
                    </div> 
                    <div class="form-row">
                        <div class="btn-group"><button class="add-btn" onclick="return confirm('Bạn có chắc chắn muốn lưu thay đổi?')">Sửa công việc</button></div>
                        <div class="btn-group"><a class="cancer-btn" href="project_list.php" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy bỏ</a></div>
                    </div>
                    <input type="text" name="id" value="<?=$project['id']?>" hidden>
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

    const allEmployees = [
                <?php if ($users != 0) {
                    foreach ($users as $user) {
                        if ($user['role'] == 'employee') {
                            echo "{ id: '{$user['id']}', name: '" . addslashes($user['full_name']) . "', department_id: '{$user['department_id']}' },";
                        }
                    }
                } ?>
            ];
            document.addEventListener('DOMContentLoaded', function() {
                const departmentSelect = document.getElementById('department');
                const managerSelect = document.getElementById('projectManager');
                const employeeSelect = document.getElementById('employee');

                departmentSelect.addEventListener('change', function() {
                    const selectedDepartmentId = this.value;

                    //Lọc Manager 
                    managerSelect.value = '';
                    for (let i = 0; i < managerSelect.options.length; i++) {
                        const option = managerSelect.options[i];
                        const optionDeptId = option.getAttribute('data-department-id');

                        if (!optionDeptId) {
                            option.hidden = false;
                            option.disabled = false;
                            continue;
                        }

                        if (optionDeptId === selectedDepartmentId) {
                            option.hidden = false;
                            option.disabled = false;
                        } else {
                            option.hidden = true;
                            option.disabled = true;
                        }
                    }

                    // Lọc Employee
                    // Xóa tất cả option cũ
                    employeeSelect.innerHTML = '';
                    // Lọc & thêm option phù hợp
                    allEmployees.forEach(emp => {
                        if (emp.department_id === selectedDepartmentId) {
                            const option = document.createElement('option');
                            option.value = emp.id;
                            option.textContent = emp.name;
                            employeeSelect.appendChild(option);
                        }
                    });
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