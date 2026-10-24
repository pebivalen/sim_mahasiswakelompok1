<?php
require_once 'functions.php'; require_login();
$counts = [];
foreach (['dosen'=>'Dosen','fakultas'=>'Fakultas','program_studi'=>'Program Studi','pengguna'=>'Pengguna'] as $table=>$label) {
  $res = $conn->query("SELECT COUNT(*) AS n FROM `$table`"); $counts[$label] = $res ? (int)$res->fetch_assoc()['n'] : 0;
}
header_html('Dashboard');
?>
<h1>Dashboard</h1><p>Selamat datang, <b><?=e($_SESSION['nama'])?></b>. Role: <span class="pill"><?=e(role())?></span></p>
<div class="grid4"><?php foreach($counts as $label=>$count): ?><article class="stat"><span><?=e($label)?></span><strong><?=$count?></strong></article><?php endforeach; ?></div>
<section class="card"><h2>Akses cepat</h2><div class="actions"><a class="btn" href="dosen.php">Kelola Data Dosen</a><a class="btn secondary" href="laporan.php">Lihat Laporan</a><a class="btn secondary" href="api-docs.php">Dokumentasi API</a></div>
<p class="muted">ADMIN: akses penuh. OPERATOR: pengelolaan data dosen. PIMPINAN: melihat data dan laporan.</p></section>
<?php footer_html(); ?>
