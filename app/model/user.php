<?php
function get_all_users($conn, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM users ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users ? $users : 0;
}

function get_all_users_by_department_id($conn, $department_id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'role';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM users WHERE department_id = ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users ? $users : 0;
}
function get_all_users_not_in_department($conn, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM users WHERE department_id = 0 AND role != 'admin' ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users ? $users : 0;
}

function insert_users($conn, $data){
    $sql = "insert into users (full_name, username, email, birthday, password, role, avatar) values(?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
function update_users($conn, $data){
    $sql = "update users set full_name=?, username=?, email=?, birthday=?, password=?, role=?, avatar=? where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
function update_profile($conn, $data){
    $sql = "update users set full_name=?, username=?, email=?, birthday=?, password=?, avatar=? where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
function update_users_department_id($conn, $data){
    $sql = "update users set department_id=? where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
function delete_users($conn, $data){
    $sql = "delete from users where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function get_users_by_id($conn, $id){
    $sql = "select * from users where id= ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $user = $stmt->fetch();
    }else $user = 0;

    return $user;
}

function find_users_by_name($conn, $name) {
    $sql = "SELECT * FROM users WHERE full_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like = "%$name%";
    $stmt->execute([$like]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users ? $users : [];
}

function find_users($conn, $search, $sort='id', $order='asc'){
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "select * from users where concat(id, full_name, username) like ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%"]);

    if($stmt->rowCount() > 0){
        $users = $stmt->fetchALL();
    }else $users = 0;

    return $users;
}

function find_users_by_department_id($conn, $department_id, $search, $sort='id', $order='asc'){
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM users WHERE department_id = ? AND CONCAT(id, full_name, username) LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id, "%$search%"]);

    if($stmt->rowCount() > 0){
        $users = $stmt->fetchALL();
    }else $users = 0;

    return $users;
}

function find_users_not_in_department($conn, $search, $sort='id', $order='asc'){
    $allowed_sort = ['id','full_name','username','birthday','role'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM users WHERE department_id = 0 AND role != 'admin' AND CONCAT(id, full_name, username) LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%"]);

    if($stmt->rowCount() > 0){
        $users = $stmt->fetchALL();
    }else $users = 0;

    return $users;
}
function count_users($conn){
	$sql = "SELECT id FROM users";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function get_role_name($role) {
    switch ($role) {
        case 'admin':
            return "Quản trị viên";
        case 'manager':
            return "Trưởng ban";
        case 'employee':
            return "Nhân viên";
        default:
            return "Không xác định";
    }
}
?>