<?php
require 'config.php';
session_start();

// Check if user is an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Handle job deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $job_id = intval($_GET['id']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete related applications
        $sql = "DELETE FROM applications WHERE job_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $job_id);
        $stmt->execute();
        $stmt->close();

        // Delete the job
        $sql = "DELETE FROM jobs WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $job_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        header("Location: admin_dashboard.php?msg=Job deleted successfully");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
