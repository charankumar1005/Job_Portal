<?php
require 'config.php'; // Include database configuration
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Initialize optional fields
    $skills = null;
    $company_name = null;
    $company_location = null;
    $resumePath = null;

    // Handle additional fields based on role
    if ($role === 'jobseeker') {
        $skills = isset($_POST['skills']) ? trim($_POST['skills']) : null;

        // Handle resume upload for jobseekers
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $resumeFile = $_FILES['resume'];
            $resumeName = time() . '_' . basename($resumeFile['name']);
            $resumeDir = 'uploads/';
            $resumePath = $resumeDir . $resumeName;

            if (!is_dir($resumeDir)) {
                mkdir($resumeDir, 0755, true); // Create uploads directory if it doesn't exist
            }
            move_uploaded_file($resumeFile['tmp_name'], $resumePath);
        }
    } elseif ($role === 'employer') {
        $company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : null;
        $company_location = isset($_POST['company_location']) ? trim($_POST['company_location']) : null;
    }

    // Prepare SQL query to insert new user
    $sql = "INSERT INTO users (username, email, password, role, skills, company_name, company_location, resume) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $username, $email, $password, $role, $skills, $company_name, $company_location, $resumePath);

    // Execute and check if registration was successful
    if ($stmt->execute()) {
        echo "Registration successful!";
        header('Location: login.html'); // Redirect to login page after successful registration
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Portal</title>
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Styling for the registration container */
        .register-container {
            background-color: #f4f9ff; /* Light background for contrast */
            max-width: 400px;
            margin: 50px auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Header for the registration form */
        .register-container h2 {
            color: #002f4b; /* Dark blue color */
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        /* General styling for the form and inputs */
        .register-form {
            display: flex;
            flex-direction: column;
        }

        .register-form input,
        .register-form select {
            padding: 0.8rem;
            margin: 0.6rem 0;
            border: 1px solid #00aaff; /* Light blue border */
            border-radius: 5px;
            font-size: 1rem;
        }

        .register-form input:focus,
        .register-form select:focus {
            outline: none;
            border-color: #ffcc00; /* Gold border on focus */
            box-shadow: 0 0 8px rgba(255, 204, 0, 0.5);
        }

        /* Styling for the additional fields */
        .additional-fields {
            display: none; /* Hidden by default */
            margin-top: 0.6rem;
        }

        /* Register button styling */
        .register-button {
            background-color: #00aaff;
            color: #ffffff;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }

        .register-button:hover {
            background-color: #ffcc00; /* Gold color on hover */
        }

        /* Login link styling */
        .login-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #00aaff; /* Light blue color */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #ffcc00; /* Gold color on hover */
        }
    </style>
    <script>
        function showAdditionalFields(role) {
            const jobseekerFields = document.querySelectorAll('.jobseeker-fields input');
            const employerFields = document.querySelectorAll('.employer-fields input');
            
            // Hide all fields
            document.querySelector('.jobseeker-fields').style.display = 'none';
            document.querySelector('.employer-fields').style.display = 'none';
            
            // Remove 'required' attribute for hidden fields
            jobseekerFields.forEach(field => field.removeAttribute('required'));
            employerFields.forEach(field => field.removeAttribute('required'));
            
            // Show relevant fields and add 'required' attribute
            if (role === 'jobseeker') {
                document.querySelector('.jobseeker-fields').style.display = 'block';
                jobseekerFields.forEach(field => field.setAttribute('required', true));
            } else if (role === 'employer') {
                document.querySelector('.employer-fields').style.display = 'block';
                employerFields.forEach(field => field.setAttribute('required', true));
            }
        }
    </script>
</head>

<body>
    <div class="register-container">
        <h2>Create Your Account</h2>
        <form action="register.php" method="POST" class="register-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" id="role" onchange="showAdditionalFields(this.value)" required>
                <option value="">Select Your Role</option>
                <option value="jobseeker">Jobseeker</option>
                <option value="employer">Employer</option>
            </select>

            <!-- Role-specific fields -->
            <div class="additional-fields jobseeker-fields">
                <input type="text" name="skills" placeholder="Skills">
            </div>
            <div class="additional-fields employer-fields">
                <input type="text" name="company_name" placeholder="Company Name">
                <input type="text" name="company_location" placeholder="Company Location">
            </div>

            <button type="submit" class="register-button">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.html">Login here</a></p>
    </div>
</body>

</html>
