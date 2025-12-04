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

// Optional: Add active state to menu items when clicked
const menuItems = document.querySelectorAll('.menu-item');
menuItems.forEach(item => {
    item.addEventListener('click', function (e) {
       
        // Remove active class from all items
        menuItems.forEach(i => i.classList.remove('active'));

        // Add active class to clicked item
        this.classList.add('active');
    });
});


// Update footer position when sidebar toggles
document.getElementById('hamburgerBtn').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const footer = document.getElementById('footer');

    // Toggle collapsed class on footer
    if (sidebar.classList.contains('collapsed')) {
        footer.classList.add('collapsed');
    } else {
        footer.classList.remove('collapsed');
    }
});


// Function to set active menu item based on current page
function setActiveMenuItem() {
    // Get current page filename
    const currentPage = window.location.pathname.split("/").pop();
    
    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => item.classList.remove('active'));
    
    // Add active class to matching menu item
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        }
    });
}

// Call function when page loads
document.addEventListener('DOMContentLoaded', setActiveMenuItem);

// Also call when clicking menu items (for single-page behavior)
menuItems.forEach(item => {
    item.addEventListener('click', function() {
        menuItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});




