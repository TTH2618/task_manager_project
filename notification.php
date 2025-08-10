<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/notification.php";
    $page_title = "Thông báo của tôi";
    $notifications = get_all_my_notifications($conn, $_SESSION['id']);


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
                 <div class="task-header">
                    <h2 class="title">Tất cả thông báo</h2>
                    <a class="delete-btn" href="app/delete-notification.php" onclick="return confirm('Bạn có chắc muốn xóa tất cả thông báo?')">Xóa tất cả thông báo</a>
                 </div>    
                <?php if ($notifications != 0) { ?>
                    <table class="main-table task-table">
                        <tr>
                            <th>
                                Thông tin
                            </th>
                            <th>
                                Phân loại
                            </th>
                            <th>
                                Hành động
                            </th>
                        </tr>
                        <?php foreach ($notifications as $notification) { ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?php if ($notification['type'] == "Dự án mới") { ?>
                                            <a class="view-detail" href="detail-project.php?id=<?= $notification['project_id'] ?>"><?= $notification['message'] ?></a>
                                        <?php } else { ?>
                                            <a class="view-detail" href="detail-task-employee.php?id=<?= $notification['task_id'] ?>"><?= $notification['message'] ?></a>
                                        <?php } ?>
                                    </strong>
                                    <small style="padding-top: 4px;"><?= $notification['date'] ?></small>
                                </td>
                                <td><?= $notification['type'] ?></td>
                                <td>
                                    <a class="delete-btn" href="app/delete-notification.php?id=<?= $notification['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    Bạn không có thông báo nào
                <?php } ?>
            </section>

        </div>
    </body>

    </html>

<?php
} else {
    $em = "Log in first";
    header("Location: login.php?error=$em");
    exit();
}
?>