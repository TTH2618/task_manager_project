<?php
require '../phpspreadsheet/vendor/autoload.php';
include "../db_connection.php";
include "../app/model/user.php";
include "../app/model/projects.php";
include "../app/model/tasks.php";
include "../app/model/department.php";
// Lấy dữ liệu dự án từ database
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Lấy thông tin dự án
$project = get_project_by_id($conn, $project_id);
// Lấy các công việc thuộc dự án
$tasks =  get_all_tasks_by_project_id($conn, $project_id);
// Lấy thành viên dự án
$members = get_all_users($conn);
// Lấy thông tin người quản lý dự án
$manager = get_users_by_id($conn, $project['manager_id']);
// lấy thông tin phòng ban
$department = get_department_by_id($conn, $project['department_id']);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề
$sheet->setCellValue('A1', 'Thông Tin Dự Án');
$sheet->mergeCells('A1:E1');

// Thông tin chung
$sheet->setCellValue('A2', 'Tên Dự Án:');
$sheet->setCellValue('B2', $project['title']);
$sheet->setCellValue('A3', 'Ngày Bắt Đầu:');
$sheet->setCellValue('B3', $project['start_date']);
$sheet->setCellValue('A4', 'Ngày Kết Thúc:');
$sheet->setCellValue('B4', $project['end_date']);
$sheet->setCellValue('A5', 'Phòng ban thực hiện:');
$sheet->setCellValue('B5', $department['name']);
// $sheet->setCellValue('A6', 'Loại Dự Án:');
// $sheet->setCellValue('B6', $project['type']);
$sheet->setCellValue('A7', 'Mô Tả:');
$sheet->setCellValue('B7', $project['description']);

// Thành viên
$sheet->setCellValue('A9', 'Thành Viên:');
$row = 10;
if (!empty($project['employee_id'])) {
    $member_ids = explode(',', $project['employee_id']);
    // $member_names = [];
    foreach ($members as $member) {
        if (in_array($member['id'], $member_ids)) {
            $sheet->setCellValue('A' . $row, $member['full_name']);
            $sheet->setCellValue('B' . $row, $member['email']);
            $row++;
        }
    }
} else {
    $sheet->setCellValue('A' . $row, 'Không có thành viên nào');
}
// foreach ($members as $member) {
//     $sheet->setCellValue('A' . $row, $member['full_name']);
//     $sheet->setCellValue('B' . $row, $member['email']);
//     $sheet->setCellValue('C' . $row, $member['phone']);
//     $row++;
// }

// Bảng công việc
$row += 2;
// $sheet->setCellValue('A' . $row, 'Các giai đoạn');
$sheet->setCellValue('A' . $row, 'Công việc');
$sheet->setCellValue('B' . $row, 'Thời gian');
// $sheet->setCellValue('C' . $row, 'Thành viên thực hiện');
$sheet->setCellValue('D' . $row, 'Trạng thái');
$row++;

foreach ($tasks as $task) {
    // $sheet->setCellValue('A' . $row, $task['phase']);
    $sheet->setCellValue('A' . $row, $task['title']);
    $sheet->setCellValue('B' . $row, $task['start_date'] . ' - ' . $task['end_date']);
    // $sheet->setCellValue('C' . $row, $task['progress'] . '%');
    $sheet->setCellValue('D' . $row, get_status_name($task['status']));
    $row++;
}

// Xuất file
$filename = 'BaoCaoDuAn_' . $project['title'] . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
