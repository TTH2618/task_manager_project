<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/user.php";
    include "app/model/department.php";
    $page_title = "Chi tiết phòng ban";
    // Lấy thông tin phòng ban
    if (!isset($_GET['id'])) {
        header("Location: department_list.php");
        exit();
    }
    $id = $_GET['id'];
    $department = get_department_by_id($conn, $id);

    if ($department == 0) {
        header("Location: department_list.php");
        exit();
    }
    $users = get_all_users_by_department_id($conn, $id);
    $tab = $_GET['tab'] ?? 'employee';
?>

    <!DOCTYPE html>
    <html>
    <?php include "inc/head.php";?>
    <body>
        <input type="checkbox" id="checkbox">
        <?php include "inc/header.php" ?>
        <div class="body">
            <?php include "inc/nav.php" ?>
            <section class="user">
                <h4 class="title"><a href="javascript:history.back()">Danh sách phòng ban</a></h4>
                <div class="project-detail-container">
                    <div class="project-info-card">
                        <div class="project-info-left">
                            <h2><?= htmlspecialchars($department['name']) ?></h2>
                            <div class="project-desc"><?= nl2br(htmlspecialchars($department['description'])) ?></div>
                        </div>

                        <div class="project-info-right">
                            <div><span class="label">Ngày tạo:</span> <?= date('d/m/Y', strtotime($department['create_at'])) ?></div>
                            <div>
                                <span class="label">Trưởng ban:</span>
                                <?php
                                if ($users !=0) {
                                foreach ($users as $user) {
                                    if ($user['role'] == 'manager') {
                                        echo htmlspecialchars($user['full_name']);
                                        break;
                                    }
                                }
                            }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab">
                    <a class="tablinks<?php if ($tab == 'employee') echo ' active'; ?>" href="?id=<?= $id ?>&tab=employee">Nhân viên</a>
                    <a class="tablinks<?php if ($tab == 'project') echo ' active'; ?>" href="?id=<?= $id ?>&tab=project">Dự án</a>
                </div>

                <div id="employee" class="tabcontent">
                    <div class="task-header">
                        <h3>Danh sách nhân viên</h3>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <button id="openEmployeeModal" class="btn btn-primary" type="button">+ Thêm nhân viên</button>
                        <?php } ?>
                    </div>
                    <div>
                        <label>Tìm kiếm: </label>
                        <input type="text" id="searchEmployee1" name="search" placeholder="Nhập thông tin nhân viên" style="width: 230px;">
                    </div>
                    <table class="main-table task-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="sort-th1" data-sort="full_name" data-order="asc">Họ tên <i class="fa fa-sort"></i></th>
                                <th class="sort-th1" data-sort="username" data-order="asc">Username <i class="fa fa-sort"></i></th>
                                <th class="sort-th1" data-sort="email" data-order="asc">Email <i class="fa fa-sort"></i></th>
                                <th class="sort-th1" data-sort="role" data-order="asc">Chức vụ <i class="fa fa-sort"></i></th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody1">
                            <!-- Kết quả AJAX sẽ render ở đây -->
                        </tbody>
                    </table>
                </div>
                <div id="project" class="tabcontent">
                    <div class="task-header">
                        <h3>Danh sách Dự án</h3>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <a class="btn btn-primary" href="create_project.php"><i class="fa fa-plus"></i> Thêm dự án mới</a>
                        <?php } ?>
                    </div>
                    <form action="" method="get">
                        <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        <label>Tìm kiếm: </label>
                        <input type="text" id="searchProject" name="search" placeholder="Nhập thông tin dự án">
                        <button class="search-btn">Tìm kiếm</button>
                    </form>
                    <table class="main-table task-table">
                        <tr>
                            <th>#</th>
                            <th class="sort-th1" data-sort="title" data-order="asc">Tên dự án <i class="fa fa-sort"></i></th>
                            <th class="sort-th1" data-sort="manager_id" data-order="asc">Người quản lý <i class="fa fa-sort"></i></th>
                            <th class="sort-th1" data-sort="start_date" data-order="asc">Ngày bắt đầu <i class="fa fa-sort"></i></th>
                            <th class="sort-th1" data-sort="end_date" data-order="asc">Ngày kết thúc <i class="fa fa-sort"></i></th>
                            <th class="sort-th1" data-sort="status" data-order="asc">Trạng thái <i class="fa fa-sort"></i></th>
                            <th>Hành động</th>
                        </tr>
                        <tbody id="projectTableBody">
                            <!-- Kết quả AJAX sẽ render ở đây -->
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- Employee Modal -->
        <div id="employeeModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeEmployeeModal">&times;</span>
                <h3>Thêm nhân viên</h3>
                <form action="" method="get">
                    <div>
                        <label>Tìm kiếm: </label>
                        <input type="text" id="searchEmployee" name="search" placeholder="Nhập thông tin nhân viên" style="width: 230px;">
                    </div>
                    <table class="main-table task-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="sort-th" data-sort="full_name" data-order="asc">Họ tên <i class="fa fa-sort"></i></th>
                                <th class="sort-th" data-sort="username" data-order="asc">Username <i class="fa fa-sort"></i></th>
                                <th class="sort-th" data-sort="email" data-order="asc">Email <i class="fa fa-sort"></i></th>
                                <th class="sort-th" data-sort="role" data-order="asc">Chức vụ <i class="fa fa-sort"></i></th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            <!-- Kết quả AJAX sẽ render ở đây -->
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        </section>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Tự động mở tab đúng khi load lại trang
                var urlParams = new URLSearchParams(window.location.search);
                var tab = urlParams.get('tab') || localStorage.getItem('currentTab') || 'employee';

                // Ẩn tất cả tab
                $('.tabcontent').hide();

                // Bật tab đúng
                $('#' + tab).show();

                // Đánh dấu nút tab đúng
                $('.tablinks').removeClass('active');
                $('.tablinks[href*="tab=' + tab + '"]').addClass('active');

                // Khởi tạo AJAX table cho modal thêm nhân viên
                initAjaxTable({
                    openBtnSelector: '#openEmployeeModal',
                    modalSelector: '#employeeModal',
                    closeBtnSelector: '#closeEmployeeModal',
                    searchInputSelector: '#searchEmployee',
                    tableBodySelector: '#employeeTableBody',
                    sortThSelector: '.sort-th',
                    ajaxUrl: 'app/get_employees_wo_department.php',
                    defaultSort: 'username',
                    defaultOrder: 'asc',
                    extraParams: {
                        id: <?= $id ?>
                    }
                });

                // Khởi tạo AJAX table cho danh sách nhân viên hiện tại
                initAjaxTable({
                    openBtnSelector: null, // Không dùng modal
                    modalSelector: null,
                    closeBtnSelector: null,
                    searchInputSelector: '#searchEmployee1',
                    tableBodySelector: '#employeeTableBody1',
                    sortThSelector: '.sort-th1',
                    ajaxUrl: 'app/get_employee_w_department.php',
                    defaultSort: 'role',
                    defaultOrder: 'asc',
                    extraParams: {
                        id: <?= $id ?>
                    }
                });

                initAjaxTable({
                    openBtnSelector: null, // Không dùng modal
                    modalSelector: null,
                    closeBtnSelector: null,
                    searchInputSelector: '#searchProject',
                    tableBodySelector: '#projectTableBody',
                    sortThSelector: '.sort-th1',
                    ajaxUrl: 'app/get_project_department.php',
                    defaultSort: 'status',
                    defaultOrder: 'desc',
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

