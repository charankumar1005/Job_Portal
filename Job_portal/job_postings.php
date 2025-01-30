<?php
require 'config.php';

// Fetch job postings
$sql_jobs = "SELECT * FROM jobs";
$result_jobs = $conn->query($sql_jobs);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings - Job Portal</title>
    <style>
        /* Reset styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    padding: 2rem;
}

header {
    background-color: #333;
    padding: 1rem;
    margin-bottom: 2rem;
}

nav ul {
    list-style: none;
    display: flex;
    justify-content: flex-end;
}

nav ul li {
    margin-left: 2rem;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

main {
    max-width: 900px;
    margin: 0 auto;
    padding: 1rem;
}

.page-title {
    font-size: 2rem;
    color: #0056b3;
    text-align: center;
    margin-bottom: 1.5rem;
}

.job-list {
    list-style: none;
    padding: 0;
}

.job-ad {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 5px solid #007bff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.job-ad:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.job-title {
    font-size: 1.5rem;
    color: #007bff;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

.company-name {
    font-size: 1rem;
    color: #555;
    margin-bottom: 0.75rem;
    font-style: italic;
}

.job-description {
    font-size: 0.95rem;
    color: #444;
    margin-bottom: 1rem;
}

.job-details {
    font-size: 0.9rem;
    color: #666;
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.location,
.salary {
    display: inline-block;
}

.apply-now {
    display: inline-block;
    color: #fff;
    background-color: #28a745;
    padding: 0.5rem 1rem;
    border-radius: 3px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

.apply-now:hover {
    background-color: #218838;
}

    </style>
    <!-- <link rel="stylesheet" href="job_postings.css"> -->
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2 class="page-title">Job Advertisements</h2>
        <ul class="job-list">
            <?php while ($job = $result_jobs->fetch_assoc()): ?>
                <li class="job-ad">
                    <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p class="company-name"><?php echo htmlspecialchars($job['company']); ?></p>
                    <p class="job-description"><?php echo htmlspecialchars($job['description']); ?></p>
                    <div class="job-details">
                        <span class="location">📍 <?php echo htmlspecialchars($job['location']); ?></span>
                        <span class="salary">💰 <?php echo htmlspecialchars($job['salary']); ?></span>
                    </div>
                    <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="apply-now">Apply Now</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>
</body>

</html>
