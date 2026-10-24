<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/db.php';

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function logged_in() { return !empty($_SESSION['id_pengguna']); }
function require_login() {
    if (!logged_in()) { header('Location: login.php'); exit; }
}
function role() { return $_SESSION['role'] ?? 'MAHASISWA'; }
function require_roles(array $roles) {
    require_login();
    if (!in_array(role(), $roles, true)) {
        http_response_code(403);
        exit('403 - Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf'] ?? '';
        if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(403); exit('Token keamanan tidak valid. Muat ulang halaman.');
        }
    }
}
function header_html($title='') {
    $name = $title ? e($title).' - ' : '';
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>'.$name.e(APP_NAME).'</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<header class="top"><a class="brand" href="index.php">'.e(APP_NAME).'</a><nav>';
    if (logged_in()) {
        echo '<a href="dashboard.php">Dashboard</a><a href="dosen.php">Data Dosen</a><a href="laporan.php">Laporan</a>';
        if (in_array(role(), ['ADMIN','OPERATOR'], true)) echo '<a href="master.php">Data Master</a>';
        echo '<a href="logout.php">Keluar</a>';
    } else echo '<a href="login.php">Login</a>';
    echo '</nav></header><main class="container">';
}
function footer_html() {
    echo '</main><footer>Praktikum Web Tradisional &amp; Web Service/API · UM Bengkulu</footer></body></html>';
}
