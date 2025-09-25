<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: tasks.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Simple To-Do App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(120deg, #89f7fe 0, #66a6ff 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .welcome-card {
      background: white;
      padding: 40px 30px;
      border-radius: 1rem;
      box-shadow: 0 6px 25px rgba(0,0,0,0.15);
      text-align: center;
      max-width: 400px;
      width: 100%;
    }
    .btn-primary, .btn-success {
      width: 120px;
      margin: 10px;
      border-radius: 25px;
      font-weight: 600;
      padding: 10px;
    }
  </style>
</head>
<body>
  <div class="welcome-card">
    <h1 class="mb-4">Simple To-Do App</h1>
    <p class="mb-4">Manage your tasks quickly and easily.</p>
    <a href="login.php" class="btn btn-primary">Login</a>
    <a href="register.php" class="btn btn-success">Register</a>
  </div>
</body>
</html>
