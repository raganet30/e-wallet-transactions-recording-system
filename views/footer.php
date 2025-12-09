<!-- Scripts -->
<script src="../assets/js/jquery-3.7.0.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap5.min.js"></script>
<script src="../assets/js/script.js"></script>



<footer class="footer" id="footer">
    <div class="footer-content">
        <div class="footer-copyright">
            © 2025 E-Wallet Transaction Recording System v1.0 | All rights reserved.
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Support</a>
        </div>
    </div>
</footer>

<script>
    const footer = document.getElementById('footer');
    const hamburgerBtn = document.getElementById('hamburgerBtn');

    hamburgerBtn.addEventListener('click', () => {
        footer.classList.toggle('collapsed');
    });

</script>