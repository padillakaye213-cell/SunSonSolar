<?php
// Sun Son Solar — configuration. Edit these values for your server.
define('DB_HOST', 'localhost');
define('DB_NAME', 'sunson_solar');
define('DB_USER', 'root');
define('DB_PASS', '');

// If the site lives in a sub-folder (e.g. http://localhost/sunson) set '/sunson'. Otherwise leave ''.
define('BASE_URL', '/sunson');

// Idle time (seconds) before a logged-in user is signed out automatically.
define('SESSION_TIMEOUT', 900);

define('UPLOAD_DIR', __DIR__ . '/uploads');

// Local testing only: show the password-reset link on screen when no mail server exists. Set false in production.
define('DEV_SHOW_RESET_LINK', true);
