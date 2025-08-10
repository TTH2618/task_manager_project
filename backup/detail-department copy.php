<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "db_connection.php";
    include "app/model/projects.php";
    include "app/model/user.php";
    include "app/model/tasks.php";
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
    if ($tab == 'employee') {
        $sort = $_GET['sort'] ?? 'role';
        $order = $_GET['order'] ?? 'asc';
        $allowed_sort = ['id', 'full_name', 'username', 'email', 'role'];
        $allowed_order = ['asc', 'desc'];
        if (!in_array($sort, $allowed_sort)) $sort = 'id';
        if (!in_array($order, $allowed_order)) $order = 'asc';

        // Xử lý tìm kiếm
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            if ($search === '') {
                $users = get_all_users_by_department_id($conn, $id, $sort, $order);
            } else {
                $users = find_users_by_department_id($conn, $id, $search, $sort, $order);
            }
        } else {
            $users = get_all_users_by_department_id($conn, $id, $sort, $order);
        }
    } else if ($tab == 'project') {
        $sort = $_GET['sort'] ?? 'create_at';
        $order = $_GET['order'] ?? 'desc';
        $tab = $_GET['tab'] ?? 'project';
        $allowed_sort = ['id', 'title', 'manager_id', 'start_date', 'end_date', 'status'];
        $allowed_order = ['asc', 'desc'];
        if (!in_array($sort, $allowed_sort)) $sort = 'id';
        if (!in_array($order, $allowed_order)) $order = 'asc';

        // Xử lý tìm kiếm
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            if ($search === '') {
                $projects = get_all_projects_by_department_id($conn, $id, $sort, $order);
            } else {
                $projects = find_projects($conn, $search, $sort, $order);
            }
        } else {
            $projects = get_all_projects_by_department_id($conn, $id, $sort, $order);
        }
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
                <?php
                if ($_SESSION['role'] == "admin") { ?>
                    <h4 class="title"><a href="department_list.php">Danh sách phòng ban</a></h4>
                <?php } else if ($_SESSION['role'] == "employee" || $_SESSION['role'] == "manager") { ?>
                    <h4 class="title"><a href="my_project.php">Danh sách dự án</a></h4>
                <?php } ?>

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
                                foreach ($users as $user) {
                                    if ($user['role'] == 'manager') {
                                        echo htmlspecialchars($user['full_name']);
                                        break;
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
                    <form action="" method="get">
                        <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        <label>Tìm kiếm: </label>
                        <input type="text" name="search" placeholder="Nhập thông tin nhân viên">
                        <button class="search-btn">Tìm kiếm</button>
                    </form>
                    <table class="main-table task-table">
                        <tr>
                            <th>#</th>
                            <?php
                            render_sort_th('Họ tên', 'full_name', $sort, $order, $search ?? '');
                            render_sort_th('Username', 'username', $sort, $order, $search ?? '');
                            render_sort_th('Email', 'email', $sort, $order, $search ?? '');
                            render_sort_th('Chức vụ', 'role', $sort, $order, $search ?? '');
                            ?>
                            <th>Hành động</th>
                        </tr>
                        <?php if ($users != 0) { ?>
                            <?php $i = 0; ?>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td><?= ++$i ?></td>
                                    <td><?= $user['full_name'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td>
                                        <?php if ($user['role'] == 'employee')
                                            echo "Nhân viên";
                                        else if ($user['role'] == 'manager')
                                            echo "Trưởng ban";
                                        else if ($user['role'] == 'admin')
                                            echo "Quản trị viên";
                                        ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                                            <div class="dropdown-content">
                                                <a href="profile.php?id=<?= $user['id'] ?>">Xem</a>
                                                <a href="edit-user.php?id=<?= $user['id'] ?>">Sửa</a>
                                                <a href="delete-user.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Chưa có thành viên</td>
                            </tr>
                        <?php } ?>
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
                        <input type="text" name="search" placeholder="Nhập thông tin nhân viên">
                        <button class="search-btn">Tìm kiếm</button>
                    </form>
                    <table class="main-table task-table">
                        <tr>
                            <th>#</th>
                            <?php
                            render_sort_th('Tên dự án', 'title', $sort, $order, $search ?? '');
                            render_sort_th('Người quản lý', 'manager_id', $sort, $order, $search ?? '');
                            render_sort_th('Ngày bắt đầu', 'start_date', $sort, $order, $search ?? '');
                            render_sort_th('Ngày kết thúc', 'end_date', $sort, $order, $search ?? '');
                            render_sort_th('Trạng thái', 'status', $sort, $order, $search ?? '');
                            ?>
                            <th>Hành động</th>
                        </tr>
                        <?php if ($projects != 0) { ?>
                            <?php $i = 0;
                            foreach ($projects as $project) { ?>
                                <tr>
                                    <td><?= ++$i ?></td>
                                    <td>
                                        <strong><?= $project['title'] ?></strong>
                                        <small><?= $project['description'] ?></small>
                                    </td>
                                    <td>
                                        <?php if ($users != 0) {
                                            foreach ($users as $user) {
                                                if ($project['manager_id'] == $user['id']) { ?>
                                                    <?= $user['full_name'] ?>
                                        <?php    }
                                            }
                                        } ?>
                                    </td>
                                    <td><?= $project['start_date'] ?></td>
                                    <td><?= $project['end_date'] ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $project['status'] ?>">
                                            <?php
                                            $status_map = [
                                                "1" => "Chưa giải quyết",
                                                "2" => "Đang làm",
                                                "3" => "Hoàn tất",
                                                "4" => "Tạm ngưng",
                                                "5" => "Hủy bỏ"
                                            ];
                                            echo $status_map[$project['status']] ?? "Không xác định";
                                            ?>
                                        </span>
                                    </td>
                                    <!-- Dropdown for actions -->
                                    <td>
                                        <div class="dropdown">
                                            <button class="dropbtn">Hành động <i class="fa fa-caret-down"></i></button>
                                            <div class="dropdown-content">
                                                <a href="detail-project.php?id=<?= $project['id'] ?>">Xem</a>
                                                <a href="edit-project.php?id=<?= $project['id'] ?>">Sửa</a>
                                                <a href="app/delete-project.php?id=<?= $project['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else if (isset($search) && $search !== '') { ?>
                            <tr>
                                <td colspan="8">Không tìm thấy kết quả cho: <strong><?php echo htmlspecialchars($search); ?></strong></td>
                            </tr>
                        <?php } ?>
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
            var navList = document.querySelectorAll("#navList li a");
            navList.forEach(function(link) {
                if (link.href.includes("project_list.php")) {
                    link.parentElement.classList.add("active");
                }
            });

            document.querySelectorAll('.dropbtn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Đóng tất cả dropdown khác
                    document.querySelectorAll('.dropdown').forEach(function(dd) {
                        if (dd !== btn.parentElement) dd.classList.remove('open');
                    });
                    // Toggle dropdown hiện tại
                    btn.parentElement.classList.toggle('open');
                });
            });
            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown').forEach(function(dd) {
                    dd.classList.remove('open');
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
            $(document).ready(function() {
                $('.multi-select-input').select2({

                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                var urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('show_modal') === '1') {
                    var modal = document.getElementById('taskModal');
                    if (modal) modal.style.display = 'block';
                }
            });

            // Tự động mở tab đúng khi load lại trang
            document.addEventListener('DOMContentLoaded', function() {
                // Ưu tiên lấy tab từ URL, nếu không có thì lấy từ localStorage
                var urlParams = new URLSearchParams(window.location.search);
                var tab = urlParams.get('tab') || localStorage.getItem('currentTab') || 'employee';
                // Ẩn tất cả tab
                document.querySelectorAll('.tabcontent').forEach(function(tc) {
                    tc.style.display = 'none';
                });
                // Bật tab đúng
                var tabEl = document.getElementById(tab);
                if (tabEl) tabEl.style.display = 'block';
                // Đánh dấu nút tab đúng
                document.querySelectorAll('.tablinks').forEach(function(link) {
                    link.classList.remove('active');
                });
                // Đánh dấu nút tab đúng
                var activeLink = document.querySelector('.tablinks[href*="tab=' + tab + '"]');
                if (activeLink) activeLink.classList.add('active');
            });



            $(document).ready(function() {
                let departmentId = <?= $id ?>;
                let currentSort = 'full_name';
                let currentOrder = 'asc';

                function loadEmployees() {
                    let search = $('#searchEmployee').val();
                    $.get('app/ajax_get_employees.php', {
                        id: departmentId,
                        search: search,
                        sort: currentSort,
                        order: currentOrder
                    }, function(data) {
                        $('#employeeTableBody').html(data);
                    });
                }

                $('#openEmployeeModal').click(function() {
                    $('#employeeModal').show();
                    loadEmployees();
                });

                $('#closeEmployeeModal').click(function() {
                    $('#employeeModal').hide();
                });

                $('#searchEmployee').on('keyup', function() {
                    loadEmployees();
                });

                $(document).on('click', '.sort-th', function() {
                    let clickedSort = $(this).data('sort');
                    let icon = $(this).find('i');

                    if (currentSort === clickedSort) {
                        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort = clickedSort;
                        currentOrder = 'asc';
                    }
                    // Reset tất cả icon về fa-sort
                    $('.sort-th i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                    // Gán icon đúng theo order
                    if (currentOrder === 'asc') {
                        icon.removeClass('fa-sort').addClass('fa-sort-up');
                    } else {
                        icon.removeClass('fa-sort').addClass('fa-sort-down');
                    }
                    loadEmployees();
                });
                $('#closeEmployeeModal').click(function() {
                    $('#employeeModal').hide();
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