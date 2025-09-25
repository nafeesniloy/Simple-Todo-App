<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: tasks.php");
        exit();
    } else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Use in public/login.php and similarly for register.php -->
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(120deg, #f6d365 0, #fda085 100%); }
    .login-container {
      min-height: 100vh; display: flex; justify-content: center; align-items: center;
    }
    .card {
      box-shadow: 0 4px 24px rgba(0,0,0,0.15);
      border: none;
      border-radius: 1.2rem;
    }
    .form-floating label { color: #888; }
    .btn-primary {
      background: linear-gradient(90deg, #47caff 0, #677fff 100%);
      border: none;
    }
    .btn-primary:hover { opacity:0.9; }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card p-4" style="width:350px;">
      <h3 class="text-center mb-3">Sign In</h3>
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <div class="mt-3 text-center">
        <small>No account? <a href="register.php">Register here</a></small>
      </div>
    </div>
  </div>
</body>
</html>
