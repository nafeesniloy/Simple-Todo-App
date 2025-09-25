<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();
require_once '../includes/functions.php';

$user_id = $_SESSION['user_id'];
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            $error = "Task name cannot be empty.";
        } else {
            $title = sanitize($title);
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title) VALUES (?, ?)");
            $stmt->execute([$user_id, $title]);
            header("Location: tasks.php");
            exit();
        }
    }

    if (isset($_POST['edit_id'])) {
        $title = trim($_POST['edit_title'] ?? '');
        if ($title === '') {
            $error = "Task name cannot be empty.";
            $edit_id = (int) $_POST['edit_id'];  
        } else {
            $title = sanitize($title);
            $id = (int) $_POST['edit_id'];
            $stmt = $pdo->prepare("UPDATE tasks SET title=? WHERE id=? AND user_id=?");
            $stmt->execute([$title, $id, $user_id]);
            header("Location: tasks.php");
            exit();
        }
    }

    if (isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
        header("Location: tasks.php");
        exit();
    }

    if (isset($_POST['toggle_id'])) {
        $id = (int) $_POST['toggle_id'];
        $stmt = $pdo->prepare("UPDATE tasks SET is_complete = 1 - is_complete WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
        header("Location: tasks.php");
        exit();
    }
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tasks</title>
  <link href="assets/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .status-complete { color: #198754; font-weight: 600; }
    .status-incomplete { color: #6c757d; font-weight: 600; }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="card p-4 shadow rounded">
    <h2 class="mb-3 text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
      <a href="logout.php" class="btn btn-warning">Logout</a>
      <form method="POST" class="d-flex w-75">
        <input name="title" class="form-control me-2" placeholder="New Task" autocomplete="off" required>
        <button name="add" type="submit" class="btn btn-primary px-4">Add</button>
      </form>
    </div>

    <table class="table table-hover">
      <thead>
        <tr><th>Task</th><th>Status</th><th style="min-width:160px;">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($tasks as $task): ?>
          <tr>
            <form method="POST" class="d-flex align-items-center w-100">
              <td style="vertical-align: middle; width: 60%;">
                <?php if ($edit_id === $task['id']): ?>
                  <input name="edit_title" value="<?php echo htmlspecialchars($task['title']); ?>" class="form-control" required>
                  <input type="hidden" name="edit_id" value="<?php echo $task['id']; ?>">
                <?php else: ?>
                  <?php echo htmlspecialchars($task['title']); ?>
                <?php endif; ?>
              </td>
              <td style="vertical-align: middle; width: 15%;">
                <button name="toggle_id" value="<?php echo $task['id']; ?>" class="btn btn-sm <?php echo $task['is_complete'] ? 'btn-success status-complete' : 'btn-outline-secondary status-incomplete'; ?>" title="Toggle Completion">
                  <?php echo $task['is_complete'] ? 'Complete' : 'Incomplete'; ?>
                </button>
              </td>
              <td style="vertical-align: middle; width: 25%;">
                <?php if ($edit_id !== $task['id']): ?>
                  <a href="tasks.php?edit=<?php echo $task['id']; ?>" class="btn btn-sm btn-info me-2" title="Edit Task">Edit</a>
                <?php else: ?>
                  <button type="submit" class="btn btn-sm btn-primary me-2" title="Save Changes">Save</button>
                  <a href="tasks.php" class="btn btn-sm btn-secondary me-2" title="Cancel Edit">Cancel</a>
                <?php endif; ?>
                <button name="delete_id" value="<?php echo $task['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')" title="Delete Task">Delete</button>
              </td>
            </form>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="text-center mt-4 mb-3 text-muted small">
  &copy; <?php echo date("Y"); ?> All rights reserved S.M. Nafees Hossain Niloy
</footer>

</body>
</html>
