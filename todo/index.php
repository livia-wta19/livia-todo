<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();

$pdo   = db();
$token = csrf_token();

// Edit mode: which todo is being edited
$edit_id = (int) ($_GET['edit'] ?? 0);

$todos = $pdo->query('SELECT * FROM todos ORDER BY done ASC, created_at DESC')->fetchAll();
$total = count($todos);
$done  = array_sum(array_column($todos, 'done'));

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(APP_NAME) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
  <span class="logo"><span class="logo-ai">AI</span><span class="logo-name"> TODO</span></span>
  <nav class="topbar-nav">
    <span class="topbar-user">// <?= e(AUTH_USER) ?></span>
    <a href="logout.php" class="topbar-logout">Odhlásiť</a>
  </nav>
</header>

<main class="app-wrap">

  <h1 class="page-title"># Moje úlohy</h1>

  <!-- Add form -->
  <form class="add-form" method="post" action="actions.php?op=add">
    <input type="hidden" name="_csrf" value="<?= e($token) ?>">
    <input class="add-input" type="text" name="text"
           placeholder="Nová úloha..." required maxlength="500" autofocus>
    <button class="btn-primary" type="submit">+ Pridať</button>
  </form>

  <!-- Todo list -->
  <?php if (empty($todos)): ?>
  <div class="callout callout-info">
    📋 Žiadne úlohy. Pridaj prvú vyššie!
  </div>
  <?php else: ?>
  <ul class="todo-list">
    <?php foreach ($todos as $t): ?>
    <?php $is_edit = ($edit_id === (int)$t['id']); ?>
    <li class="todo-item <?= $t['done'] ? 'is-done' : '' ?>">

      <!-- Toggle checkbox -->
      <form class="form-inline" method="post" action="actions.php?op=toggle">
        <input type="hidden" name="_csrf" value="<?= e($token) ?>">
        <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
        <button type="submit" class="checkbox <?= $t['done'] ? 'checked' : '' ?>"
                title="<?= $t['done'] ? 'Označiť ako neskončené' : 'Označiť ako hotové' ?>">
          <?= $t['done'] ? '✓' : '' ?>
        </button>
      </form>

      <!-- Text or inline edit -->
      <?php if ($is_edit): ?>
      <form class="form-inline edit-form" method="post" action="actions.php?op=edit">
        <input type="hidden" name="_csrf" value="<?= e($token) ?>">
        <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
        <input class="edit-input" type="text" name="text"
               value="<?= e($t['text']) ?>" required maxlength="500" autofocus>
        <button class="btn-save" type="submit">Uložiť</button>
        <a class="btn-cancel" href="index.php">Zrušiť</a>
      </form>
      <?php else: ?>
      <span class="todo-text"><?= e($t['text']) ?></span>
      <?php endif ?>

      <!-- Action buttons (only when not editing) -->
      <?php if (!$is_edit): ?>
      <div class="todo-actions">
        <a class="action-edit" href="index.php?edit=<?= (int)$t['id'] ?>"
           title="Editovať">edit</a>
        <form class="form-inline" method="post" action="actions.php?op=delete"
              onsubmit="return confirm('Zmazať túto úlohu?')">
          <input type="hidden" name="_csrf" value="<?= e($token) ?>">
          <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
          <button type="submit" class="action-delete" title="Zmazať">del</button>
        </form>
      </div>
      <?php endif ?>

    </li>
    <?php endforeach ?>
  </ul>
  <?php endif ?>

  <p class="footer-stats">// <?= $total ?> úloh, <?= $done ?> hotových</p>

</main>

</body>
</html>
