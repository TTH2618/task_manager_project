<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/tasks.php";
    include "app/model/user.php";
    include "app/model/progress.php";
    $page_title = "Chi tiết công việc";
    if (!isset($_GET['id'])) {
        header("Location: my_task.php");
        exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
        header("Location: my_task.php");
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
                <div class="project-detail-container">
                    <div class="project-info-card">
                        <div class="project-info-left">
                            <!-- Name and Status row -->
                            <h2><?= htmlspecialchars($task['title']) ?></h2>
                            <div class="project-desc"><?= nl2br(htmlspecialchars($task['description'])) ?></div>
                            <div class="project-members">
                                <strong>Nhân viên thực hiện:</strong>
                                <?php
                                if (!empty($task['employee_id'])) {
                                    $member_ids = explode(',', $task['employee_id']);
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
                            <div><span class="label">Ngày bắt đầu:</span> <?= date('d/m/Y', strtotime($task['start_date'])) ?></div>
                            <div><span class="label">Ngày kết thúc:</span> <?= date('d/m/Y', strtotime($task['end_date'])) ?></div>
                            <div>
                                <span class="label">Trạng thái:</span>
                                <span class="status-badge status-<?= $task['status'] ?>">
                                    <?= get_status_name($task['status']) ?>
                                </span>
                            </div>
                            <div>
                                <span class="label">Người giao:</span>
                                <?php
                                foreach ($users as $user) {
                                    if ($user['id'] == $task['created_by']) {
                                        echo htmlspecialchars($user['full_name']);
                                        break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Modal cập nhật tiến độ -->
                    <div id="taskModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="closeTaskModal">&times;</span>
                            <h3>Cập nhật tiến độ</h3>
                            <form action="app/add-progress.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <input type="hidden" name="manager_id" value="<?= $task['created_by'] ?>">
                                <input type="hidden" name="title" value="<?= $task['title'] ?>">
                                <?php foreach (explode(',', $task['employee_id']) as $eid): ?>
                                    <input type="hidden" name="employee_id[]" value="<?= $eid ?>">
                                <?php endforeach; ?>
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Thêm bình luận</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Gửi file đính kèm</label>
                                        <input class="form-control" type="file" id="file" name="file">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Khu vực hiển thị tiến độ đã cập nhật -->
                    <div id="progressList" class="form-container">
                        <div class="task-header">
                            <h2 class="title">Tiến độ công việc</h2>
                            <div>
                                <form action="app/update-task-status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                    <input type="hidden" name="manager_id" value="<?= $task['created_by'] ?>">
                                    <input type="hidden" name="title" value="<?= htmlspecialchars($task['title']) ?>">
                                    <?php foreach (explode(',', $task['employee_id']) as $eid) { ?>
                                        <input type="hidden" name="employee_id[]" value="<?= $eid ?>">
                                        <?php } ?>
                                        <?php if ($task['status'] != 2) { ?>
                                            <input type="hidden" name="status" value="2">
                                            <button class="complete-btn" type="submit" onclick="return confirm('Bạn có chắc chắn đã hoàn thành công việc này?')"><i class="fa fa-check"></i> Hoàn thành</button>
                                            <button id="openTaskModal" class="btn btn-primary2" type="button">+ Cập nhật tiến độ</button>
                                        <?php }else if ($task['status'] == 2 && $_SESSION['id'] == $task['created_by']) { ?>
                                            <input type="hidden" name="status" value="1">
                                            <button class="cancer-btn" type="submit" onclick="return confirm('Bạn có chắc chắn muốn hủy hoàn thành công việc này?')"><i class="fa fa-undo"></i> Hủy hoàn thành</button>
                                        <?php } ?>
                                </form>
                            </div>
                        </div>
                        <?php
                        $progresses = get_progress_by_task_id($conn, $task['id']);
                        if ($progresses && count($progresses) > 0) {
                            foreach ($progresses as $progress) {
                        ?>
                                <div class="progress-item">
                                    <div class="progress-user">
                                        <?php if ($users != 0) {
                                            foreach ($users as $user) {
                                                if ($progress['user_id'] == $user['id']) {
                                                    echo "<span style='color:#388e3c;'>" . $user['full_name'] . "</span>";
                                                }
                                            }
                                        }
                                        ?>

                                        <span class="progress-date">(<?= date('d/m/Y H:i', strtotime($progress['created_date'])) ?>)</span>
                                    </div>
                                    <div class="progress-comment"><?= nl2br(htmlspecialchars($progress['comment'])) ?></div>
                                    <?php if (!empty($progress['file'])): ?>
                                        <a href="uploads/<?= htmlspecialchars($progress['file']) ?>" target="_blank" class="progress-file">
                                            <i class="fa fa-paperclip"></i> File đính kèm
                                        </a>
                                    <?php endif; ?>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<div style='color:#888;'>Chưa có tiến độ nào.</div>";
                        }
                        ?>
                    </div>
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