<?php
// Pomocný skript — spusti raz, skopíruj hash do config.php, potom ZMAŽ tento súbor.
$hash = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw = trim($_POST['password'] ?? '');
    if (strlen($pw) < 6) {
        $error = 'Heslo musí mať aspoň 6 znakov.';
    } else {
        $hash = password_hash($pw, PASSWORD_DEFAULT);
    }
}
?><!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<title>Generovanie hash — AI TODO</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box}
  body{font-family:'IBM Plex Mono',monospace;background:#f5efe4;color:#1f1f1f;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
  .box{background:#faf6ee;border:1px solid #d9cfbe;border-radius:6px;padding:32px;max-width:480px;width:100%}
  h1{margin:0 0 6px;font-size:18px;color:#3a8a8a}
  p{margin:0 0 20px;font-size:13px;color:#6b6258}
  label{display:block;margin-bottom:6px;font-size:13px;font-weight:600}
  input{width:100%;padding:10px 12px;border:1px solid #d9cfbe;border-radius:6px;background:#f5efe4;font-family:inherit;font-size:14px;margin-bottom:12px}
  button{background:#3a8a8a;color:#fff;border:none;border-radius:6px;padding:10px 18px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer}
  .hash{background:#e6f0ef;border-left:3px solid #3a8a8a;padding:14px;border-radius:0 6px 6px 0;word-break:break-all;font-size:13px;margin-top:20px}
  .warn{background:#fef3cd;border-left:3px solid #d94a4a;padding:10px 14px;border-radius:0 6px 6px 0;font-size:13px;margin-bottom:20px;color:#6b4c1a}
  .error{background:#fde8e8;border-left:3px solid #d94a4a;padding:10px 14px;border-radius:0 6px 6px 0;font-size:13px;margin-bottom:16px;color:#7a1a1a}
</style>
</head>
<body>
<div class="box">
  <h1>&gt; Generovanie hesla</h1>
  <p>Zadaj heslo, ktoré chceš použiť. Skopíruj vygenerovaný hash do <code>config.php</code>.</p>
  <div class="warn">⚠ Po skopírovaní hash-u <strong>zmaž tento súbor</strong> (generate_hash.php) zo servera!</div>
  <?php if ($error): ?>
  <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif ?>
  <form method="post">
    <label for="pw">Nové heslo</label>
    <input type="password" id="pw" name="password" required minlength="6" autofocus>
    <button type="submit">Generovať hash</button>
  </form>
  <?php if ($hash): ?>
  <div class="hash">
    <strong>AUTH_PASS_HASH:</strong><br><br>
    <?= htmlspecialchars($hash) ?>
  </div>
  <p style="margin-top:12px;font-size:12px;color:#6b6258">Skopíruj celý reťazec (vrátane <code>$2y$...</code>) do <code>config.php</code>.</p>
  <?php endif ?>
</div>
</body>
</html>
