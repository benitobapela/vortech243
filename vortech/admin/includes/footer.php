    </div><!-- Fin du .admin-container -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.admin-sidebar');
            const toggler = document.querySelector('.navbar-toggler');
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggler.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Highlight current page in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const filename = currentPath.split('/').pop();
            const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === filename) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 