<?php
function insert_tasks($conn, $data){
    $sql = "INSERT INTO tasks (title, project_id, description, created_by, employee_id, end_date, status) VALUES(?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $inserted_id = $conn->lastInsertId(); 
    return $inserted_id;
}

function get_all_tasks($conn, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','employee_id','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks ? $tasks : 0;
}

function delete_task($conn, $data){
$sql = "DELETE FROM tasks WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute($data);
}   

function get_task_by_id($conn, $id){
$sql = "SELECT * FROM tasks WHERE id= ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

if($stmt->rowCount() > 0){
    $task = $stmt->fetch();
}else $task = 0;

return $task;
}

function find_tasks($conn, $search, $sort = 'id', $order = 'asc'){
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE concat(id, title, project_id, created_by, end_date, status) LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$search%"]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks ? $tasks : 0;
}

function find_tasks_by_user_id_and_search($conn, $user_id, $search, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks 
            WHERE (created_by = ? OR FIND_IN_SET(?, employee_id) > 0)
            AND (
                id LIKE ? OR
                title LIKE ? OR
                project_id LIKE ? OR
                created_by LIKE ? OR
                end_date LIKE ? OR
                status LIKE ?
            )
            ORDER BY $sort $order";
    $search_param = "%$search%";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $user_id, $user_id,
        $search_param, $search_param, $search_param, $search_param, $search_param, $search_param
    ]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks ? $tasks : [];
}

function find_tasks_by_created_by_ids($conn, $user_ids) {
    if (empty($user_ids)) return [];
    // Tạo chuỗi dấu hỏi cho PDO
    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
    $sql = "SELECT * FROM tasks WHERE created_by IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($user_ids);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks ? $tasks : [];
}

function get_all_tasks_by_created_by_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE created_by = ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $task;
    }else {
        return 0;
    }
}

function get_all_tasks_by_employee_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $task;
    }else {
        return 0;
    }

}
function get_all_tasks_by_user_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE created_by=? OR FIND_IN_SET(?, employee_id) > 0 ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id, $id]);

    if($stmt->rowCount() > 0){
        $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $task;
    }else {
        return 0;
    }

}

function get_all_tasks_by_project_id($conn, $id, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE project_id = ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $task;
    }else {
        return 0;
    }

}

function update_task($conn, $data){
    $sql = "UPDATE tasks SET title=?, description=?, employee_id=?, end_date=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function update_task_status($conn, $data){
    $sql = "UPDATE tasks SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) return false;
    return $stmt->execute($data);
}

function count_tasks($conn){
	$sql = "SELECT id FROM tasks";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_tasks_by_manager($conn, $manager_id){
	$sql = "SELECT id FROM tasks WHERE created_by=? OR FIND_IN_SET(?, employee_id) > 0";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$manager_id, $manager_id]);

	return $stmt->rowCount();
}

function count_tasks_by_employee($conn, $id){
	$sql = "SELECT id FROM tasks WHERE FIND_IN_SET(?, employee_id)";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_complete_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 3";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_complete_tasks_by_manager($conn, $manager_id){
	$sql = "SELECT id FROM tasks WHERE status = 3 AND created_by = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$manager_id]);

	return $stmt->rowCount();
}
function count_complete_tasks_by_employee($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 3 AND FIND_IN_SET(?, employee_id)";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_incomplete_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status != 3";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_incomplete_tasks_by_manager($conn, $manager_id){
	$sql = "SELECT id FROM tasks WHERE status != 3 AND created_by = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$manager_id]);

	return $stmt->rowCount();
}

function count_incomplete_tasks_by_employee($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status != 3 AND FIND_IN_SET(?, employee_id)";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function get_total_tasks_by_project($conn, $project_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE project_id = ?");
    $stmt->execute([$project_id]);
    return (int)$stmt->fetchColumn();
}

function get_completed_tasks_by_project($conn, $project_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE project_id = ? AND status = '2'");
    $stmt->execute([$project_id]);
    return (int)$stmt->fetchColumn();
}

function find_tasks_by_project_ids($conn, $project_ids, $sort = 'id', $order = 'asc') {
    if (empty($project_ids)) return [];
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $placeholders = implode(',', array_fill(0, count($project_ids), '?'));
    $sql = "SELECT * FROM tasks WHERE project_id IN ($placeholders) ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute($project_ids);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function find_tasks_by_user_and_title($conn, $employee_id, $search, $sort = 'id', $order = 'asc') {
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $sql = "SELECT * FROM tasks WHERE (created_by = ? OR FIND_IN_SET(?, employee_id) > 0) AND title LIKE ? ORDER BY $sort $order";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$employee_id, $employee_id, "%$search%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function find_tasks_by_user_and_project_ids($conn, $employee_id, $project_ids, $sort = 'id', $order = 'asc') {
    if (empty($project_ids)) return [];
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $placeholders = implode(',', array_fill(0, count($project_ids), '?'));
    $sql = "SELECT * FROM tasks WHERE (created_by = ? OR FIND_IN_SET(?, employee_id) > 0) AND project_id IN ($placeholders) ORDER BY $sort $order";
    $params = array_merge([$employee_id, $employee_id], $project_ids);
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function find_tasks_by_user_and_created_by_ids($conn, $employee_id, $user_ids, $sort = 'id', $order = 'asc') {
    if (empty($user_ids)) return [];
    $allowed_sort = ['id','title','project_id','created_by','end_date','status'];
    $allowed_order = ['asc','desc'];
    if (!in_array($sort, $allowed_sort)) $sort = 'id';
    if (!in_array($order, $allowed_order)) $order = 'asc';
    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
    $sql = "SELECT * FROM tasks WHERE (created_by = ? OR FIND_IN_SET(?, employee_id) > 0) AND created_by IN ($placeholders) ORDER BY $sort $order";
    $params = array_merge([$employee_id, $employee_id], $user_ids);
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_status_name($status) {
    switch ($status) {
        case '1':
            return "Chưa hoàn thành";
        case '2':
            return "Hoàn thành";
        case '3':
            return "Hủy bỏ";
        default:
            return "Không xác định";
    }
}
?>
