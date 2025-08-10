<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "manager")) {
    include "db_connection.php";
    include "app/model/user.php";
    $page_title = "Danh sách nhân viên";
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
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <h4 class="title"><a href="add-user.php"><i class="fa fa-plus"></i> Thêm nhân viên</a></h4>
                <?php } ?>
                <form action="" method="get">
                    <label>Tìm kiếm: </label>
                    <input type="text" id="search" name="search" placeholder="Nhập thông tin nhân viên">
                </form>
                <table class="main-table task-table">
                    <tr>
                        <th>#</th>
                        <th class="sort-th" data-sort="full_name" data-order="asc">Họ tên <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="username" data-order="asc">Username <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="email" data-order="asc">Email <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="department" data-order="asc">Phòng ban <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="role" data-order="asc">Chức vụ <i class="fa fa-sort"></i></th>
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
                    ajaxUrl: 'app/get_users.php',
                    defaultSort: 'role',
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