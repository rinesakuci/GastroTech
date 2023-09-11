<?php
// Start session
session_start();

// Include database connection file
include "db.php";

// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  // Verify CSRF token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token!");
  }

  // Get username and password from form
  $uname = $_POST['uname'];
  $pass = $_POST['pass'];

  try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$server;dbname=$db", $user, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE uname = :uname AND pass = :pass");

    // Bind parameters
    $stmt->bindParam(':uname', $uname);
    $stmt->bindParam(':pass', $pass);

    // Execute query
    $stmt->execute();

    // Check if the query returned a row
    if ($stmt->rowCount() > 0) {
      header("Location: admin/admin-panel.php");
      exit();
    } else {
      echo "Not logged in";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Mrizi</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <style>
            body {
                font-family: "Times New Roman", Georgia, Serif;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: Times New Roman;
                letter-spacing: 5px;
            }
            body {
  background-image: url("assets/images/d.jpeg");
  background-size: cover;
            }
            .form-container {
              margin: 100px auto 0;
  width: 50%;
  max-width: 500px;
  background-color: #f2f2f2;
  padding: 20px;
  border-radius: 10px;
  font-family:  'Times New Roman', Georgia, Serif;
  font-size: 18px;
  letter-spacing: 4px;
 
}

form {
  display: flex;
  flex-direction: column;

}

label {
  
  margin-bottom: 5px;
}

input[type="text"],
input[type="password"] {

  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  border: none;
  background-color: #e6e6e6;
}

input[type="submit"] {
  background-color: darkgrey;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  letter-spacing: 2px;
}

input[type="submit"]:hover {
  background-color: red;
}

        </style>
    </head>
<body>

<!-- Navbar (sit on top) -->
<div class="w3-top">
    <div class="w3-bar w3-white w3-padding w3-card" style="letter-spacing:4px;">

    <a href="index.php" class="w3-bar-item w3-button">Mrizi</a>			
        <a href="index.php" class="w3-bar-item w3-button">Home</a>
        <a href="takeaway.php" class="w3-bar-item w3-button">Takeaway</a>

        <!-- Right-sided navbar links. Hide them on small screens -->

    </div>

<div class="form-container">
<form method="post" action="login.php" >
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
  <label for="email">Name:</label>
  <input type="text" id="uname" name="uname" required>

  <label for="password">Password:</label>
  <input type="password" id="pass" name="pass" required>

  <input type="submit" value="Login">
</form>
</div>
</div>
</body>

</html>