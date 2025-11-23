@echo off
echo =========================================
echo   TEST OPENAI API CONNECTION
echo =========================================
echo.

REM Get API key from .env
for /f "tokens=2 delims==" %%a in ('findstr "^OPENAI_API_KEY=" .env') do set APIKEY=%%a

if "%APIKEY%"=="" (
    echo [ERROR] Khong tim thay OPENAI_API_KEY trong .env
    echo.
    echo Hay chay: setup-openai.bat de setup API key
    pause
    exit /b 1
)

echo [INFO] Dang test connection voi OpenAI...
echo.

REM Create temporary PHP test script
echo ^<?php > temp_test_openai.php
echo require __DIR__ . '/vendor/autoload.php'; >> temp_test_openai.php
echo. >> temp_test_openai.php
echo use Illuminate\Support\Facades\Http; >> temp_test_openai.php
echo. >> temp_test_openai.php
echo $apiKey = env('OPENAI_API_KEY'); >> temp_test_openai.php
echo. >> temp_test_openai.php
echo if (empty($apiKey)) { >> temp_test_openai.php
echo     echo "[ERROR] API key is empty\n"; >> temp_test_openai.php
echo     exit(1); >> temp_test_openai.php
echo } >> temp_test_openai.php
echo. >> temp_test_openai.php
echo echo "[INFO] Testing OpenAI API...\n"; >> temp_test_openai.php
echo echo "[INFO] API Key: " . substr($apiKey, 0, 10) . "...\n\n"; >> temp_test_openai.php
echo. >> temp_test_openai.php
echo try { >> temp_test_openai.php
echo     $response = Http::withHeaders([ >> temp_test_openai.php
echo         'Authorization' =^> 'Bearer ' . $apiKey, >> temp_test_openai.php
echo         'Content-Type' =^> 'application/json', >> temp_test_openai.php
echo     ])-^>timeout(10)-^>post('https://api.openai.com/v1/chat/completions', [ >> temp_test_openai.php
echo         'model' =^> 'gpt-3.5-turbo', >> temp_test_openai.php
echo         'messages' =^> [ >> temp_test_openai.php
echo             ['role' =^> 'user', 'content' =^> 'Say hello in Vietnamese'] >> temp_test_openai.php
echo         ], >> temp_test_openai.php
echo         'max_tokens' =^> 50 >> temp_test_openai.php
echo     ]); >> temp_test_openai.php
echo. >> temp_test_openai.php
echo     if ($response-^>successful()) { >> temp_test_openai.php
echo         $data = $response-^>json(); >> temp_test_openai.php
echo         echo "[SUCCESS] OpenAI API is working!\n\n"; >> temp_test_openai.php
echo         echo "Response: " . $data['choices'][0]['message']['content'] . "\n"; >> temp_test_openai.php
echo         exit(0); >> temp_test_openai.php
echo     } else { >> temp_test_openai.php
echo         echo "[ERROR] API returned error\n"; >> temp_test_openai.php
echo         echo "Status: " . $response-^>status() . "\n"; >> temp_test_openai.php
echo         echo "Body: " . $response-^>body() . "\n"; >> temp_test_openai.php
echo         exit(1); >> temp_test_openai.php
echo     } >> temp_test_openai.php
echo } catch (Exception $e) { >> temp_test_openai.php
echo     echo "[ERROR] Exception: " . $e-^>getMessage() . "\n"; >> temp_test_openai.php
echo     exit(1); >> temp_test_openai.php
echo } >> temp_test_openai.php

REM Run the test
php artisan tinker --execute="require 'temp_test_openai.php';"

REM Cleanup
del temp_test_openai.php >nul 2>&1

echo.
echo =========================================
pause
