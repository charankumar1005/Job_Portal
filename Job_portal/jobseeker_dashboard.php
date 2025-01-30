<?php
require 'config.php';
session_start();

// Redirect if the user is not a jobseeker
if ($_SESSION['role'] != 'jobseeker') {
    header("Location: login.html");
    exit();
}

// Fetch available jobs
$sql_jobs = "SELECT id, title, description, location, company, salary FROM jobs";
$result_jobs = $conn->query($sql_jobs);

$jobs = [];
if ($result_jobs->num_rows > 0) {
    while ($row = $result_jobs->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobseeker Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
   /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Navbar Styling */
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background-color: #1a73e8; /* Main blue theme */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.navbar-title {
    color: #fff;
    font-size: 24px;
    font-weight: 600;
}

.navbar-menu ul {
    list-style: none;
    display: flex;
    gap: 20px;
}

.navbar-menu ul li {
    position: relative;
}

.navbar-menu ul li a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

.navbar-menu ul li a:hover {
    background-color: #145bb5; /* Darker blue on hover */
    color: #ffffff;
}

/* Responsive Navbar Styling */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
    }

    .navbar-menu ul {
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }

    .navbar-menu ul li {
        text-align: center;
        width: 100%;
    }
}

/* Style for Main Section (matches job content) */
main {
    padding: 20px;
    background-color: #f3f7fc;
    min-height: 100vh;
}

h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

/* Filter Section */
.filter-section {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.filter-section input,
.filter-section select,
.filter-section button {
    padding: 8px 12px;
    font-size: 16px;
    border: 1px solid #d1d9e6;
    border-radius: 4px;
    outline: none;
}

.filter-section button {
    background-color: #1a73e8;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter-section button:hover {
    background-color: #145bb5;
}

/* Job Listings */
.job-section {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 15px 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.job-header h3 {
    color: #1a73e8;
    font-size: 22px;
    margin-bottom: 5px;
}

.job-header span {
    font-size: 16px;
    color: #555;
}

.job-details p {
    color: #666;
    margin: 5px 0;
}

.job-apply button {
    padding: 10px 20px;
    background-color: #1a73e8;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.job-apply button:hover {
    background-color: #145bb5;
}

.no-jobs {
    color: #999;
    font-size: 18px;
    text-align: center;
    padding: 20px 0;
}


    </style>
</head>

<body>

<header class="navbar">
    <h1 class="navbar-title">Welcome, Jobseeker</h1>
    <nav class="navbar-menu">
        <ul>
            <li><a href="jobseeker_dashboard.php">Dashboard</a></li>
            <li><a href="job_seeker_applications.php">My Applications</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Find Your Next Opportunity</h2>

    <!-- Filter Section for Job Title Only -->
    <div class="filter-section">
        <input type="text" id="search-job" placeholder="Search Job Title...">
        <button id="filter-btn">Filter</button>
    </div>

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
                <div class="job-apply">
                    <button onclick="window.location.href='apply_job.php?job_id=<?= $job['id'] ?>'">Apply Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-jobs">No jobs available at the moment.</p>
    <?php endif; ?>
</main>

<script>
    document.getElementById('filter-btn').addEventListener('click', function () {
        const searchValue = document.getElementById('search-job').value.toLowerCase();
        const jobSections = document.querySelectorAll('.job-section');

        jobSections.forEach(section => {
            const title = section.querySelector('.job-header h3').textContent.toLowerCase();

            if (searchValue === '' || title.includes(searchValue)) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });
    });
</script>
