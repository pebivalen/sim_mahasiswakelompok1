APLIKASI SISTEM INFORMASI DOSEN UM BENGKULU
PHP Native + MySQLi, dirancang untuk InfinityFree.

INSTALASI DI INFINITYFREE
1. Buat database MySQL melalui Control Panel InfinityFree.
2. Buka phpMyAdmin hosting, pilih database yang dibuat, lalu Import database/simdosen.sql.
3. Edit config.php dan isi DB_HOST, DB_NAME, DB_USER, DB_PASS dengan detail database hosting.
   Catatan: nama database di hosting biasanya memiliki prefix akun, gunakan nama persis dari panel.
4. Upload isi folder project ini ke htdocs/ melalui File Manager atau FTP. Pastikan index.php berada langsung di htdocs.
5. Buka domain website.
6. Login awal:
   username: admin
   password: password
7. Segera ganti password admin setelah login. Password awal untuk akun praktikum ini adalah `password`. Hapus atau ubah akun contoh.
8. API:
   GET /api/dosen.php
   GET /api/dosen.php?id=1
   Header untuk endpoint terlindungi: X-API-Key: GANTI_API_KEY_DI_CONFIG
   Endpoint mutasi POST/PUT/DELETE memerlukan API key dan role API_ADMIN.

PENTING
- Ganti nilai API_KEY pada config.php sebelum dipublikasikan.
- Data dosen contoh bersifat fiktif. Jangan gunakan sebagai data resmi.
- Hosting gratis dapat membatasi request API dari layanan eksternal/Postman. Endpoint dapat berjalan di hosting, tetapi akses eksternal bergantung pada kebijakan firewall hosting. Jika diblokir, uji dari website sendiri atau gunakan hosting API yang mengizinkan akses eksternal.
- Ini adalah starter project praktikum; uji semua fitur dan sesuaikan dengan instruksi dosen sebelum dikumpulkan.
