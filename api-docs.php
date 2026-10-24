<?php require_once 'functions.php'; header_html('Dokumentasi API'); ?>
<h1>Dokumentasi Web Service / API</h1><p>Endpoint mengembalikan respons JSON. URL relatif di bawah mengikuti lokasi aplikasi.</p>
<div class="card"><h3>1. Daftar dosen</h3><code>GET /api/dosen.php</code><p>Mengembalikan daftar dosen. Parameter opsional: <code>?q=nama</code>, <code>?id=1</code>.</p></div>
<div class="card"><h3>2. Menambah dosen</h3><code>POST /api/dosen.php</code><p>Header <code>X-API-Key</code> wajib. Body JSON berisi nama dan kolom dosen. API key contoh harus diganti pada config.php.</p></div>
<div class="card"><h3>3. Memperbarui dosen</h3><code>PUT /api/dosen.php?id=1</code><p>Header <code>X-API-Key</code> wajib. Body JSON memuat kolom yang akan diperbarui.</p></div>
<div class="card"><h3>4. Menghapus dosen</h3><code>DELETE /api/dosen.php?id=1</code><p>Header <code>X-API-Key</code> wajib.</p></div>
<p class="alert">Catatan: InfinityFree dapat memblokir sebagian request API eksternal. Uji pada hosting Anda. Jangan gunakan data pribadi dosen sungguhan tanpa izin.</p>
<?php footer_html(); ?>
