/**
 * Prime Fleet Services - Theme Engine (Light / Dark Mode)
 * - FOUC-free switching
 * - Integrated Header Top Bar Pill & Mobile Switch
 * - Persistent choice in localStorage (syncs with both keys)
 * - Listens to OS prefers-color-scheme changes
 */
(function() {
    'use strict';

    var STORAGE_KEY = 'pfs-theme';

    function getPreferredTheme() {
        var saved = localStorage.getItem(STORAGE_KEY) || localStorage.getItem('pfs_theme');
        if (saved === 'light' || saved === 'dark') {
            return saved;
        }
        return 'dark'; // Dark mode is default
    }

    function applyTheme(theme, persist) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'light') {
            document.documentElement.classList.add('theme-style--light');
            if (document.body) {
                document.body.classList.add('theme-style--light');
            }
        } else {
            document.documentElement.classList.remove('theme-style--light');
            if (document.body) {
                document.body.classList.remove('theme-style--light');
            }
        }
        var metaScheme = document.querySelector('meta[name="color-scheme"]');
        if (metaScheme) {
            metaScheme.content = theme;
        }

        if (persist) {
            try {
                localStorage.setItem(STORAGE_KEY, theme);
                localStorage.setItem('pfs_theme', theme);
            } catch (e) {
                console.warn('Unable to persist theme to localStorage', e);
            }
        }

        // Update all toggle button attributes, active classes & labels
        var toggles = document.querySelectorAll('.pfs-theme-pill-toggle, .pfs-theme-toggle, #theme-toggle');
        var nextThemeText = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
        var labelText = theme === 'dark' ? 'Dark' : 'Light';
        toggles.forEach(function(btn) {
            btn.setAttribute('aria-label', nextThemeText);
            btn.setAttribute('title', nextThemeText);
            var labelSpan = btn.querySelector('.theme-label-text, .theme-label');
            if (labelSpan) {
                labelSpan.textContent = labelText;
            }
            if (theme === 'dark') {
                btn.classList.add('is-dark');
                btn.classList.remove('is-light');
            } else {
                btn.classList.add('is-light');
                btn.classList.remove('is-dark');
            }
        });
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme') || 'dark';
        var next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next, true);
        return next;
    }

    // Expose globally to window so other scripts can safely call it without conflicts
    window.pfsToggleTheme = toggleTheme;
    window.pfsApplyTheme = applyTheme;

    // Initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        var currentTheme = document.documentElement.getAttribute('data-theme') || getPreferredTheme();
        applyTheme(currentTheme, false);

        // Bind clicks to all toggle buttons (header top bar pill, mobile menu, aside drawer)
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.pfs-theme-pill-toggle, .pfs-theme-toggle, #theme-toggle, #mode_switcher');
            if (btn) {
                if (e._pfsThemeHandled) return;
                e._pfsThemeHandled = true;
                e.preventDefault();
                toggleTheme();
            }
        });

        // Listen for OS scheme change if user hasn't explicitly set a preference
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem(STORAGE_KEY) && !localStorage.getItem('pfs_theme')) {
                    applyTheme(e.matches ? 'dark' : 'light', false);
                }
            });
        }
        // Scroll Progress Bar Tracker
        var progressBar = document.getElementById('scroll-progress-bar');
        if (progressBar) {
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                var docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                progressBar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
            }, { passive: true });
        }

        // UTM Parameter Preservation
        try {
            var params = new URLSearchParams(window.location.search);
            ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(function(utm) {
                if (params.has(utm)) {
                    sessionStorage.setItem(utm, params.get(utm));
                }
            });
            // Inject UTM into forms if hidden fields or inquiry forms exist
            document.querySelectorAll('form').forEach(function(form) {
                ['utm_source', 'utm_medium', 'utm_campaign'].forEach(function(utm) {
                    var val = sessionStorage.getItem(utm);
                    if (val && !form.querySelector('input[name="' + utm + '"]')) {
                        var hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = utm;
                        hidden.value = val;
                        form.appendChild(hidden);
                    }
                });
            });
        } catch(e) {}

        // Global Copy Button Handler
        document.addEventListener('click', function(e) {
            var copyBtn = e.target.closest('.pfs-copy-btn');
            if (copyBtn) {
                e.preventDefault();
                var textToCopy = copyBtn.getAttribute('data-copy') || copyBtn.innerText;
                navigator.clipboard.writeText(textToCopy).then(function() {
                    var originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
                    setTimeout(function() {
                        copyBtn.innerHTML = originalText;
                    }, 2000);
                });
            }
        });

        // Global Password Visibility Toggle Handler
        document.addEventListener('click', function(e) {
            var pwBtn = e.target.closest('.pw-toggle-btn');
            if (pwBtn) {
                e.preventDefault();
                var targetId = pwBtn.getAttribute('data-target');
                var input = targetId ? document.getElementById(targetId) : pwBtn.parentElement.querySelector('input[type="password"], input[type="text"]');
                if (input) {
                    var isPw = input.type === 'password';
                    input.type = isPw ? 'text' : 'password';
                    var icon = pwBtn.querySelector('i');
                    if (icon) {
                        icon.className = isPw ? 'bi bi-eye-slash' : 'bi bi-eye';
                    }
                }
            }
        });
    });
})();
