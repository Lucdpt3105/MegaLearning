@echo off
echo ========================================
echo Testing MegaLearning Backend API
echo ========================================
echo.

REM Check if backend is running
echo [1/4] Checking backend health...
curl -s http://localhost:8080/api/health
echo.
echo.

REM Test API documentation
echo [2/4] Checking API documentation...
curl -s http://localhost:8080/api
echo.
echo.

REM Test CORS
echo [3/4] Testing CORS...
curl -i -X OPTIONS http://localhost:8080/api/subjects ^
  -H "Origin: http://localhost:5173" ^
  -H "Access-Control-Request-Method: GET"
echo.
echo.

echo [4/4] All basic tests completed!
echo.
echo To test authenticated endpoints:
echo 1. Register/Login via POST /api/auth/login
echo 2. Copy the token from response
echo 3. Use: curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8080/api/subjects
echo.
pause
