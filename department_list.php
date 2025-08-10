<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "db_connection.php";
    include "app/model/department.php";
    include "app/model/user.php";
    include "app/model/projects.php";
    $page_title = "Danh sách phòng ban";
    // $sort = $_GET['sort'] ?? 'id';
    // $order = $_GET['order'] ?? 'asc';
    // $allowed_sort = ['id', 'name', 'member_id', 'project_count', 'member_count'];
    // $allowed_order = ['asc', 'desc'];
    // if (!in_array($sort, $allowed_sort)) $sort = 'id';
    // if (!in_array($order, $allowed_order)) $order = 'asc';

    // // Xử lý tìm kiếm
    // if (isset($_GET['search'])) {
    //     $search = trim($_GET['search']);
    //     if ($search === '') {
    //         $departments = get_all_departments($conn, $sort, $order);
    //     } else {
    //         // Tìm phòng ban theo tên
    //         $departments = [];
    //         $all_departments = get_all_departments($conn, $sort, $order);
    //         foreach ($all_departments as $dept) {
    //             if (stripos($dept['name'], $search) !== false) {
    //                 $departments[] = $dept;
    //             }
    //         }
    //     }
    // } else {
    //     $departments = get_all_departments($conn, $sort, $order);
    // }

    // // Lấy tất cả user để đếm thành viên
    // $users = get_all_users($conn);

    // // Lấy tất cả dự án để đếm số dự án theo phòng ban
    // $projects = get_all_projects($conn);

    // // Sau khi lấy $departments, $users, $projects
    // if ($departments != 0) {
    //     foreach ($departments as &$dept) {
    //         // Đếm số dự án
    //         $dept['project_count'] = 0;
    //         if ($projects != 0) {
    //             foreach ($projects as $project) {
    //                 if ($project['department_id'] == $dept['id']) $dept['project_count']++;
    //             }
    //         }
    //         // Đếm số thành viên
    //         $dept['member_count'] = 0;
    //         if ($users != 0) {
    //             foreach ($users as $user) {
    //                 if ($user['department_id'] == $dept['id']) $dept['member_count']++;
    //             }
    //         }
    //     }
    //     unset($dept);

    //     // Sort theo số dự án hoặc số thành viên nếu được chọn
    //     if ($sort == 'project_count' || $sort == 'member_count') {
    //         usort($departments, function ($a, $b) use ($sort, $order) {
    //             if ($order == 'asc') return $a[$sort] <=> $b[$sort];
    //             else return $b[$sort] <=> $a[$sort];
    //         });
    //     }
    // }
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
                <h4 class="title"><a id="openModal"><i class="fa fa-plus"></i> Thêm phòng ban mới</a></h4>
                <form action="" method="get">
                    <label>Tìm kiếm: </label>
                    <input type="text" id="searchDepartment" name="search" placeholder="Nhập tên phòng ban">
                    <!-- <button class="search-btn">Tìm kiếm</button> -->
                </form>
                <table class="main-table task-table">
                    <tr>
                        <th>#</th>
                        <th class="sort-th" data-sort="title" data-order="asc">Tên phòng ban <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="project_count" data-order="asc">Số dự án <i class="fa fa-sort"></i></th>
                        <th class="sort-th" data-sort="member_count" data-order="asc">Số thành viên <i class="fa fa-sort"></i></th>
                        <th>Hành động</th>
                    </tr>
                    <tbody id="TableBody">
                        <!-- Kết quả AJAX sẽ render ở đây -->
                    </tbody>
                </table>

                <!--modal tạo phòng ban -->
                <div id="Modal" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeModal">&times;</span>
                        <h3>Tạo phòng ban mới</h3>
                        <form action="app/add-department.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Tên phòng ban</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                            </div>
                            <!-- Description section -->
                            <div class="form-row" style="grid-template-columns: 1fr;">
                                <div class="form-group">
                                    <label for="description">Mô tả phòng ban</label>
                                    <textarea id="description" name="description" class="description-textarea" rows="5" cols="50"></textarea>
                                </div>
                            </div>
                            <div style="margin-top:10px;">
                                <button type="submit" class="btn btn-success">Tạo</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal sửa phòng ban -->
                <div id="EditModal" class="modal">
                    <div class="modal-content">
                        <span class="close" id="closeEditModal">&times;</span>
                        <h3>Sửa phòng ban</h3>
                        <form id="editDepartmentForm" action="app/update-department.php" method="POST">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_name">Tên phòng ban</label>
                                    <input type="text" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="form-row" style="grid-template-columns: 1fr;">
                                <div class="form-group">
                                    <label for="edit_description">Mô tả phòng ban</label>
                                    <textarea id="edit_description" name="description" class="description-textarea" rows="5" cols="50"></textarea>
                                </div>
                            </div>
                            <div style="margin-top:10px;">
                                <button type="submit" class="btn btn-success">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>

            </section>
        </div>
        <script type="text/javascript">
            // Hàm khởi tạo AJAX cho bảng
            $(document).ready(function() {
                initAjaxTable({
                    openBtnSelector: null,
                    modalSelector: null,
                    closeBtnSelector: null,
                    searchInputSelector: '#searchDepartment',
                    tableBodySelector: '#TableBody',
                    sortThSelector: '.sort-th',
                    ajaxUrl: 'app/get_department.php',
                    defaultSort: 'id',
                    defaultOrder: 'asc',
                });
            });

            // modal tạo phòng ban
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('openModal').onclick = function() {
                    document.getElementById('Modal').style.display = 'block';
                };
                document.getElementById('closeModal').onclick = function() {
                    document.getElementById('Modal').style.display = 'none';
                };
                window.onclick = function(event) {
                    if (event.target == document.getElementById('Modal')) {
                        document.getElementById('Modal').style.display = 'none';
                    }
                };
            });

            // Modal sửa phòng ban
            document.addEventListener('DOMContentLoaded', function() {
                // Mở modal sửa và điền dữ liệu
                document.body.addEventListener('click', function(e) {
                    if (e.target.classList.contains('edit-department-btn')) {
                        e.preventDefault();
                        document.getElementById('EditModal').style.display = 'block';
                        document.getElementById('edit_id').value = e.target.getAttribute('data-id');
                        document.getElementById('edit_name').value = e.target.getAttribute('data-name');
                        document.getElementById('edit_description').value = e.target.getAttribute('data-description');
                    }
                });
                // Đóng modal sửa
                document.getElementById('closeEditModal').onclick = function() {
                    document.getElementById('EditModal').style.display = 'none';
                };
                window.onclick = function(event) {
                    if (event.target == document.getElementById('EditModal')) {
                        document.getElementById('EditModal').style.display = 'none';
                    }
                    if (event.target == document.getElementById('Modal')) {
                        document.getElementById('Modal').style.display = 'none';
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