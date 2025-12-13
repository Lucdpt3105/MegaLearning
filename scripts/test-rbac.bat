@echo off
REM Quick test for role-based access control
echo.
echo ============================================
echo   TESTING ROLE-BASED ACCESS CONTROL
echo ============================================
echo.

php scripts/test-role-based-access.php

echo.
echo ============================================
echo   MANUAL TESTING
echo ============================================
echo.
echo Open browser and test login at:
echo http://localhost:8000/login
echo.
echo Test Accounts:
echo.
echo [ADMIN]
echo   Email: admin@megalearning.com
echo   Password: password
echo   Expected: Redirect to /admin
echo.
echo [TEACHER]
echo   Email: teacher@megalearning.com
echo   Password: password
echo   Expected: Redirect to /teacher/dashboard
echo.
echo [STUDENT]
echo   Email: student@megalearning.com
echo   Password: password
echo   Expected: Redirect to /student/dashboard
echo.
pause
