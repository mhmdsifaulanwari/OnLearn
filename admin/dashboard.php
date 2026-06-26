<?php
require_once '../config/database.php';
require_once '../function/helper.php';

/** @var mysqli $conn */

requireAdminLogin();

$adminUsername = $_SESSION['adminUsername'];

$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users")
)['total'];

$totalMateri = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM materi")
)['total'];

$totalQuiz = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(DISTINCT materiId) as total
        FROM quiz
    ")
)['total'];

$totalDiskusi = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM diskusi")
)['total'];

$totalBug = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_bug")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - OnLearn</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <!--  -->
</head>

<body>

    <?php include '../templates/admin_sidebar.php'; ?>

    <main class="admin-content">

        <div class="content-header">
            <h1>Dashboard</h1>
            <p>Selamat datang, <?= htmlspecialchars($adminUsername) ?></p>
        </div>

        <div class="dashboard-chart-grid">

            <div class="chart-card">
                <h3>Statistik Platform</h3>

                <div class="chart-wrapper">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Ringkasan Data</h3>

                <div class="mini-stats">

                    <div class="mini-stat">
                        <span>
                            <img
                                src="/finalProject/assets/icon/user.png"
                                alt="user">
                        </span>
                        <div>
                            <h4 class="counter" data-target="<?= $totalUsers ?>">0</h4>
                            <p>User</p>
                        </div>
                    </div>

                    <div class="mini-stat">
                        <span><img
                                src="/finalProject/assets/icon/book.png"
                                alt="book"></span>
                        <div>
                            <h4 class="counter" data-target="<?= $totalMateri ?>">0</h4>
                            <p>Materi</p>
                        </div>
                    </div>

                    <div class="mini-stat">
                        <span><img
                                src="/finalProject/assets/icon/examination.png"
                                alt="quiz"></span>
                        <div>
                            <h4 class="counter" data-target="<?= $totalQuiz ?>">0</h4>
                            <p>Quiz</p>
                        </div>
                    </div>

                    <div class="mini-stat">
                        <span><img
                                src="/finalProject/assets/icon/chat.png"
                                alt="diskusi"></span>
                        <div>
                            <h4 class="counter" data-target="<?= $totalDiskusi ?>">0</h4>
                            <p>Diskusi</p>
                        </div>
                    </div>

                    <div class="mini-stat">
                        <span><img
                                src="/finalProject/assets/icon/bug.png"
                                alt="bug"></span>
                        <div>
                            <h4 class="counter" data-target="<?= $totalBug ?>">0</h4>
                            <p>Bug</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </main>
    <script>
        const ctx = document.getElementById('mainChart');

        Chart.register(ChartDataLabels);

        new Chart(ctx, {
            type: 'doughnut',

            data: {
                labels: [
                    'User',
                    'Materi',
                    'Quiz',
                    'Diskusi',
                    'Bug'
                ],

                datasets: [{
                    data: [
                        <?= $totalUsers ?>,
                        <?= $totalMateri ?>,
                        <?= $totalQuiz ?>,
                        <?= $totalDiskusi ?>,
                        <?= $totalBug ?>
                    ],

                    backgroundColor: [
                        '#2563eb',
                        '#3b82f6',
                        '#60a5fa',
                        '#93c5fd',
                        '#bfdbfe'
                    ],

                    borderWidth: 0
                }]
            },

            options: {

                responsive: true,

                animation: {
                    animateRotate: true,
                    animateScale: false,
                    duration: 2000
                },

                plugins: {

                    legend: {
                        position: 'bottom'
                    },

                    datalabels: {

                        color: '#ffffff',

                        font: {
                            weight: 'bold',
                            size: 16
                        },

                        formatter: (value) => {

                            return value > 0 ?
                                value :
                                '';

                        }
                    }
                }
            }
        });
    </script>

    <script>
        const counters = document.querySelectorAll('.counter');

        counters.forEach(counter => {

            const target = +counter.getAttribute('data-target');

            let current = 0;

            const increment = target / 80;

            const updateCounter = () => {

                current += increment;

                if (current < target) {

                    counter.innerText = Math.ceil(current);

                    requestAnimationFrame(updateCounter);

                } else {

                    counter.innerText = target;

                }
            };

            updateCounter();

        });
    </script>
</body>

</html>