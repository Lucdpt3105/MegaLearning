@echo off
echo ========================================
echo   TEST PASSWORD RESET FEATURE
echo ========================================
echo.

echo Step 1: Forgot Password Page
echo URL: http://localhost:8000/forgot-password
echo.

echo Step 2: Nhap email de nhan reset link
echo Example: admin@megalearning.com
echo.

echo Step 3: Sau khi submit, kiem tra database:
php artisan tinker --execute="DB::table('password_reset_tokens')->latest('created_at')->first()"
echo.

echo Step 4: Copy token va vao URL:
echo http://localhost:8000/reset-password/{TOKEN}?email={EMAIL}
echo.

echo Step 5: Nhap mat khau moi va submit
echo.

echo ========================================
echo   HOAC RESET TRUC TIEP QUA TINKER
echo ========================================
echo.

echo Chay lenh:
echo php artisan tinker
echo.
echo Sau do go:
echo $user = App\Models\User::where('email', 'student@megalearning.com')->first();
echo $user->password = Hash::make('12345678');
echo $user->save();
echo.

pause
