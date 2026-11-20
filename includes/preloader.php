<!-- Preloader -->
<div id="preloader">
    <div class="preloader-inner">
        <div class="spinner">
            <img src="assets/img/preloader-logo.png" alt="Logo">
            <img style="margin-top: 15px;" src="assets/img/preloader-wheel.svg" alt="Loading..." class="wheel">
        </div>
        <div class="loading-text">
            <img src="assets/img/preloader-logo-text.png" alt="Prime Fleet Services">
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        var p = document.getElementById('preloader');
        if (p && !p.classList.contains('hide')) {
            p.classList.add('hide');
        }
    }, 1000);
</script>
