<?php
define('APP_NAME', 'AI TODO');

// ── UPRAV TIETO DVE HODNOTY ────────────────────────────────────────────────
define('AUTH_USER', 'livia');

// Spusti generate_hash.php raz v prehliadači → skopíruj hash sem.
// Prípadne cez terminál: php -r "echo password_hash('TVOJE_HESLO', PASSWORD_DEFAULT);"
define('AUTH_PASS_HASH', 'REPLACE_WITH_HASH');
// ──────────────────────────────────────────────────────────────────────────

define('SESSION_NAME', 'aitodo_sess');
// Na Railway: mountuj Volume na /data (Settings → Volumes → Mount Path: /data)
// Lokálne (php -S): cesta todo/data/todo.sqlite
define('DB_PATH', getenv('RAILWAY_ENVIRONMENT') ? '/data/todo.sqlite' : __DIR__ . '/data/todo.sqlite');
