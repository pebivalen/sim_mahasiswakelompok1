<?php
require_once __DIR__ . '/config.php';
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa pengaturan database di config.php.');
}
$conn->set_charset('utf8mb4');
