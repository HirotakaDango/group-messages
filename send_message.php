<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['message'])) {
  exit;
}

// Sanitize and filter message
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

$db = new SQLite3('messaging.db');

$stmt = $db->prepare("INSERT INTO messages (email, message) VALUES (:email, :message)");
$stmt->bindValue(':email', $_SESSION['email'], SQLITE3_TEXT);
$stmt->bindValue(':message', nl2br($message), SQLITE3_TEXT); // Convert newlines to <br> tags
$stmt->execute();

echo json_encode(['success' => true]);
?>
