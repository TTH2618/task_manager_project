<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if(isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && isset($_POST['birthday']) && isset($_POST['role']) && isset($_POST['email']) && $_SESSION['role'] == 'admin') {
    include "../db_connection.php";

    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);
    $full_name = validate_input($_POST['full_name']);
    $birthday = validate_input($_POST['birthday']);
    $role = validate_input($_POST['role']);
    $email = validate_input($_POST['email']);
     // Xử lý avatar
    $avatar_path = "img/user.jpg"; // mặc định
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "../img/";
        $file_ext = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed)) {
            $new_name = uniqid("avatar_") . "." . $file_ext;
            $target_file = $target_dir . $new_name;
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $avatar_path = "img/" . $new_name;
            }
        }
    }

    // bắt buột nhập 
    $stmt = $conn->prepare("select count(*) from users where username = ?");
    $stmt->execute([$user_name]);
    if ($stmt->fetchColumn() > 0) {
        $em = "Tên đăng nhập đã tồn tại!";
        header("Location:../add-user.php?error=$em");
        exit();
    }else if (empty($user_name)) {
        $em = "Bạn phải nhập User name";
        header("Location:../add-user.php?error=$em");
        exit();
    }else if (empty($password)) {
        $em = "Bạn phải nhập Mật khẩu";
        header("Location:../add-user.php?error=$em");
        exit();
    }else if (empty($full_name)) {
        $em = "Bạn phải nhập tên đầy đủ";
        header("Location:../add-user.php?error=$em");
        exit();
    }else if (empty($birthday)) {
        $em = "Bạn phải nhập ngày sinh";
        header("Location:../add-user.php?error=$em");
        exit();
    }else if (empty($role)) {
        $em = "Vui lòng chọn chức vụ";
        header("Location:../add-user.php?error=$em");
        exit();
    }else {
        include "model/user.php";
        $password = password_hash($password, PASSWORD_DEFAULT);
        $data = array($full_name, $user_name, $email, $birthday, $password,$role, $avatar_path);
        insert_users($conn,$data);

        // $em = "Thêm nhân viên mới thành công";
        // header("Location:../add-user.php?success=$em");
        // exit();
        $em = "Thêm nhân viên mới thành công";
        $_SESSION['status'] = $em;
        $_SESSION['status_code'] = "success";
        header("Location:../add-user.php");
        exit();
    }
}else {
    $em = "error";
    header("Location:../add-user.php?error=$em");
    exit();
}

}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em");
    exit();
}
?>

