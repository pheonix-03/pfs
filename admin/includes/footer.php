        </main>
    </div>

    <!-- Core Scripts -->
    <script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        });
    }, 5000);
    </script>
</body>
</html>
