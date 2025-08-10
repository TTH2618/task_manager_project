<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/user.php";
    $users = get_all_users($conn);
    $page_title = "Thêm công việc";

    $current_user = get_users_by_id($conn, $_SESSION['id']);
    // $current_department_id = $current_user['department_id'];
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
                    <form id="projectForm" method="post" action="app/add-task.php">
                        <input type="hidden" name="created_by" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" name="project_id" value="0">
                        <!-- Name and Status row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Tên công việc</label>
                                <input type="text" id="name" name="title" placeholder="Nhập tên công việc" required>
                                <input type="text" id="status" name="status" value="1" hidden>
                            </div>
                            <!-- <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select id="status" name="status" required>
                                    <option value="1" selected readonly>Chưa hoàn thành</option>
                                    <option value="1">Chưa giải quyết</option>
                                    <option value="2">Đang làm</option>
                                    <option value="3">Hoàn tất</option>
                                    <option value="4">Tạm ngưng</option>
                                    <option value="5">Hủy bỏ</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="endDate">Ngày kết thúc</label>
                                <input type="date" id="endDate" name="end_date" placeholder="dd/mm/yyyy" required>
                            </div>
                        </div>

                        <!-- Members End Date row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="teamMembers">Thành viên thực hiện công việc</label>
                                <select id="projectManagere" name="employee_id[]" class="multi-select-input" multiple="multiple" required>
                                    <?php if ($users != 0) {
                                        if ($_SESSION['role'] == 'admin') {
                                            foreach ($users as $user) {
                                                if($user['role'] != 'admin') {
                                    ?>
                                                <option value="<?= $user['id'] ?>" <?php echo isset($employee_id) && in_array($user['id'], explode(',', $employee_id)) ? 'selected' : '' ?>><?= $user['full_name'] ?></option>
                                                <?php }
                                            }
                                        } elseif ($_SESSION['role'] == 'manager') {
                                            foreach ($users as $user) {
                                                if ($user['department_id'] == $current_user['department_id'] && $user['role'] == 'employee') {
                                                ?>
                                                    <option value="<?= $user['id'] ?>" <?php echo isset($employee_id) && in_array($user['id'], explode(',', $employee_id)) ? 'selected' : '' ?>><?= $user['full_name'] ?></option>
                                    <?php }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                            
                        </div>
                        <!-- Description section -->
                        <div class="form-row" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label for="description">Mô tả công việc</label>
                                <textarea id="description" name="description" class="description-textarea" rows="5" cols="50"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="btn-group"><button class="add-btn" onclick="return confirm('Bạn có chắc chắn thêm công việc này?')">Thêm công việc</button></div>
                            <div class="btn-group"><a class="cancer-btn" href="task_list.php" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy bỏ</a></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
} else {
    $em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>