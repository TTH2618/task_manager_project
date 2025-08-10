<?php
session_start();
if(isset($_POST['user_name'])&& isset($_POST['password'])) {
    include "../db_connection.php";

    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);
    // bắt buột nhập username và passpass
    if (empty($user_name)) {
        $em = "Bạn phải nhập User name";
        header("Location:../login.php?error=$em");
        exit();
    }else if (empty($password)) {
        $em = "Bạn phải nhập Mật khẩu";
        header("Location:../login.php?error=$em");
        exit();
    }else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_name]);

        if ($stmt->rowCount()==1) {
            $user = $stmt->fetch();
            $usernameDb = $user['username'];
            $passwordDb = $user['password'];
            $role =$user['role'];
            $id = $user['id'];
            //kiểm tra pass
            if ($user_name === $usernameDb) {
                if(password_verify($password, $passwordDb)) {
                    if ($role == "admin") {
                        $_SESSION['role'] = $role;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $usernameDb;
                        header("Location: ../index.php");
                    }else if ($role == 'employee') {
                        $_SESSION['role'] = $role;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $usernameDb;
                        header("Location: ../index.php");
                    }else if ($role == 'manager') {
                        $_SESSION['role'] = $role;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $usernameDb;
                        header("Location: ../index.php");
                    }else {
                        $em = "Unknown error occurred ";
                                header("Location: ../login.php?error=$em");
                                exit();
                    }
                }else {
                    $em = "Sai tài khoản hoặc mật khẩu";
                    header("Location:../login.php?error=$em");
                    exit();
                }   
            }
        }else {
                $em = "Sai tài khoản hoặc mật khẩu";
                header("Location:../login.php?error=$em");
                exit();
            }
    }
}else {
    $em = "error";
    header("Location:../login.php?error=$em");
    exit();
}

?>
