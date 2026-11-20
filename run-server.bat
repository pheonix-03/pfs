@echo off
title Prime Fleet Services - Local Development Server
echo ========================================================
echo       Prime Fleet Services - PHP Server Launcher
echo ========================================================
echo.
echo Starting web server on http://localhost:8000 ...
echo Main Website: http://localhost:8000/index.php
echo Admin CMS:    http://localhost:8000/admin/
echo Default Admin: admin / admin123
echo.
echo Press Ctrl+C anytime to stop the server.
echo.

if exist .\.php\php.exe (
    .\.php\php.exe -S 127.0.0.1:8000
) else (
    php -S 127.0.0.1:8000
)
pause
