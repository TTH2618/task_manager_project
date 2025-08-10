<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/projects.php";
    include "app/model/user.php";
    $page_title = "Chi tiết dự án";


    if (!isset($_GET['id'])) {
        header("Location: project_list.php");
        exit();
    }
    $id = $_GET['id'];
    $project = get_project_by_id($conn, $id);

    if ($project == 0) {
        header("Location: project_list.php");
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
                <h4 class="title"><a href="project_list.php">Danh sách dự án</a></h4>
                <div class="project-detail-container">
                    <div class="project-info-card">
                        <div class="project-info-left">
                            <h2><?= htmlspecialchars($project['title']) ?></h2>
                            <div class="project-desc"><?= nl2br(htmlspecialchars($project['description'])) ?></div>
                            <div class="project-members">
                                <strong>Thành viên:</strong>
                                <?php
                                if (!empty($project['employee_id'])) {
                                    $member_ids = explode(',', $project['employee_id']);
                                    $member_names = [];
                                    foreach ($users as $user) {
                                        if (in_array($user['id'], $member_ids)) {
                                            $member_names[] = $user['full_name'];
                                        }
                                    }
                                    if (count($member_names) > 0) {
                                        echo '<ul>';
                                        foreach ($member_names as $name) {
                                            echo "<li>$name</li>";
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo 'Không có thành viên';
                                    }
                                } else {
                                    echo 'Không có thành viên';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="project-info-right">
                            <div><span class="label">Ngày bắt đầu:</span> <?= date('d/m/Y', strtotime($project['start_date'])) ?></div>
                            <div><span class="label">Ngày kết thúc:</span> <?= date('d/m/Y', strtotime($project['end_date'])) ?></div>
                            <div>
                                <span class="label">Trạng thái:</span>
                                <span class="status-badge status-<?= $project['status'] ?>">
                                    <?php
                                    $status_map = [
                                        "1" => "Chưa bắt đầu",
                                        "2" => "Đang tiến hành",
                                        "3" => "Hoàn thành",
                                        "4" => "Tạm dừng",
                                        "5" => "Đã hủy"
                                    ];
                                    echo $status_map[$project['status']] ?? "Không xác định";
                                    ?>
                                </span>
                            </div>
                            <div>
                                <span class="label">Quản lý:</span>
                                <?php
                                foreach ($users as $user) {
                                    if ($user['id'] == $project['manager_id']) {
                                        echo htmlspecialchars($user['full_name']);
                                        break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="task-header">
                        <h3>Danh sách công việc</h3>
                        <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'manager') { ?>
                            <button id="openTaskModal" class="btn btn-primary" type="button">+ Tạo công việc mới</button>
                        <?php } ?>
                    </div>

                    <table class="main-table task-table">
                        <tr>
                            <th>#</th>
                            <th class="sort-th" data-sort="title" data-order="asc">Tên công việc <i class="fa fa-sort"></i></th>
                            <th class="sort-th" data-sort="employee_id" data-order="asc">Giao cho <i class="fa fa-sort"></i></th>
                            <th class="sort-th" data-sort="end_date" data-order="asc">Hạn cuối <i class="fa fa-sort"></i></th>
                            <th class="sort-th" data-sort="time_left" data-order="asc">Thời hạn <i class="fa fa-sort"></i></th>
                            <th class="sort-th" data-sort="status" data-order="asc">Trạng thái <i class="fa fa-sort"></i></th>
                            <th>Hành động</th>
                        </tr>
                        <tbody id="TableBody">
						<!-- Kết quả AJAX sẽ render ở đây -->
					    </tbody>
                    </table>
                </div>
                <!-- Task Modal -->
                <div id="taskModal" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeTaskModal">&times;</span>
                        <h3>Tạo công việc mới trong dự án</h3>
                        <form action="app/add-task.php" method="POST">
                            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                            <input type="hidden" name="created_by" value="<?= $_SESSION['id'] ?>">
                            <input type="hidden" name="status" value="1">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Tên công việc</label>
                                    <input type="text" id="name" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="endDate">Ngày kết thúc</label>
                                    <input type="date" id="endDate" name="end_date" placeholder="dd/mm/yyyy" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select id="status" name="status" required>
                                        <option value="" disabled selected>Chọn trạng thái</option>
                                        <option value="1">Chưa giải quyết</option>
                                        <option value="2">Đang làm</option>
                                        <option value="3">Hoàn tất</option>
                                        <option value="4">Tạm ngưng</option>
                                        <option value="5">Hủy bỏ</option>
                                    </select>
                                </div> -->
                            </div>

                            <!-- Members End Date row -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="teamMembers">Thành viên thực hiện</label>
                                    <select id="employee" name="employee_id[]" class="multi-select-input" multiple="multiple" required>">
                                        <?php if ($users != 0 && !empty($project['employee_id'])) {
                                            $project_members = explode(',', $project['employee_id']);
                                            foreach ($users as $user) {
                                                if ($user['role'] == 'employee' && in_array($user['id'], $project_members)) {
                                        ?>
                                                    <option value="<?= $user['id'] ?>" <?php echo isset($employee_id) && in_array($user['id'], explode(',', $employee_id)) ? 'selected' : '' ?>><?= $user['full_name'] ?></option>
                                        <?php }
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
                            <div style="margin-top:10px;">
                                <button type="submit" class="btn btn-success">Tạo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script type="text/javascript">
            // Modal for creating new task
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('openTaskModal').onclick = function() {
                    document.getElementById('taskModal').style.display = 'block';
                };
                document.getElementById('closeTaskModal').onclick = function() {
                    document.getElementById('taskModal').style.display = 'none';
                };
                window.onclick = function(event) {
                    if (event.target == document.getElementById('taskModal')) {
                        document.getElementById('taskModal').style.display = 'none';
                    }
                };
            });
            $(document).ready(function() {
                $('.multi-select-input').select2({

                });
            });

            $(document).ready(function() {
				initAjaxTable({
					openBtnSelector: null, // Không dùng modal
					modalSelector: null,
					closeBtnSelector: null,
					searchInputSelector: '#search',
					tableBodySelector: '#TableBody',
					sortThSelector: '.sort-th',
					ajaxUrl: 'app/get_tasks_project.php',
					defaultSort: 'time_left',
					defaultOrder: 'asc',
                    extraParams: {
                        id: <?= $id ?>
                    }
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