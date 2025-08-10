<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['manager_id']) && isset($_POST['employee_id']) && is_array($_POST['employee_id']) && isset($_POST['department_id']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['status']) && $_SESSION['role'] == 'admin') {
        include "../db_connection.php";

        function validate_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $manager_id = validate_input($_POST['manager_id']);
        $department_id = validate_input($_POST['department_id']);
        // Xử lý employee_id array
        $employee_ids = $_POST['employee_id'];
        $validated_employee_ids = array();
        foreach ($employee_ids as $emp_id) {
            $validated_employee_ids[] = validate_input($emp_id);
        }
        $employee_id = implode(',', $validated_employee_ids);

        $start_date = validate_input($_POST['start_date']);
        $end_date = validate_input($_POST['end_date']);
        $status = validate_input($_POST['status']);
        // bắt buột nhập 
        if (empty($title)) {
            $em = "Bạn phải nhập tên dự án";
            header("Location:../create_project.php?error=$em");
            exit();
        } else if (empty($manager_id)) {
            $em = "Bạn phải chọn quản lý";
            header("Location:../create_project.php?error=$em");
            exit();
        } else if (empty($employee_id)) {
            $em = "Bạn phải chọn nhân viên";
            header("Location:../create_project.php?error=$em");
            exit();
        } else if (empty($start_date)) {
            $em = "Vui lòng chọn ngày bắt đầu";
            header("Location:../create_project.php?error=$em");
            exit();
        } else if (empty($end_date)) {
            $em = "Vui lòng chọn ngày kết thúc";
            header("Location:../create_project.php?error=$em");
            exit();
        } else if (empty($status)) {
            $em = "Vui lòng chọn trạng thái";
            header("Location:../create_project.php?error=$em");
            exit();
        } else {
            include "model/projects.php";
            $data = array($title, $description, $manager_id, $employee_id, $department_id, $start_date, $end_date, $status);

            $project_id = insert_projects($conn, $data);

            include "model/notification.php";
            foreach ($validated_employee_ids as $emp_id) {
                $notif_data = array($project_id, "Bạn được thêm vào là thành viên của dự án '$title'. Bấm vào đây để xem chi tiết.", $emp_id, "Dự án mới");
                insert_notification_project($conn, $notif_data);
            }
            $notif_data = array($project_id, "Bạn được thêm vào là quản lý của dự án '$title'. Bấm vào đây để xem chi tiết.", $manager_id, "Dự án mới");
            insert_notification_project($conn, $notif_data);

            // $em = "Thêm dự án mới thành công";
            // header("Location:../create_project.php?success=$em");
            // exit();
            $_SESSION['status'] = "Thêm dự án mới thành công";
            $_SESSION['status_code'] = "success";
            header("Location: ../detail-project.php?id=$project_id");
            exit();
        }
    } else {
        $_SESSION['status'] = "Thêm dự án mới thất bại";
        $_SESSION['status_code'] = "error";
        header("Location: ../detail-project.php?id=$project_id");
        exit();
    }
} else {
    $em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
