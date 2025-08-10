<?php
function insert_progress($conn, $data){
    $sql = "INSERT INTO progress (task_id, user_id, comment, file) VALUES(?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) return false;
    return $stmt->execute($data);
}

function get_progress_by_task_id($conn, $task_id) {
    $sql = "SELECT * FROM progress WHERE task_id = ? ORDER BY created_date DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) return [];
    $stmt->execute([$task_id]);
    $progresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $progresses;
}