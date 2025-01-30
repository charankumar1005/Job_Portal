<?php
require 'config.php';
session_start();

// Redirect if not logged in or not a jobseeker
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'jobseeker') {
    header("Location: login.html");
    exit();
}

// Check if job_id is set in the URL
if (!isset($_GET['job_id'])) {
    echo "Error: Job ID is missing.";
    exit();
}

$job_id = intval($_GET['job_id']);

// Check if the request is a POST method (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply'])) {
    // Capture the form inputs
    $user_id = intval($_SESSION['user_id']); // Use logged-in user ID
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $resume = $_POST['resume']; // Assuming a URL; if file, change to file handling
    $expected_salary = intval($_POST['expected_salary']);
    $current_salary = intval($_POST['current_salary']);

    // Prepare SQL query to insert application data
    $sql = "INSERT INTO applications (job_id, user_id, name, dob, experience, skills, resume, expected_salary, current_salary, application_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";
    
    $stmt = $conn->prepare($sql);

    // Check if statement prepared successfully
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }

    // Bind parameters and execute statement
    $stmt->bind_param("iisssssii", $job_id, $user_id, $name, $dob, $experience, $skills, $resume, $expected_salary, $current_salary);

    if ($stmt->execute()) {
        // Redirect to jobseeker dashboard upon successful application
        header("Location: jobseeker_dashboard.php");
        exit();
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply for Job</title>
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    max-width: 700px;
    width: 100%;
    padding: 2rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.container h2 {
    color: #007bff;
    text-align: center;
    font-size: 1.8em;
    margin-bottom: 1.5rem;
}

/* Flex layout for form rows */
.form-row {
    display: flex;
    gap: 20px;
}

/* Left and Right columns */
.form-left, .form-right {
    flex: 1;
}

.application-form label {
    font-weight: bold;
    color: #333;
    margin-top: 1rem;
    display: block;
}

.application-form input[type="text"],
.application-form input[type="date"],
.application-form input[type="number"],
.application-form input[type="file"],
.application-form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

.application-form textarea {
    resize: vertical;
    height: 80px;
}

/* Submit button styling */
.submit-btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    margin-top: 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #0056b3;
}



    </style>
</head>

<body>
<div class="container">
        <h2><center>Job Application Form</center></h2>

        <form action="apply_job.php?job_id=<?= $job_id ?>" method="POST" class="application-form" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-left">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>

                    <label for="experience">Experience:</label>
                    <input type="text" id="experience" name="experience" required>

                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" required></textarea>
                </div>

                <div class="form-right">
                    <label for="resume">Resume (Upload):</label>
                    <input type="file" id="resume" name="resume" required>

                    <label for="expected_salary">Expected Salary:</label>
                    <input type="number" id="expected_salary" name="expected_salary" required>

                    <label for="current_salary">Current Salary:</label>
                    <input type="number" id="current_salary" name="current_salary">
                </div>
            </div>
            <button type="submit" class="submit-btn" name="apply">Submit Application</button>
        </form>
    </div>
</body>

</html>
