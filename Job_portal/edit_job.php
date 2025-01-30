<?php
require 'config.php';
session_start();

// Redirect if the user is not an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

// Get job ID from URL
if (isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch job details
$sql = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found.";
    exit();
}

// Update job details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];

    $sql_update = "UPDATE jobs SET title = ?, company = ?, location = ?, salary = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssdi", $title, $company, $location, $salary, $job_id);

    if ($stmt_update->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating job: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-top: 0;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Edit Job</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="title">Job Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($job['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" name="company" id="company" value="<?= htmlspecialchars($job['company']) ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" value="<?= htmlspecialchars($job['location']) ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" name="salary" id="salary" value="<?= htmlspecialchars($job['salary']) ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Job</button>
            </div>
        </form>
    </div>

</body>

</html>