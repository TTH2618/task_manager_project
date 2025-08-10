<?php
function insert_department($conn, $data){
    $sql = "INSERT INTO department (name, description) VALUES(?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $inserted_id = $conn->lastInsertId(); 
    return $inserted_id;
}

function get_all_departments($conn, $sort = 'name', $order = 'asc') {
    $allowed_sort = ['id','name'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM department ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $departments ? $departments : 0;
}

function delete_department($conn, $data){
$sql = "DELETE FROM department WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute($data);
}   

function get_department_by_id($conn, $id){
$sql = "SELECT * FROM department WHERE id= ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

if($stmt->rowCount() > 0){
    $department = $stmt->fetch();
}else $department = 0;

return $department;
}

function count_departments($conn){
	$sql = "SELECT id FROM department";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function update_department($conn, $data){
    $sql = "UPDATE department SET name=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

// function find_projects($conn, $search, $sort = 'id', $order = 'asc'){
//     $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
//     $allowed_order = ['asc','desc'];
//     if (!in_array($sort, $allowed_sort)) $sort = 'id';
//     if (!in_array($order, $allowed_order)) $order = 'asc';
//     $sql = "SELECT * FROM projects WHERE concat(id, title, manager_id, start_date, end_date, status) LIKE ? ORDER BY $sort $order";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute(["%$search%"]);
//     $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     return $projects ? $projects : 0;
// }

// function find_project_by_manager_ids($conn, $user_ids) {
//     if (empty($user_ids)) return [];
//     // Tạo chuỗi dấu hỏi cho PDO
//     $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
//     $sql = "SELECT * FROM projects WHERE manager_id IN ($placeholders)";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute($user_ids);
//     $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     return $projects ? $projects : [];
// }

// function get_all_projects_by_manager_id($conn, $id, $sort = 'id', $order = 'asc') {
//     $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
//     $allowed_order = ['asc','desc'];
//     if (!in_array($sort, $allowed_sort)) $sort = 'id';
//     if (!in_array($order, $allowed_order)) $order = 'asc';
//     $sql = "SELECT * FROM projects WHERE manager_id = ? ORDER BY $sort $order";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute([$id]);

//     if($stmt->rowCount() > 0){
//         $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $project;
//     }else {
//         return 0;
//     }
// }

// function get_all_projects_by_employee_id($conn, $id, $sort = 'id', $order = 'asc') {
//     $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
//     $allowed_order = ['asc','desc'];
//     if (!in_array($sort, $allowed_sort)) $sort = 'id';
//     if (!in_array($order, $allowed_order)) $order = 'asc';
//     $sql = "SELECT * FROM projects WHERE FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute([$id]);

//     if($stmt->rowCount() > 0){
//         $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $project;
//     }else {
//         return 0;
//     }
// }

// function get_all_projects_by_user_id($conn, $id, $sort = 'id', $order = 'asc') {
//     $allowed_sort = ['id','title','manager_id','start_date','end_date','status'];
//     $allowed_order = ['asc','desc'];
//     if (!in_array($sort, $allowed_sort)) $sort = 'id';
//     if (!in_array($order, $allowed_order)) $order = 'asc';
//     $sql = "SELECT * FROM projects WHERE manager_id = ? OR FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute([$id, $id]);

//     if($stmt->rowCount() > 0){
//         $project = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $project;
//     }else {
//         return 0;
//     }
// }



// function update_project_status($conn, $data){
//     $sql = "UPDATE projects SET status=? WHERE id=?";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute($data);
// }


// function count_projects_by_manager($conn, $manager_id){
// 	$sql = "SELECT id FROM projects WHERE manager_id = ?";
// 	$stmt = $conn->prepare($sql);
// 	$stmt->execute([$manager_id]);

// 	return $stmt->rowCount();
// }

// function count_projects_by_employee($conn, $id){
// 	$sql = "SELECT id FROM projects WHERE FIND_IN_SET(?, employee_id) > 0";
// 	$stmt = $conn->prepare($sql);
// 	$stmt->execute([$id]);

// 	return $stmt->rowCount();
// }

// function find_projects_by_name($conn, $search) {
//     $sql = "SELECT * FROM projects WHERE title LIKE ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute(["%$search%"]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }
// ?>
