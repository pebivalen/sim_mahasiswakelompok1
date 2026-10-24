
<?php
require_once 'functions.php';
require_login();

$canEdit = in_array(role(), ['ADMIN', 'OPERATOR'], true);
$msg = '';
$error = '';

/*
|--------------------------------------------------------------------------
| Pilihan dropdown
|--------------------------------------------------------------------------
*/
$dropdowns = [
    'jabatan_fungsional' => [
        'Belum Memiliki',
        'Asisten Ahli',
        'Lektor',
        'Lektor Kepala',
        'Guru Besar'
    ],
    'kepangkatan' => [
        'Belum Ada',
        'III/a',
        'III/b',
        'III/c',
        'III/d',
        'IV/a',
        'IV/b',
        'IV/c',
        'IV/d',
        'IV/e'
    ],
    'pendidikan_terakhir' => [
        'D3',
        'D4',
        'S1',
        'S2',
        'S3',
        'Profesi'
    ],
    'rumpun_ilmu' => [
        'Teknik',
        'Pendidikan',
        'Ekonomi',
        'Hukum',
        'Kesehatan',
        'Pertanian',
        'Ilmu Sosial',
        'MIPA',
        'Lainnya'
    ],
    'ikatan_kerja' => [
        'Dosen Tetap',
        'Dosen Tidak Tetap',
        'Dosen DPK',
        'Kontrak'
    ]
];

$labels = [
    'jabatan_fungsional' => 'Jabatan Fungsional',
    'kepangkatan' => 'Kepangkatan',
    'pendidikan_terakhir' => 'Pendidikan Terakhir',
    'rumpun_ilmu' => 'Rumpun Ilmu',
    'ikatan_kerja' => 'Ikatan Kerja'
];

/*
|--------------------------------------------------------------------------
| Proses tambah, edit, dan hapus
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    require_roles(['ADMIN', 'OPERATOR']);

    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $st = $conn->prepare(
                'DELETE FROM dosen WHERE id_dosen = ?'
            );
            $st->bind_param('i', $id);

            if ($st->execute()) {
                $msg = 'Data dosen berhasil dihapus.';
            } else {
                $error = 'Data dosen gagal dihapus.';
            }

            $st->close();
        } else {
            $error = 'ID dosen tidak valid.';
        }
    }

    elseif ($action === 'save') {
        $id = (int)($_POST['id_dosen'] ?? 0);

        $reg = trim($_POST['nomor_registrasi'] ?? '');
        $nuptk = trim($_POST['nuptk'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $nip = trim($_POST['nip'] ?? '');

        $jab = trim($_POST['jabatan_fungsional'] ?? '');
        $pangkat = trim($_POST['kepangkatan'] ?? '');
        $pend = trim($_POST['pendidikan_terakhir'] ?? '');
        $rumpun = trim($_POST['rumpun_ilmu'] ?? '');

        $serdos = trim($_POST['status_serdos'] ?? 'Belum');
        $pegawai = trim($_POST['status_pegawai'] ?? 'Tetap');
        $ikatan = trim($_POST['ikatan_kerja'] ?? '');
        $aktiv = trim($_POST['status_aktivitas'] ?? 'Aktif');

        $penempatan = (int)($_POST['id_program_studi'] ?? 0);

        /*
         * Pastikan pilihan dropdown sesuai daftar yang tersedia.
         */
        foreach ($dropdowns as $key => $options) {
            $postedValue = trim($_POST[$key] ?? '');

            if ($postedValue !== '' &&
                !in_array($postedValue, $options, true)) {
                $error = 'Pilihan ' . $labels[$key] . ' tidak valid.';
                break;
            }
        }

        if ($error === '' && $nama === '') {
            $error = 'Nama dosen wajib diisi.';
        }

        if ($error === '' &&
            !in_array($serdos, ['Belum', 'Sudah'], true)) {
            $error = 'Status Serdos tidak valid.';
        }

        if ($error === '' &&
            !in_array($pegawai, ['Tetap', 'Kontrak', 'Honorer'], true)) {
            $error = 'Status Pegawai tidak valid.';
        }

        if ($error === '' &&
            !in_array($aktiv, ['Aktif', 'Cuti', 'Tidak Aktif'], true)) {
            $error = 'Status Aktivitas tidak valid.';
        }

        if ($error === '' && $id > 0) {
            $sql = 'UPDATE dosen SET
                nomor_registrasi = ?,
                nuptk = ?,
                nama = ?,
                nip = ?,
                jabatan_fungsional = ?,
                kepangkatan = ?,
                pendidikan_terakhir = ?,
                rumpun_ilmu = ?,
                status_serdos = ?,
                status_pegawai = ?,
                ikatan_kerja = ?,
                status_aktivitas = ?,
                id_program_studi = ?
                WHERE id_dosen = ?';

            $st = $conn->prepare($sql);
            $st->bind_param(
                'ssssssssssssii',
                $reg,
                $nuptk,
                $nama,
                $nip,
                $jab,
                $pangkat,
                $pend,
                $rumpun,
                $serdos,
                $pegawai,
                $ikatan,
                $aktiv,
                $penempatan,
                $id
            );

            if ($st->execute()) {
                $msg = 'Data dosen berhasil diperbarui.';
            } else {
                $error = 'Data dosen gagal diperbarui.';
            }

            $st->close();
        }

        elseif ($error === '' && $id === 0) {
            $sql = 'INSERT INTO dosen (
                nomor_registrasi,
                nuptk,
                nama,
                nip,
                jabatan_fungsional,
                kepangkatan,
                pendidikan_terakhir,
                rumpun_ilmu,
                status_serdos,
                status_pegawai,
                ikatan_kerja,
                status_aktivitas,
                id_program_studi
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            $st = $conn->prepare($sql);
            $st->bind_param(
                'ssssssssssssi',
                $reg,
                $nuptk,
                $nama,
                $nip,
                $jab,
                $pangkat,
                $pend,
                $rumpun,
                $serdos,
                $pegawai,
                $ikatan,
                $aktiv,
                $penempatan
            );

            if ($st->execute()) {
                $msg = 'Data dosen berhasil ditambahkan.';
            } else {
                $error = 'Data dosen gagal ditambahkan.';
            }

            $st->close();
        }
    }
}

/*
|--------------------------------------------------------------------------
| Ambil data untuk formulir edit
|--------------------------------------------------------------------------
*/
$edit = null;

if (isset($_GET['edit']) && $canEdit) {
    $id = (int)$_GET['edit'];

    $st = $conn->prepare(
        'SELECT * FROM dosen WHERE id_dosen = ?'
    );
    $st->bind_param('i', $id);
    $st->execute();

    $edit = $st->get_result()->fetch_assoc();
    $st->close();
}

/*
|--------------------------------------------------------------------------
| Pencarian data dosen
|--------------------------------------------------------------------------
*/
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $like = '%' . $q . '%';

    $sql = 'SELECT d.*, p.nama_program_studi
            FROM dosen d
            LEFT JOIN program_studi p
                ON p.id_program_studi = d.id_program_studi
            WHERE d.nama LIKE ?
               OR d.nip LIKE ?
               OR d.nomor_registrasi LIKE ?
            ORDER BY d.nama';

    $st = $conn->prepare($sql);
    $st->bind_param('sss', $like, $like, $like);
    $st->execute();
    $rows = $st->get_result();
} else {
    $rows = $conn->query(
        'SELECT d.*, p.nama_program_studi
         FROM dosen d
         LEFT JOIN program_studi p
            ON p.id_program_studi = d.id_program_studi
         ORDER BY d.nama'
    );
}

/*
|--------------------------------------------------------------------------
| Daftar program studi untuk dropdown penempatan
|--------------------------------------------------------------------------
*/
$prodis = $conn->query(
    'SELECT id_program_studi, nama_program_studi
     FROM program_studi
     ORDER BY nama_program_studi'
);

header_html('Data Dosen');
?>

<h1>Data Dosen</h1>

<?php if ($msg !== ''): ?>
    <div class="success"><?= e($msg) ?></div>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <div class="alert"><?= e($error) ?></div>
<?php endif; ?>

<form class="search" method="get">
    <input
        type="text"
        name="q"
        placeholder="Cari nama, NIP, nomor registrasi..."
        value="<?= e($q) ?>"
    >
    <button class="btn" type="submit">Cari</button>
</form>

<?php if ($canEdit): ?>
<details class="form-card" <?= $edit ? 'open' : '' ?>>
    <summary>
        <?= $edit ? 'Edit Dosen' : 'Tambah Dosen' ?>
    </summary>

    <form method="post">
        <input
            type="hidden"
            name="csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <input type="hidden" name="action" value="save">

        <input
            type="hidden"
            name="id_dosen"
            value="<?= e($edit['id_dosen'] ?? 0) ?>"
        >

        <div class="form-grid">

            <?php
            $fields = [
                'nomor_registrasi' => 'Nomor Registrasi',
                'nuptk' => 'NUPTK',
                'nama' => 'Nama Dosen',
                'nip' => 'NIP'
            ];

            foreach ($fields as $key => $label):
            ?>
                <div>
                    <label><?= e($label) ?></label>
                    <input
                        type="text"
                        name="<?= e($key) ?>"
                        value="<?= e($edit[$key] ?? '') ?>"
                        <?= $key === 'nama' ? 'required' : '' ?>
                    >
                </div>
            <?php endforeach; ?>

            <?php foreach ($dropdowns as $key => $options): ?>
                <div>
                    <label><?= e($labels[$key]) ?></label>

                    <select name="<?= e($key) ?>">
                        <option value="">-- Pilih --</option>

                        <?php
                        $current = $edit[$key] ?? '';

                        /*
                         * Pertahankan nilai lama yang tidak ada
                         * dalam daftar pilihan, supaya tidak hilang
                         * saat data lama diedit.
                         */
                        if ($current !== '' &&
                            !in_array($current, $options, true)):
                        ?>
                            <option
                                value="<?= e($current) ?>"
                                selected
                            >
                                <?= e($current) ?> (nilai sebelumnya)
                            </option>
                        <?php endif; ?>

                        <?php foreach ($options as $option): ?>
                            <option
                                value="<?= e($option) ?>"
                                <?= $current === $option ? 'selected' : '' ?>
                            >
                                <?= e($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>

            <div>
                <label>Status Serdos</label>
                <select name="status_serdos">
                    <?php foreach (['Belum', 'Sudah'] as $v): ?>
                        <option
                            value="<?= e($v) ?>"
                            <?= ($edit['status_serdos'] ?? 'Belum') === $v
                                ? 'selected' : '' ?>
                        >
                            <?= e($v) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Status Pegawai</label>
                <select name="status_pegawai">
                    <?php foreach (['Tetap', 'Kontrak', 'Honorer'] as $v): ?>
                        <option
                            value="<?= e($v) ?>"
                            <?= ($edit['status_pegawai'] ?? 'Tetap') === $v
                                ? 'selected' : '' ?>
                        >
                            <?= e($v) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Status Aktivitas</label>
                <select name="status_aktivitas">
                    <?php foreach (['Aktif', 'Cuti', 'Tidak Aktif'] as $v): ?>
                        <option
                            value="<?= e($v) ?>"
                            <?= ($edit['status_aktivitas'] ?? 'Aktif') === $v
                                ? 'selected' : '' ?>
                        >
                            <?= e($v) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Penempatan / Program Studi</label>
                <select name="id_program_studi">
                    <option value="0">Belum ditentukan</option>

                    <?php if ($prodis): ?>
                        <?php while ($p = $prodis->fetch_assoc()): ?>
                            <option
                                value="<?= (int)$p['id_program_studi'] ?>"
                                <?= (int)($edit['id_program_studi'] ?? 0)
                                    === (int)$p['id_program_studi']
                                    ? 'selected' : '' ?>
                            >
                                <?= e($p['nama_program_studi']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

        </div>

        <button class="btn" type="submit">Simpan</button>
        <a class="btn secondary" href="dosen.php">Batal</a>
    </form>
</details>
<?php endif; ?>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Registrasi</th>
                <th>NUPTK</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan Fungsional</th>
                <th>Kepangkatan</th>
                <th>Pendidikan</th>
                <th>Rumpun Ilmu</th>
                <th>Serdos</th>
                <th>Status Pegawai</th>
                <th>Ikatan Kerja</th>
                <th>Aktivitas</th>
                <th>Penempatan</th>
                <?php if ($canEdit): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
        <?php
        $i = 1;

        if ($rows):
            while ($d = $rows->fetch_assoc()):
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= e($d['nomor_registrasi']) ?></td>
                <td><?= e($d['nuptk']) ?></td>
                <td><?= e($d['nama']) ?></td>
                <td><?= e($d['nip']) ?></td>
                <td><?= e($d['jabatan_fungsional']) ?></td>
                <td><?= e($d['kepangkatan']) ?></td>
                <td><?= e($d['pendidikan_terakhir']) ?></td>
                <td><?= e($d['rumpun_ilmu']) ?></td>
                <td><?= e($d['status_serdos']) ?></td>
                <td><?= e($d['status_pegawai']) ?></td>
                <td><?= e($d['ikatan_kerja']) ?></td>
                <td><?= e($d['status_aktivitas']) ?></td>
                <td><?= e($d['nama_program_studi'] ?? '-') ?></td>

                <?php if ($canEdit): ?>
                    <td class="nowrap">
                        <a href="?edit=<?= (int)$d['id_dosen'] ?>">
                            Edit
                        </a>

                        <form
                            method="post"
                            class="inline"
                            onsubmit="return confirm('Hapus data dosen ini?')"
                        >
                            <input
                                type="hidden"
                                name="csrf"
                                value="<?= e(csrf_token()) ?>"
                            >

                            <input
                                type="hidden"
                                name="action"
                                value="delete"
                            >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int)$d['id_dosen'] ?>"
                            >

                            <button class="link danger" type="submit">
                                Hapus
                            </button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php
            endwhile;
        endif;
        ?>
        </tbody>
    </table>
</div>

<?php footer_html(); ?>
