<?php
require 'config.php';
session_start();

// Redirect if the user is not an employer
if ($_SESSION['role'] != 'employer') {
    header("Location: login.html");
    exit();
}

// Fetch jobs posted by the employer
$sql_jobs = "SELECT id, title, description, location, company, salary FROM jobs WHERE posted_by = ?";
$stmt_jobs = $conn->prepare($sql_jobs);
$stmt_jobs->bind_param("i", $_SESSION['user_id']);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();

$jobs = [];
if ($result_jobs->num_rows > 0) {
    while ($row = $result_jobs->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Fetch applications for each job
$applications = [];
foreach ($jobs as $job) {
    $sql_apps = "SELECT a.id AS application_id, a.applied_at, a.status, u.username, u.email 
                 FROM applications a
                 JOIN users u ON a.user_id = u.id
                 WHERE a.job_id = ?";
    $stmt_apps = $conn->prepare($sql_apps);
    $stmt_apps->bind_param("i", $job['id']);
    $stmt_apps->execute();
    $result_apps = $stmt_apps->get_result();

    $applications[$job['id']] = [];
    if ($result_apps->num_rows > 0) {
        while ($app = $result_apps->fetch_assoc()) {
            $applications[$job['id']][] = $app;
        }
    }
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    $sql_update = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $status, $application_id);

    if ($stmt_update->execute()) {
        echo "<p>Status updated successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt_update->error . "</p>";
    }

    $stmt_update->close(); // Close the statement
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
/* General styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

/* Header styling */
header {
    background-color: #007bff;
    color: #ffffff;
    padding: 20px;
    text-align: center;
}

header h1 {
    margin: 0;
}

header nav ul {
    list-style-type: none;
    padding: 0;
    margin: 10px 0 0;
    display: flex;
    justify-content: center;
}

header nav ul li {
    margin: 0 15px;
}

header nav ul li a {
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

header nav ul li a:hover {
    color: #00aaff;
}

/* Main section */
main {
    padding: 20px;
    max-width: 800px;
    margin: auto;
}

h2 {
    color: #1a3e6e;
    text-align: center;
}

/* Job section styling */
.job-section {
    border: 1px solid #1a3e6e;
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.job-header h3 {
    color: #1a3e6e;
    margin: 0;
    font-size: 1.2em;
}

.job-header span {
    color: #555;
    font-size: 0.9em;
}

.job-details p {
    margin: 5px 0;
    color: #333;
}

/* Application section */
.application-section {
    margin-top: 20px;
}

.application-header {
    font-weight: bold;
    color: #1a3e6e;
    margin-bottom: 10px;
}

.application-list {
    list-style-type: none;
    padding: 0;
}

.application-list li {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.application-status {
    display: flex;
    align-items: center;
    gap: 10px;
}

.application-status select {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #1a3e6e;
    background-color: #e6f1ff;
    color: #1a3e6e;
}

.application-status button {
    padding: 5px 10px;
    background-color: #1a3e6e;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.application-status button:hover {
    background-color: #005bb5;
}

/* No jobs and no applications styling */
.no-jobs,
.no-applications {
    text-align: center;
    color: #999;
    font-style: italic;
    padding: 20px;
}

    </style>
</head>

<body>

    <header>
        <h1>Welcome, Employer</h1>
        <nav>
            <ul>
                <li><a href="post_job.php">Post Job</a></li>
                <li><a href="employer_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Your Job Listings</h2>

        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-section">
                    <div class="job-header">
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <span><?= htmlspecialchars($job['company']) ?></span>
                    </div>
                    <div class="job-details">
                        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                        <p><strong>Salary:</strong> ₹ <?= htmlspecialchars(number_format($job['salary'])) ?></p>
                        <p><?= htmlspecialchars($job['description']) ?></p>
                    </div>
                    <div class="application-section">
                        <div class="application-header">Applications:</div>
                        <?php if (!empty($applications[$job['id']])): ?>
                            <ul class="application-list">
                                <?php foreach ($applications[$job['id']] as $app): ?>
                                    <li>
                                        <strong>Applicant:</strong> <?= htmlspecialchars($app['username']) ?><br>
                                        <strong>Email:</strong> <?= htmlspecialchars($app['email']) ?><br>
                                        <strong>Applied At:</strong> <?= htmlspecialchars($app['applied_at']) ?><br>
                                        <form method="POST">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                            <div class="application-status">
                                                <label for="status">Status:</label>
                                                <select name="status" required>
                                                    <option value="Pending" <?= $app['status'] === 'Pending' ? 'selected' : '' ?>>Pending
                                                    </option>
                                                    <option value="Reviewed" <?= $app['status'] === 'Reviewed' ? 'selected' : '' ?>>
                                                        Reviewed</option>
                                                    <option value="Rejected" <?= $app['status'] === 'Rejected' ? 'selected' : '' ?>>
                                                        Rejected</option>
                                                    <option value="Accepted" <?= $app['status'] === 'Accepted' ? 'selected' : '' ?>>
                                                        Accepted</option>
                                                </select>
                                                <button type="submit" name="update_status">Update Status</button>
                                            </div>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="no-applications">No applications yet.</p>
                        <?php endif; ?>
                    </div>

                </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-jobs">No jobs posted yet.</p>
        <?php endif; ?>
    </main>

</body>

</html>