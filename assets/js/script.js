// Hamburger button toggle functionality
document.getElementById('hamburgerBtn').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const navbar = document.getElementById('navbar');
    const footer = document.getElementById('footer');

    // Toggle collapsed class
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
    navbar.classList.toggle('collapsed');
    footer.classList.toggle('collapsed');

    // Change hamburger icon
    const hamburgerIcon = this.querySelector('i');
    if (sidebar.classList.contains('collapsed')) {
        hamburgerIcon.classList.remove('bi-list');
        hamburgerIcon.classList.add('bi-chevron-right');
    } else {
        hamburgerIcon.classList.remove('bi-chevron-right');
        hamburgerIcon.classList.add('bi-list');
    }
});



// Add active state on click
// sidebarMenuItems.forEach(item => {
//     item.addEventListener('click', function () {
//         sidebarMenuItems.forEach(i => i.classList.remove('active'));
//         this.classList.add('active');
//     });
// });

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
