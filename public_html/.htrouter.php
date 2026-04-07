<?php
// .htrouter.php - Router for PHP built-in server
if (preg_match('/\.(?:js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf)$/', $_SERVER['REQUEST_URI'])) {
    return false; // Serve static files normally
}
// Everything else goes to index.php
return true;