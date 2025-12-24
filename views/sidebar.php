<?php
require '../config/helpers.php';

?>
<!-- Sidebar with Hamburger Button -->
<div class="sidebar position-fixed" id="sidebar">
    <!-- Hamburger Button -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <i class="bi bi-list"></i>
    </button>

    <h5 class="text-center mb-4">
        <i class="bi bi-building"></i> <span>
            <?php
            // show branch name
            if ($_SESSION['role'] === 'super_admin') {
                echo "SUPER ADMIN PANEL";
            } else {
                echo htmlspecialchars(currentBranchName());

            }
            ?>
        </span>
    </h5>

    <!-- Dashboard -->
    <a href="<?= (currentRole() === 'super_admin') ? 'super_admin_dashboard' : 'dashboard'; ?>" class="menu-item">
        <i class="bi bi-speedometer2"></i>
        <span class="menu-text">Dashboard</span>
    </a>

    <a href="transactions" class="menu-item">
        <i class="bi bi-card-checklist"></i> <span class="menu-text">Transactions</span>
    </a>

    <a href="e-wallets" class="menu-item">
        <i class="bi bi-wallet-fill"></i> <span class="menu-text">E-Wallet Accounts</span>
    </a>
    <?php if (currentRole() !== 'super_admin'): ?>
        <a href="cash_on_hand" class="menu-item">
            <i class="bi bi-cash-stack"></i> <span class="menu-text">Cash on Hand</span>
        </a>
    <?php endif; ?>
    <!-- Logs with Submenu -->
    <div class="menu-item has-submenu">
        <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
            <i class="bi bi-clock-history"></i>
            <span class="menu-text">Logs</span>
            <i class="bi bi-chevron-down ms-auto submenu-icon"></i>
        </a>

        <div class="submenu">
            <?php if (currentRole() === 'super_admin' || currentRole() === 'admin'): ?>
                <a href="audit_logs" class="submenu-item">
                    <i class="bi bi-journal-text"></i>
                    <span>Audit Logs</span>
                </a>
            <?php endif; ?>

            <a href="login_logs" class="submenu-item">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login Logs</span>
            </a>
        </div>
    </div>


    <a href="reports" class="menu-item">
        <i class="bi bi-journal-text"></i> <span class="menu-text">Reports</span>
    </a>


    <!-- show only if user is super_admin -->
    <?php if (currentRole() === 'super_admin'): ?>

        <!-- Branches -->
        <a href="branches" class="menu-item">
            <i class="bi bi-building"></i> <span class="menu-text">Branches</span>
        </a>

        <!-- Users -->
        <a href="users" class="menu-item">
            <i class="bi bi-people"></i> <span class="menu-text">Users</span>
        </a>
    <?php endif; ?>

    <!-- <a href="settings" class="menu-item">
        <i class="bi bi-gear"></i> <span class="menu-text">Settings</span>
    </a> -->
</div>

<script>
    // Hamburger button toggle functionality
    document.getElementById('hamburgerBtn').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('mainContent');
        const navbar = document.getElementById('navbar');

        // Toggle collapsed class on all elements
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
        navbar.classList.toggle('collapsed');

        // Change hamburger icon when collapsed
        const hamburgerIcon = this.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            hamburgerIcon.classList.remove('bi-list');
            hamburgerIcon.classList.add('bi-chevron-right');
        } else {
            hamburgerIcon.classList.remove('bi-chevron-right');
            hamburgerIcon.classList.add('bi-list');
        }
    });

    // Function to set active menu item based on current page
    function setActiveMenuItem() {
        const currentPage = window.location.pathname.split("/").pop();
        const sidebarMenuItems = document.querySelectorAll('.menu-item');

        sidebarMenuItems.forEach(item => {
            item.classList.remove('active');
            const href = item.getAttribute('href');
            if (href === currentPage) {
                item.classList.add('active');
            }
        });
    }

    // Run when page loads
    document.addEventListener('DOMContentLoaded', setActiveMenuItem);


    // submenu toggle
    function toggleSubmenu(el) {
        const parent = el.closest('.has-submenu');
        parent.classList.toggle('open');
    }

    // Extend active menu detection for submenu items
    function setActiveMenuItem() {
        const currentPage = window.location.pathname.split("/").pop();

        document.querySelectorAll('.menu-item, .submenu-item').forEach(item => {
            item.classList.remove('active');

            const href = item.getAttribute('href');
            if (href === currentPage) {
                item.classList.add('active');

                // Auto-open submenu if child is active
                const submenu = item.closest('.has-submenu');
                if (submenu) submenu.classList.add('open');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', setActiveMenuItem);
</script>