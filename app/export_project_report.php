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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề lớn
$sheet->setCellValue('A1', 'THÔNG TIN DỰ ÁN');
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

// Thông tin dự án
$sheet->setCellValue('A2', 'Tên Dự Án:');
$sheet->setCellValue('B2', $project['title']);
$sheet->mergeCells('B2:D2');
$sheet->setCellValue('A3', 'Ngày Bắt Đầu:');
$sheet->setCellValue('B3', $project['start_date']);
$sheet->mergeCells('B3:D3');
$sheet->setCellValue('A4', 'Ngày Kết Thúc:');
$sheet->setCellValue('B4', $project['end_date']);
$sheet->mergeCells('B4:D4');
$sheet->setCellValue('A5', 'Phòng ban thực hiện:');
$sheet->setCellValue('B5', $department['name']);
$sheet->mergeCells('B5:D5');
$sheet->setCellValue('A6', 'Mô Tả:');
$sheet->setCellValue('B6', $project['description']);
$sheet->mergeCells('B6:D6');
$sheet->getStyle("A6:D6")->getAlignment()->setWrapText(true);
$sheet->getRowDimension(6)->setRowHeight(50);
$sheet->setCellValue('A7', 'Người quản lý dự án:');
$sheet->setCellValue('B7', $manager['full_name'] ?? '');
$sheet->setCellValue('C7', $manager['email'] ?? '');
$sheet->mergeCells('C7:D7');

// Dòng trống
$sheet->setCellValue('A8', '');
$sheet->mergeCells('A8:D8');
// Thành viên dự án
$sheet->setCellValue('A9', 'Thành viên của dự án');
$sheet->mergeCells('A9:D9');
$sheet->getStyle('A9')->getFont()->setBold(true);
$sheet->getStyle('A9')->getAlignment()->setHorizontal('center');

// Tiêu đề bảng thành viên
$sheet->setCellValue('A10', 'Tên thành viên');
$sheet->setCellValue('B10', 'Email');
$sheet->mergeCells('B10:C10');
$sheet->setCellValue('D10', 'Chức vụ');
$sheet->getStyle('A10:D10')->getFont()->setBold(true);

$row = 10;
if (!empty($project['employee_id'])) {
    $member_ids = explode(',', $project['employee_id']);
    foreach ($members as $member) {
        if (in_array($member['id'], $member_ids)) {
            $row++;
            $sheet->setCellValue('A' . $row, $member['full_name']);
            $sheet->setCellValue('B' . $row, $member['email']);
            $sheet->mergeCells('B' . $row . ':' . 'C' . $row);
            $sheet->setCellValue('D' . $row, $member['role'] ?? '');
            
        }
    }
} else {
    $sheet->setCellValue('A' . $row, 'Không có thành viên nào');
    $row++;
}

// Dòng trống
$row++;
$sheet->setCellValue('A' . $row, '');
$sheet->mergeCells('A' . $row . ':D' . $row);
// Tiêu đề bảng công việc
$row++;
$sheet->setCellValue('A' . $row, 'Danh sách công việc của dự án');
$sheet->mergeCells('A' . $row . ':D' . $row);
$sheet->getStyle('A' . $row)->getFont()->setBold(true);
$sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');

// Tiêu đề các cột công việc
$row++;
$sheet->setCellValue('A' . $row, 'Công việc');
$sheet->setCellValue('B' . $row, 'Thời gian');
$sheet->setCellValue('C' . $row, 'Thành viên thực hiện');
$sheet->setCellValue('D' . $row, 'Trạng thái');
$sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);

$row++;
foreach ($tasks as $task) {
    $sheet->setCellValue('A' . $row, $task['title']);
    $sheet->setCellValue('B' . $row, $task['start_date'] . ' - ' . $task['end_date']);
    // Thành viên thực hiện
    $task_member_names = [];
    if (!empty($task['employee_id'])) {
        $task_member_ids = explode(',', $task['employee_id']);
        foreach ($members as $member) {
            if (in_array($member['id'], $task_member_ids)) {
                $task_member_names[] = $member['full_name'];
            }
        }
    }
    $sheet->setCellValue('C' . $row, implode(', ', $task_member_names));
    $sheet->setCellValue('D' . $row, get_status_name($task['status']));
    $row++;
}


$lastRow = $row - 1;
$sheet->getStyle("A2:D$lastRow")->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);

// Định dạng bảng
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Xuất file
$filename = 'BaoCaoDuAn_' . $project['title'] . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
