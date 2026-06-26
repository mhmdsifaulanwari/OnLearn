<?php
require_once __DIR__ . '/../function/helper.php';

if (!isAdminLoggedIn()) {
    redirect('/finalProject/auth/login.php');
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">

            <div class="sidebar-toggle" onclick="toggleSidebar()">
                ☰
            </div>

            <div class="sidebar-brand">
                OnLearn
            </div>

        </div>

        <nav class="sidebar-menu">

            <a href="/finalProject/admin/dashboard.php"
                class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/home.png" alt="dashboard"></span>

                <span class="menu-text">
                    Dashboard
                </span>

            </a>

            <a href="/finalProject/admin/users/index.php"
                class="<?= strpos($_SERVER['PHP_SELF'], '/users/') !== false ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/user-settings.png" alt="kelola user"></span>

                <span class="menu-text">
                    Kelola User
                </span>

            </a>

            <a href="/finalProject/admin/materi/index.php"
                class="<?= strpos($_SERVER['PHP_SELF'], '/materi/') !== false ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/literature.png" alt="kelola materi"></span>

                <span class="menu-text">
                    Kelola Materi
                </span>

            </a>

            <a href="/finalProject/admin/quiz/index.php"
                class="<?= strpos($_SERVER['PHP_SELF'], '/quiz/') !== false ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/document.png" alt="kelola quiz"></span>

                <span class="menu-text">
                    Kelola Quiz
                </span>

            </a>

            <a href="/finalProject/admin/diskusi/index.php"
                class="<?= strpos($_SERVER['PHP_SELF'], '/diskusi/') !== false ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/chat-modern.png" alt="kelola diskusi"></span>

                <span class="menu-text">
                    Kelola Diskusi
                </span>

            </a>

            <a href="/finalProject/admin/laporan_bug/index.php"
                class="<?= strpos($_SERVER['PHP_SELF'], '/laporan_bug/') !== false ? 'active' : '' ?>">

                <span class="menu-icon"><img src="/finalProject/assets/icon/malware.png" alt="laporan bug"></span>

                <span class="menu-text">
                    Laporan Bug
                </span>

            </a>

        </nav>

        <div class="sidebar-footer">

            <a href="/finalProject/auth/logout.php" class="logout-btn">

                <span class="menu-icon"><img src="/finalProject/assets/icon/power.png" alt="logout"></span>

                <span class="menu-text">
                    Logout
                </span>

            </a>

        </div>
    </aside>

</div>

<script>
    const sidebar = document.querySelector('.admin-sidebar');

    /* LOAD */
    if (localStorage.getItem('sidebarCollapsed') === 'true') {

        sidebar.classList.add('collapsed');

        document.body.classList.add('sidebar-collapsed');
    }

    /* TOGGLE */
    function toggleSidebar() {

        sidebar.classList.toggle('collapsed');

        document.body.classList.toggle('sidebar-collapsed');

        localStorage.setItem(
            'sidebarCollapsed',
            sidebar.classList.contains('collapsed')
        );
    }
</script>