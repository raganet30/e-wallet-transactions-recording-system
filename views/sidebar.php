<!-- Sidebar with Hamburger Button -->
<div class="sidebar position-fixed" id="sidebar">
    <!-- Hamburger Button -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <i class="bi bi-list"></i>
    </button>

    <h5 class="text-center mb-4">
        <i class="bi bi-building"></i> <span>Branch Panel</span>
    </h5>

    <a href="dashboard.php" class="menu-item active">
        <i class="bi bi-speedometer2"></i> <span class="menu-text">Dashboard</span>
    </a>

    <a href="transactions.php" class="menu-item">
        <i class="bi bi-cash-stack"></i> <span class="menu-text">Transactions</span>
    </a>

    <a href="e-wallets.php" class="menu-item">
        <i class="bi bi-wallet2"></i> <span class="menu-text">E-Wallet Accounts</span>
    </a>

    <a href="cash_on_hand.php" class="menu-item">
        <i class="bi bi-currency-dollar"></i> <span class="menu-text">Cash on Hand</span>
    </a>

    <!-- Audit Log -->
    <a href="audit_logs.php" class="menu-item">
        <i class="bi bi-clock-history"></i> <span class="menu-text">Audit Logs</span>
    </a>

    <a href="reports.php" class="menu-item">
        <i class="bi bi-journal-text"></i> <span class="menu-text">Reports</span>
    </a>

    <a href="settings.php" class="menu-item">
        <i class="bi bi-gear"></i> <span class="menu-text">Settings</span>
    </a>
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
</script>