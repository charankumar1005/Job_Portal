<?php
require 'config.php';
session_start();

// Check if user is logged in as jobseeker
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jobseeker') {
    header("Location: login.html");
    exit();
}

// Fetch applications for the logged-in jobseeker
$user_id = $_SESSION['user_id'];
$sql_applications = "
    SELECT a.job_id, j.title, j.company, j.location, j.salary,a.applied_at
    FROM applications AS a
    JOIN jobs AS j ON a.job_id = j.id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
";
$stmt = $conn->prepare($sql_applications);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_applications = $stmt->get_result();

$applications = [];
if ($result_applications->num_rows > 0) {
    while ($row = $result_applications->fetch_assoc()) {
        $applications[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background: #007bff;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        .application-section {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .application-header h3 {
            margin: 0;
            font-size: 24px;
        }

        .application-details p {
            margin: 5px 0;
        }

        .no-applications {
            text-align: center;
            color: #888;
        }
    </style>
</head>

<body>

    <header>
        <h1>My Applications</h1>
    </header>

    <main>
        <h2>Your Job Applications</h2>

        <?php if (!empty($applications)): ?>
            <?php foreach ($applications as $application): ?>
                <div class="application-section">
                    <div class="application-header">
                        <h3><?= htmlspecialchars($application['title']) ?></h3>
                        <span>at <?= htmlspecialchars($application['company']) ?></span>
                    </div>
                    <div class="application-details">
                        <p><strong>Location:</strong> <?= htmlspecialchars($application['location']) ?></p>
                        <p><strong>Salary:</strong>₹ <?= htmlspecialchars(number_format($application['salary'])) ?></p>
                        <p><strong>Application Date:</strong> <?= htmlspecialchars($application['applied_at']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-applications">You have not applied to any jobs yet.</p>
        <?php endif; ?>
    </main>

</body>

</html>