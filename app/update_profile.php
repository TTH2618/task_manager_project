<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if(isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && isset($_POST['birthday']) && isset($_POST['email'])) {
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
    $id = $_SESSION['id'];
    $email = validate_input($_POST['email']);
    // Xử lý avatar
    $avatar_path = isset($_POST['old_avatar']) && $_POST['old_avatar'] ? $_POST['old_avatar'] : "img/user.jpg";
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
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $current_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra username đã tồn tại chưa (và không phải của user hiện tại)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user_name]);
    $user_exist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user_name)) {
        $em = "Bạn phải nhập User name";
        header("Location:../edit-user.php?error=$em&id=$id");
        exit();
    }else if ($user_exist && $user_exist['id'] != $id) {
        $em = "Tên đăng nhập đã tồn tại!";
        header("Location:../edit-user.php?error=$em&id=$id");
        exit();
    }else if (empty($full_name)) {
        $em = "Bạn phải nhập tên đầy đủ";
        header("Location:../edit_profile.php?error=$em&id=$id");
        exit();
    }else if (empty($birthday)) {
        $em = "Bạn phải nhập ngày sinh";
        header("Location:../edit_profile.php?error=$em&id=$id");
        exit();
    }else {
        include "model/user.php";
        if ($user_exist && $user_exist['id'] == $id) {
            // Username không đổi, giữ nguyên username cũ
            $user_name = $current_user['username']; 
        }
    // Nếu mật khẩu không rỗng thì cập nhật, nếu rỗng thì giữ nguyên
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $data = array($full_name, $user_name, $email,  $birthday, $password_hashed, $avatar_path, $id);
        update_profile($conn, $data);
    } else {
        // Lấy mật khẩu cũ từ DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $old_password = $stmt->fetchColumn();
        $data = array($full_name, $user_name, $email, $birthday, $old_password, $avatar_path, $id);
        update_profile($conn, $data);
    }
        $em = "Thay đổi thông tin nhân viên thành công";
        // header("Location:../edit_profile.php?success=$em&id=$id");
        $_SESSION['status'] = $em;
        $_SESSION['status_code'] = "success";
        header("Location: ../edit_profile.php");
        exit();
    }
}else {
    $em = "error";
    header("Location:../edit_profile.php?error=$em&id=$id");
    exit();
}

}else {
	$em = "Log in first";
    header("Location: ../login.php?error=$em&id=$id");
    exit();
}
?>

