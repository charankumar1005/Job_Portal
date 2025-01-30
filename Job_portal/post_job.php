<?php
require 'config.php';
session_start();

// Redirect if the user is not an employer
if ($_SESSION['role'] != 'employer') {
    header("Location: login.html");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $company = trim($_POST['company']);
    $salary = trim($_POST['salary']);
    $posted_by = $_SESSION['user_id'];

    $sql = "INSERT INTO jobs (title, description, location, company, salary, posted_by) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdi", $title, $description, $location, $company, $salary, $posted_by);

    if ($stmt->execute()) {
        echo "<p>Job posted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - Job Portal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Post a Job</h1>
    <nav>
        <ul>
            <li><a href="employer_dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <form action="" method="POST">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Job Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="company">Company Name:</label>
        <input type="text" id="company" name="company" required>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required>

        <button type="submit">Post Job</button>
    </form>
</main>

</body>
</html>
