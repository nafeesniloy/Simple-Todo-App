<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php?registered=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(120deg, #f6d365 0, #fda085 100%); }
    .register-container {
      min-height: 100vh; display: flex; justify-content: center; align-items: center;
    }
    .card {
      box-shadow: 0 4px 24px rgba(0,0,0,0.15);
      border: none;
      border-radius: 1.2rem;
    }
    .form-floating label { color: #888; }
    .btn-success {
      background: linear-gradient(90deg, #47d147 0, #32a632 100%);
      border: none;
    }
    .btn-success:hover { opacity:0.9; }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="card p-4" style="width:350px;">
      <h3 class="text-center mb-3">Sign Up</h3>
      <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="form-floating mb-3">
          <input name="username" type="text" class="form-control" id="floatingInput" placeholder="Username" required>
          <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating mb-3">
          <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
          <label for="floatingPassword">Password</label>
        </div>
        <button type="submit" class="btn btn-success w-100">Sign Up</button>
      </form>
      <div class="mt-3 text-center">
        <small>Already have an account? <a href="login.php">Login here</a></small>
      </div>
    </div>
  </div>
</body>
</html>