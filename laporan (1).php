<?php
require_once 'functions.php'; require_login();
$sql='SELECT d.*,p.nama_program_studi,f.nama_fakultas FROM dosen d LEFT JOIN program_studi p ON p.id_program_studi=d.id_program_studi LEFT JOIN fakultas f ON f.id_fakultas=p.id_fakultas WHERE d.status_aktivitas="Aktif" ORDER BY f.nama_fakultas,p.nama_program_studi,d.nama';
$rows=$conn->query($sql); header_html('Laporan');
?>
<div class="no-print actions"><h1>Laporan Daftar Dosen Aktif</h1><button class="btn" onclick="window.print()">Cetak / Simpan PDF</button></div>
<p><b>Universitas:</b> Universitas Muhammadiyah Bengkulu</p>
<p><b>Fakultas / Program Studi:</b> seluruh fakultas dan program studi</p>
<div class="table-wrap"><table><thead><tr><th>No</th><th>Nomor Registrasi</th><th>NUPTK</th><th>Nama</th><th>NIP</th><th>Jabatan Fungsional</th><th>Kepangkatan</th><th>Pendidikan Terakhir</th><th>Rumpun Ilmu</th><th>Status Serdos</th><th>Status Pegawai</th><th>Ikatan Kerja</th><th>Status Aktivitas</th><th>Penempatan</th></tr></thead><tbody>
<?php $i=1;if($rows)while($d=$rows->fetch_assoc()):?><tr><td><?=$i++?></td><td><?=e($d['nomor_registrasi'])?></td><td><?=e($d['nuptk'])?></td><td><?=e($d['nama'])?></td><td><?=e($d['nip'])?></td><td><?=e($d['jabatan_fungsional'])?></td><td><?=e($d['kepangkatan'])?></td><td><?=e($d['pendidikan_terakhir'])?></td><td><?=e($d['rumpun_ilmu'])?></td><td><?=e($d['status_serdos'])?></td><td><?=e($d['status_pegawai'])?></td><td><?=e($d['ikatan_kerja'])?></td><td><?=e($d['status_aktivitas'])?></td><td><?=e($d['nama_program_studi']??'-')?></td></tr><?php endwhile;?>
</tbody></table></div><?php footer_html(); ?>
