
<?php
require_once 'functions.php';
header_html('Beranda');
?>

<style>
/* Bagian utama */
.hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 25px;
    min-height: 370px;
    padding: 40px;
    border-radius: 24px;
    color: white;
    background: linear-gradient(120deg, #173c83, #2864c5);
    overflow: hidden;
}

.hero .hero-text {
    position: relative;
    z-index: 2;
}

.hero .eyebrow {
    display: inline-block;
    margin-bottom: 15px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
}

.hero h1 {
    margin: 8px 0 18px;
    font-size: clamp(30px, 4vw, 48px);
    line-height: 1.15;
    color: white;
}

.hero p {
    line-height: 1.7;
    font-size: 16px;
}

.hero .actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 25px;
}

/* Ilustrasi dosen dan mahasiswa */
.kampus-animasi {
    min-height: 280px;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 12px;
    position: relative;
    padding: 20px 10px;
    border-radius: 22px;
    overflow: hidden;
    background: linear-gradient(180deg, #8ecbff, #e4f4ff);
}

/* Dekorasi gedung */
.kampus-animasi .gedung {
    position: absolute;
    top: 20px;
    left: 12%;
    right: 12%;
    height: 135px;
    border: 5px solid #ffffff;
    border-radius: 12px 12px 0 0;
    background:
        repeating-linear-gradient(
            90deg,
            transparent 0 32px,
            #ffffff 33px 39px
        ),
        repeating-linear-gradient(
            0deg,
            #78b6ed 0 30px,
            #ffffff 31px 36px
        );
    opacity: 0.9;
}

.kampus-animasi .gedung::before {
    content: "UM BENGKULU";
    position: absolute;
    top: -3px;
    left: 15%;
    right: 15%;
    padding: 7px 3px;
    text-align: center;
    color: white;
    font-size: 11px;
    font-weight: bold;
    background: #17458f;
    border-radius: 5px;
}

/* Karakter */
.karakter {
    position: relative;
    z-index: 1;
    width: 30%;
    max-width: 115px;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    text-align: center;
    color: #173c83;
    font-size: 12px;
    font-weight: 700;
    animation: melayang 3s ease-in-out infinite;
}

.karakter .kepala {
    width: 68px;
    height: 68px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: -3px;
    border-radius: 50%;
    background: #ffe0bd;
    font-size: 43px;
    box-shadow: 0 4px 10px #173c8320;
}

.karakter .badan {
    width: 85px;
    height: 65px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 24px 24px 8px 8px;
    background: #ffffff;
    font-size: 27px;
}

.karakter.dosen .badan {
    background: #173c83;
}

.karakter.mahasiswa .badan {
    background: #159b83;
}

.karakter.kedua {
    animation-delay: 0.7s;
}

.karakter.ketiga {
    animation-delay: 1.3s;
}

.buku-animasi {
    position: absolute;
    z-index: 2;
    right: 12px;
    bottom: 12px;
    font-size: 30px;
    animation: putar 4s ease-in-out infinite;
}

@keyframes melayang {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes putar {
    0%, 100% { transform: rotate(-8deg); }
    50% { transform: rotate(8deg); }
}

/* Kartu informasi */
.grid3 {
    margin-top: 25px;
}

.grid3 .card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.grid3 .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px #173c8318;
}

/* Tampilan HP */
@media (max-width: 760px) {
    .hero {
        grid-template-columns: 1fr;
        padding: 25px 20px;
        gap: 20px;
    }

    .kampus-animasi {
        min-height: 245px;
        gap: 4px;
    }

    .karakter {
        transform: scale(0.9);
    }

    .hero h1 {
        font-size: 32px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .karakter,
    .buku-animasi {
        animation: none;
    }
}
</style>

<section class="hero">
    <div class="hero-text">
        <span class="eyebrow">
            UNIVERSITAS MUHAMMADIYAH BENGKULU
        </span>

        <h1>
            Sistem Informasi Manajemen Dosen
        </h1>

        <p>
            Pengelolaan data dosen, informasi akademik,
            dan layanan data melalui Web Service/API
            dalam satu aplikasi.
        </p>

        <div class="actions">
            <a class="btn" href="login.php">
                Masuk ke Aplikasi
            </a>

            <a class="btn secondary" href="api-docs.php">
                Dokumentasi API
            </a>
        </div>
    </div>

    <div class="kampus-animasi">
        <div class="gedung"></div>

        <div class="karakter dosen">
            <div class="kepala">👨‍🏫</div>
            <div class="badan">📋</div>
            <span>Dosen</span>
        </div>

        <div class="karakter mahasiswa kedua">
            <div class="kepala">👩‍🎓</div>
            <div class="badan">💻</div>
            <span>Mahasiswa</span>
        </div>

        <div class="karakter mahasiswa ketiga">
            <div class="kepala">🧑‍🎓</div>
            <div class="badan">📚</div>
            <span>Mahasiswa</span>
        </div>

        <div class="buku-animasi">📚</div>
    </div>
</section>

<section class="grid3">
    <article class="card">
        <h3>🗄️ Data Terpusat</h3>
        <p>
            Data dosen tersimpan pada basis data
            relasional dan dapat dikelola dengan mudah.
        </p>
    </article>

    <article class="card">
        <h3>🛡️ Kontrol Akses</h3>
        <p>
            Menu dan operasi dibatasi berdasarkan
            role pengguna untuk menjaga keamanan.
        </p>
    </article>

    <article class="card">
        <h3>🌐 Web Service</h3>
        <p>
            Endpoint API menyediakan respons JSON
            untuk mendukung integrasi data.
        </p>
    </article>
</section>

<?php footer_html(); ?>
