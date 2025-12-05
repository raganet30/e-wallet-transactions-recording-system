<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collapsible Sidebar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        /* style for e-wallet transaction recording system */
        body {
            background: #e8f1ff;
            transition: margin-left 0.3s ease;
        }
        .login-card {
            max-width: 380px;
            margin: 8% auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: #3f8cff;
            border-color: #3f8cff;
        }
        .btn-primary:hover {
            background-color: #1f6fe0;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background: #3f8cff;
            min-height: 100vh;
            padding-top: 20px;
            color: white;
            transition: all 0.3s ease;
            overflow-x: hidden;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }
        .sidebar .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            margin-bottom: 5px;
            white-space: nowrap;
        }
        .sidebar .menu-item:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar .menu-item i {
            font-size: 1.2rem;
            margin-right: 12px;
            min-width: 24px;
            text-align: center;
        }
        .sidebar.collapsed .menu-item i {
            margin-right: 0;
        }
        .sidebar .menu-text {
            transition: opacity 0.3s ease;
            opacity: 1;
        }
        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar h5 {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 20px 20px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            margin-top: 50px;
        }
        .sidebar.collapsed h5 {
            justify-content: center;
            padding: 0 10px 20px 10px;
        }
        .sidebar h5 span {
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        .sidebar.collapsed h5 span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        /* Hamburger button */
        .hamburger-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: transparent;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
            transition: all 0.3s ease;
        }
        .hamburger-btn:hover {
            color: rgba(255,255,255,0.8);
            transform: scale(1.1);
        }
        
        /* Content area */
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        .content.collapsed {
            margin-left: 70px;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .navbar.collapsed {
            margin-left: 70px;
        }
        
        /* Demo content */
        .demo-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
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

        <a href="wallets.php" class="menu-item">
            <i class="bi bi-wallet2"></i> <span class="menu-text">Wallet Accounts</span>
        </a>

        <a href="coh.php" class="menu-item">
            <i class="bi bi-currency-dollar"></i> <span class="menu-text">Cash on Hand</span>
        </a>

        <a href="reports.php" class="menu-item">
            <i class="bi bi-journal-text"></i> <span class="menu-text">Reports</span>
        </a>

        <a href="settings.php" class="menu-item">
            <i class="bi bi-gear"></i> <span class="menu-text">Settings</span>
        </a>
    </div>

    <!-- Main Content Area -->
    <nav class="navbar" id="navbar">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="bi bi-wallet2 me-2"></i>E-Wallet Transaction System
            </span>
            <div class="d-flex">
                <span class="badge bg-primary me-3">Branch: Main</span>
                <span class="badge bg-success">Status: Active</span>
            </div>
        </div>
    </nav>

    <div class="content" id="mainContent">
        <div class="container-fluid">
            <h1 class="mb-4">Dashboard</h1>
            
            <div class="demo-content">
                <h4><i class="bi bi-speedometer2 me-2"></i>Welcome to the Dashboard</h4>
                <p>This is your main content area. When you click the hamburger menu (☰) in the top-left corner of the sidebar, it will collapse to show only icons.</p>
                <p>Click it again to expand the full sidebar with text labels.</p>
                
                <div class="mt-4">
                    <h5>Features:</h5>
                    <ul>
                        <li>Hamburger button to toggle sidebar</li>
                        <li>Smooth animations</li>
                        <li>Icon-only mode when collapsed</li>
                        <li>Full text labels when expanded</li>
                        <li>Main content adjusts automatically</li>
                    </ul>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="demo-content">
                        <h5><i class="bi bi-cash-stack me-2"></i>Recent Transactions</h5>
                        <p>View your recent transaction history here.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="demo-content">
                        <h5><i class="bi bi-wallet2 me-2"></i>Wallet Balance</h5>
                        <p>Check your current wallet balances.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hamburger button toggle functionality
        document.getElementById('hamburgerBtn').addEventListener('click', function() {
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
        
        // Optional: Add active state to menu items when clicked
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Remove this if you want actual navigation
                
                // Remove active class from all items
                menuItems.forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>