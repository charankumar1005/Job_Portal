<?php
require 'config.php';
session_start();

// Check if admin is logged in
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Get user ID from query parameter
$user_id = $_GET['id'];

// First, delete any associated jobs posted by this user to avoid foreign key constraint issues
$deleteJobs = $conn->prepare("DELETE FROM jobs WHERE posted_by = ?");
$deleteJobs->bind_param("i", $user_id);
if (!$deleteJobs->execute()) {
    echo "Error deleting user's jobs: " . $deleteJobs->error;
    $deleteJobs->close();
    $conn->close();
    exit();
}
$deleteJobs->close();

// Now delete the user from the database
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    header("Location: admin_dashboard.php?status=deleted");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>