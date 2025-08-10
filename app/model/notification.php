<?php  

function get_all_my_notifications($conn, $id){
	$sql = "SELECT * FROM notifications WHERE recipient=? ORDER BY date DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$notifications = $stmt->fetchAll();
	}else $notifications = 0;

	return $notifications;
}
function get_all_my_notifications_not_read($conn, $id){
	$sql = "SELECT * FROM notifications WHERE recipient=? AND is_read=0 ORDER BY date DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$notifications = $stmt->fetchAll();
	}else $notifications = 0;

	return $notifications;
}


function count_notification($conn, $id){
	$sql = "SELECT id FROM notifications WHERE recipient=? AND is_read=0";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function insert_notification_task($conn, $data){
	$sql = "INSERT INTO notifications (task_id, message, recipient, type) VALUES(?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}
function insert_notification_project($conn, $data){
	$sql = "INSERT INTO notifications (project_id, message, recipient, type) VALUES(?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function notification_make_read($conn, $recipient_id, $notification_id){
	$sql = "UPDATE notifications SET is_read=1 WHERE id=? AND recipient=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$notification_id, $recipient_id]);
}
function get_task_id_by_notification($conn, $notification_id) {
    $sql = "SELECT task_id FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id]);
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        return $row['task_id'];
    }
    return null;
}

function get_project_id_by_notification($conn, $notification_id) {
	$sql = "SELECT project_id FROM notifications WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$notification_id]);
	if ($stmt->rowCount() > 0) {
		$row = $stmt->fetch();
		return $row['project_id'];
	}
	return null;
}
function get_notification_by_id($conn, $notification_id) {
    $sql = "SELECT * FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id]);
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch();
    }
    return null;
}

function delete_notification($conn, $data) {
	$sql = "DELETE FROM notifications WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
	return $stmt->rowCount() > 0;
}
function delete_all_notifications_by_user($conn, $user_id) {
    $sql = "DELETE FROM notifications WHERE recipient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
}
?>