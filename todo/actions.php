<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_check($_POST['_csrf'] ?? '');

$op = $_GET['op'] ?? '';
$id = (int) ($_POST['id'] ?? 0);
$pdo = db();

switch ($op) {
    case 'add':
        $text = trim($_POST['text'] ?? '');
        if ($text !== '') {
            $stmt = $pdo->prepare('INSERT INTO todos (text, done, created_at) VALUES (?, 0, datetime("now"))');
            $stmt->execute([$text]);
        }
        break;

    case 'toggle':
        $stmt = $pdo->prepare('UPDATE todos SET done = 1 - done WHERE id = ?');
        $stmt->execute([$id]);
        break;

    case 'edit':
        $text = trim($_POST['text'] ?? '');
        if ($text !== '' && $id > 0) {
            $stmt = $pdo->prepare('UPDATE todos SET text = ? WHERE id = ?');
            $stmt->execute([$text, $id]);
        }
        break;

    case 'delete':
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ?');
            $stmt->execute([$id]);
        }
        break;
}

header('Location: index.php');
exit;
