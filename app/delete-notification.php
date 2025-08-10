<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../db_connection.php";
	include "../app/model/notification.php";

    if(!isset($_GET['id'])) {
        // Xóa tất cả thông báo của người dùng hiện tại
        delete_all_notifications_by_user($conn, $_SESSION['id']);
        $em = "Đã xóa tất cả thông báo";
        // header("Location: ../notification.php?success=$em");
        $_SESSION['status'] = $em;
        $_SESSION['status_code'] = "success";
        header("Location:../notification.php");
        exit();
    }
    $id = $_GET['id'];
	$notification = get_notification_by_id($conn, $id);
    if($notification == 0) {
        header("Location: ../notification.php");
        exit();
    }
    if($notification == 0) {
        header("Location: ../notification.php");
        exit();
    }

    $data = array($id);
    delete_notification($conn, $data);
    $em = "Xóa thông báo thành công";
    // header("Location: ../notification.php?success=$em");
    // exit();
    $_SESSION['status'] = $em;
    $_SESSION['status_code'] = "success";
    header("Location:../notification.php");
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