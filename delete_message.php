<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
  exit;
}

$db = new SQLite3('messaging.db');

$stmt = $db->prepare("DELETE FROM messages WHERE id = :id AND email = :email");
$stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
$stmt->bindValue(':email', $_SESSION['email'], SQLITE3_TEXT);
$stmt->execute();

echo json_encode(['success' => true]);
?>
