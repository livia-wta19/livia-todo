<?php
require_once __DIR__ . '/auth.php';

start_session();

// Already logged in → redirect
if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Check if hash is still the placeholder
$setup_needed = (AUTH_PASS_HASH === 'REPLACE_WITH_HASH');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$setup_needed) {
    csrf_check($_POST['_csrf'] ?? '');

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Rate-limit: delay after repeated failures
    $fails = $_SESSION['login_fails'] ?? 0;
    if ($fails >= 3) {
        usleep(800_000); // 0.8s
    }

    if ($username === AUTH_USER && password_verify($password, AUTH_PASS_HASH)) {
        $_SESSION['user'] = AUTH_USER;
        $_SESSION['login_fails'] = 0;
        session_regenerate_id(true);
        header('Location: index.php');
        exit;
    }

    $_SESSION['login_fails'] = $fails + 1;
    $error = 'Nesprávne meno alebo heslo.';
}

$token = csrf_token();
?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prihlásenie — <?= htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<header class="topbar">
  <span class="logo"><span class="logo-ai">AI</span><span class="logo-name"> TODO</span></span>
</header>

<main class="login-wrap">
  <div class="login-card">
    <h1 class="login-title">&gt; Prihlásenie</h1>

    <?php if ($setup_needed): ?>
    <div class="callout callout-warn">
      ⚠ Najprv nastav heslo — otvor <a href="generate_hash.php">generate_hash.php</a>,
      skopíruj hash do <code>config.php</code> a obnov stránku.
    </div>
    <?php elseif ($error): ?>
    <div class="callout callout-error">✗ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif ?>

    <form method="post" action="login.php" autocomplete="off">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

      <div class="field">
        <label for="username">Používateľské meno</label>
        <input type="text" id="username" name="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               required autofocus autocomplete="username">
      </div>

      <div class="field">
        <label for="password">Heslo</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>

      <button type="submit" class="btn-primary" <?= $setup_needed ? 'disabled' : '' ?>>
        Prihlásiť sa →
      </button>
    </form>
  </div>
</main>

</body>
</html>
