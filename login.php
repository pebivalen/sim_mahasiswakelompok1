
<?php
require_once 'functions.php';

// Jika sudah login, langsung ke dashboard
if (logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare(
            "SELECT id_pengguna, username, password_hash,
                    nama, role, aktif
             FROM pengguna
             WHERE username = ?
             LIMIT 1"
        );

        if (!$stmt) {
            die('Query login gagal. Periksa konfigurasi database.');
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (
            $user &&
            (int) $user['aktif'] === 1 &&
            password_verify($password, $user['password_hash'])
        ) {
            session_regenerate_id(true);

            $_SESSION['id_pengguna'] = (int) $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            header('Location: dashboard.php');
            exit;
        }

        $error = 'Username atau password salah, atau akun tidak aktif.';
    }
}

header_html('Login');
?>

<div class="form-card narrow">
    <h1>Login Pengguna</h1>
    <p>Masuk untuk mengelola informasi dosen.</p>

    <?php if ($error !== ''): ?>
        <div class="alert">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <input
            type="hidden"
            name="csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            required
            autocomplete="username"
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
        >

        <button class="btn" type="submit">Login</button>
    </form>

    <p class="muted">
        Masuk menggunakan akun admin yang sudah terdaftar.
    </p>
</div>

<?php footer_html(); ?>

