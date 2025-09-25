<?php
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

$user_id = $_SESSION['user_id'] ?? 0;

$action = $_POST['action'] ?? '';
$csrf = $_POST['csrf_token'] ?? '';

if (!verify_csrf_token($csrf)) {
    echo json_encode(['success'=>false, 'error'=>'Invalid CSRF token']);
    exit();
}

function fetch_tasks_html($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll();
    if (empty($tasks)) {
        return '<div class="alert alert-info">No tasks yet. Add your first task!</div>';
    }
    $html = '<ul class="list-group">';
    foreach ($tasks as $task) {
        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
        $html .= '<div>';
        $html .= '<button data-id="'.htmlspecialchars($task['id']).'" class="btn btn-sm btn-link btn-toggle">'.($task['is_complete'] ? '☑' : '☐').'</button> ';
        $html .= '<span '.($task['is_complete'] ? 'class="task-complete"' : '').' data-id="'.htmlspecialchars($task['id']).'">'.htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8').'</span>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<button data-id="'.htmlspecialchars($task['id']).'" class="btn btn-sm btn-secondary btn-edit">Edit</button> ';
        $html .= '<button data-id="'.htmlspecialchars($task['id']).'" class="btn btn-sm btn-danger btn-delete">Delete</button>';
        $html .= '</div>';
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

try {
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            echo json_encode(['success'=>false, 'error'=>'Title cannot be empty']);
            exit();
        }
        $title = sanitize($title);
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title) VALUES (?, ?)");
        $stmt->execute([$user_id, $title]);
        echo json_encode(['success'=>true, 'html'=>fetch_tasks_html($pdo, $user_id)]);
        exit();
    }

    if ($action === 'edit') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        if ($id <= 0 || $title === '') {
            echo json_encode(['success'=>false, 'error'=>'Invalid input']);
            exit();
        }
        $title = sanitize($title);
        $stmt = $pdo->prepare("UPDATE tasks SET title=? WHERE id=? AND user_id=?");
        $stmt->execute([$title, $id, $user_id]);
        echo json_encode(['success'=>true, 'html'=>fetch_tasks_html($pdo, $user_id)]);
        exit();
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success'=>false, 'error'=>'Invalid id']);
            exit();
        }
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
        echo json_encode(['success'=>true, 'html'=>fetch_tasks_html($pdo, $user_id)]);
        exit();
    }

    if ($action === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success'=>false, 'error'=>'Invalid id']);
            exit();
        }
        $stmt = $pdo->prepare("UPDATE tasks SET is_complete = 1 - is_complete WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
        echo json_encode(['success'=>true, 'html'=>fetch_tasks_html($pdo, $user_id)]);
        exit();
    }

    if ($action === 'list') {
        echo json_encode(['success'=>true, 'html'=>fetch_tasks_html($pdo, $user_id)]);
        exit();
    }

    echo json_encode(['success'=>false, 'error'=>'Unknown action']);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'error'=>'Server error']);
}
