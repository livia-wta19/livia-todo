<?php
define('APP_NAME', 'AI TODO');

// ── UPRAV TIETO DVE HODNOTY ────────────────────────────────────────────────
define('AUTH_USER', 'livia');

// Spusti generate_hash.php raz v prehliadači → skopíruj hash sem.
// Prípadne cez terminál: php -r "echo password_hash('TVOJE_HESLO', PASSWORD_DEFAULT);"
define('AUTH_PASS_HASH', 'REPLACE_WITH_HASH');
// ──────────────────────────────────────────────────────────────────────────

define('SESSION_NAME', 'aitodo_sess');
define('DB_PATH', __DIR__ . '/data/todo.sqlite');
