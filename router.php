<?php
// Simple router file for PHP built-in webserver that redirects to index.php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Normalise multiple leading slashes
$uri = preg_replace('#//+#', '/', $uri);

// 1. If bare directory "/orbitstar@1357admin" (without trailing slash) is requested, redirect to index.php with a 302 redirect.
// This preserves the path directory depth so relative assets like css/app.min.css work.
if ($uri === '/orbitstar@1357admin') {
    header("Location: /orbitstar@1357admin/index.php", true, 302);
    exit;
}

// 2. If directory with trailing slash "/orbitstar@1357admin/" is requested, redirect to index.php
if ($uri === '/orbitstar@1357admin/') {
    header("Location: /orbitstar@1357admin/index.php", true, 302);
    exit;
}

// 3. For static assets or files, serve them directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Otherwise serve root index.html
if ($uri === '/' && file_exists(__DIR__ . '/index.html')) {
    include __DIR__ . '/index.html';
    exit;
}
return false;
