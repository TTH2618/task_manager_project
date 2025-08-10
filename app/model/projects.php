<?php
function insert_projects($conn, $data){
    $sql = "INSERT INTO projects (title, description, manager_id , employee_id, department_id, start_date, end_date, status) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $inserted_id = $conn->lastInsertId(); 
    return $inserted_id;
}

function get_all_projects($conn, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects ? $projects : 0;
}

function get_all_projects_by_department_id($conn, $department_id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE department_id = ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects ? $projects : 0;
}

function delete_project($conn, $data){
$sql = "DELETE FROM projects WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute($data);
}   

function get_project_by_id($conn, $id){
$sql = "SELECT * FROM projects WHERE id= ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

if($stmt->rowCount() > 0){
    $project = $stmt->fetch();
}else $project = 0;

return $project;
}

function find_projects($conn, $search, $sort = 'id', $order = 'asc'){
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE concat(id, title, manager_id, start_date, end_date, status) LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%"]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects ? $projects : 0;
}
function find_projects_by_department_id($conn, $department_id, $search, $sort = 'id', $order = 'asc'){
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE department_id = ? AND CONCAT(id, title, manager_id, start_date, end_date, status) LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$department_id, "%$search%"]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects ? $projects : 0;
}

function find_project_by_manager_ids($conn, $user_ids) {
    if (empty($user_ids)) return [];
    // Tạo chuỗi dấu hỏi cho PDO
    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
    $sql = "SELECT * FROM projects WHERE manager_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($user_ids);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects ? $projects : [];
}

function get_all_projects_by_manager_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE manager_id = ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $project;
    }else {
        return 0;
    }
}

function get_all_projects_by_employee_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $project;
    }else {
        return 0;
    }
}

function get_all_projects_by_user_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM projects WHERE manager_id = ? OR FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id, $id]);

    if($stmt->rowCount() > 0){
        $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $project;
    }else {
        return 0;
    }
}

function update_project($conn, $data){
    $sql = "UPDATE projects SET title=?, description=?, manager_id=?, employee_id=?, department_id=?, start_date=?, end_date=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function update_project_status($conn, $data){
    $sql = "UPDATE projects SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function count_projects($conn){
	$sql = "SELECT id FROM projects";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_projects_by_manager($conn, $manager_id){
	$sql = "SELECT id FROM projects WHERE manager_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$manager_id]);

	return $stmt->rowCount();
}

function count_projects_by_employee($conn, $id){
	$sql = "SELECT id FROM projects WHERE FIND_IN_SET(?, employee_id) > 0";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function find_projects_by_name($conn, $search) {
    $sql = "SELECT * FROM projects WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_status_name_project($status) {
    switch ($status) {
        case '1':
            return "Đang thực hiện";
        case '2':
            return "Hoàn tất";
        case '3':
            return "Tạm ngưng";
        case '4':
            return "Hủy bỏ";
        default:
            return "Không xác định";
    }
}
?>
