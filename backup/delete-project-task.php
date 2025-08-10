<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin") || ($_SESSION['role'] == "manager")) {
    include "../db_connection.php";
	include "../app/model/tasks.php";
    include "../app/model/projects.php";

    if(!isset($_GET['id'])) {
        header("Location: ../detail-project.php?id=" . $_GET['project_id']);
        exit();
    }
    $id = $_GET['id'];
	$task = get_task_by_id($conn, $id);
    
    if($task == 0) {
        header("Location: ../detail-project.php");
        exit();
    }

    $data = array($id);
    delete_task($conn, $data);
    $em = "Xóa công việc thành công";
    header("Location: ../detail-project.php?success=$em");
    exit();


?>
?>


<?php
}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>