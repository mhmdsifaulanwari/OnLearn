<?php
require_once 'config/database.php';
require_once 'function/helper.php';

$isLoggedIn = isUserLoggedIn();
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnLearn - Platform Pembelajaran Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body id="home">

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">OnLearn</div>

        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#jenjang">Materi</a></li>

            <?php if ($isLoggedIn): ?>
                <li><a href="diskusi/index.php">Diskusi</a></li>
                <li><a href="lapor_bug/index.php">Lapor Bug</a></li>
            <?php else: ?>
                <li><a href="auth/login.php">Diskusi</a></li>
                <li><a href="auth/login.php">Lapor Bug</a></li>
            <?php endif; ?>
        </ul>

        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <span class="username">Halo, <?= htmlspecialchars($username) ?></span>
                <a href="auth/logout.php" class="btn-outline">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="btn-outline">Login</a>
                <a href="auth/register.php" class="btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>Belajar Online Jadi Lebih Mudah Bersama <span>OnLearn</span></h1>
            <p>
                Platform pembelajaran online gratis untuk siswa SD, SMP, dan SMA/SMK
                dengan materi, quiz interaktif, diskusi, dan fitur pelaporan bug.
            </p>

            <div class="hero-buttons">
                <a href="#jenjang" class="btn-primary">Mulai Belajar</a>
                <a href="#jenjang" class="btn-outline">Lihat Materi</a>
            </div>
        </div>

        <div class="hero-image">
            <img
                src="assets/icon/lesson.png"
                alt="Book">
        </div>
    </section>

    <!-- FITUR -->
    <section class="features">
        <h2>Fitur Unggulan OnLearn</h2>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="icon">
                    <span class="material-symbols-outlined">
                        menu_book
                    </span>
                </div>
                <h3>Materi Lengkap</h3>
                <p>Materi pembelajaran sesuai jenjang pendidikan yang mudah dipahami.</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <span class="material-symbols-outlined">
                        quiz
                    </span>
                </div>
                <h3>Quiz Interaktif</h3>
                <p>Latihan soal untuk mengukur pemahaman belajar secara langsung.</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <span class="material-symbols-outlined">
                        forum
                    </span>
                </div>
                <h3>Diskusi</h3>
                <p>Berdiskusi dengan pengguna lain untuk memperdalam pemahaman.</p>
            </div>

            <div class="feature-card">
                <div class="icon">
                    <span class="material-symbols-outlined">
                        bug_report
                    </span>
                </div>
                <h3>Lapor Bug</h3>
                <p>Laporkan kendala atau error untuk meningkatkan kualitas platform.</p>
            </div>
        </div>
    </section>

    <!-- JENJANG -->
    <section class="jenjang" id="jenjang">
        <h2>Pilih Jenjang Pendidikan</h2>

        <div class="jenjang-grid">

            <a href="materi/index.php?jenjang=SD" class="jenjang-card">

                <div class="jenjang-icon">
                    <span class="material-symbols-outlined">
                        school
                    </span>
                </div>

                <h3>SD</h3>
                <p>Kelas 1 - 6</p>
            </a>

            <a href="materi/index.php?jenjang=SMP" class="jenjang-card">

                <div class="jenjang-icon">
                    <span class="material-symbols-outlined">
                        auto_stories
                    </span>
                </div>

                <h3>SMP</h3>
                <p>Kelas 7 - 9</p>
            </a>

            <a href="materi/index.php?jenjang=SMA/SMK" class="jenjang-card">

                <div class="jenjang-icon">
                    <span class="material-symbols-outlined">
                        workspace_premium
                    </span>
                </div>

                <h3>SMA / SMK</h3>
                <p>Kelas 10 - 12</p>
            </a>

        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Mulai Perjalanan Belajarmu Sekarang</h2>
        <p>Belajar gratis, interaktif, dan mudah diakses kapan saja.</p>

        <?php if (!$isLoggedIn): ?>
            <a href="auth/register.php" class="btn-primary">Daftar Sekarang</a>
        <?php endif; ?>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 OnLearn. All Rights Reserved.</p>
    </footer>

</body>

</html>