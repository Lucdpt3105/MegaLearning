@echo off
echo ========================================
echo  MEGALEARNING - CREATE USERS
echo ========================================
echo.

echo Creating default users...
echo.

php artisan tinker --execute="use App\Models\User; use Illuminate\Support\Facades\Hash; use Spatie\Permission\Models\Role; if (!User::where('email', 'admin@megalearning.com')->exists()) { $admin = User::create(['name' => 'Admin User', 'email' => 'admin@megalearning.com', 'password' => Hash::make('password')]); if (Role::where('name', 'admin')->exists()) { $admin->assignRole('admin'); } echo 'Admin created!'; } else { echo 'Admin already exists!'; }"

echo.

php artisan tinker --execute="use App\Models\User; use Illuminate\Support\Facades\Hash; use Spatie\Permission\Models\Role; if (!User::where('email', 'teacher@megalearning.com')->exists()) { $teacher = User::create(['name' => 'Teacher User', 'email' => 'teacher@megalearning.com', 'password' => Hash::make('password')]); if (Role::where('name', 'teacher')->exists()) { $teacher->assignRole('teacher'); } echo 'Teacher created!'; } else { echo 'Teacher already exists!'; }"

echo.

php artisan tinker --execute="use App\Models\User; use Illuminate\Support\Facades\Hash; use Spatie\Permission\Models\Role; if (!User::where('email', 'student@megalearning.com')->exists()) { $student = User::create(['name' => 'Student User', 'email' => 'student@megalearning.com', 'password' => Hash::make('password')]); if (Role::where('name', 'student')->exists()) { $student->assignRole('student'); } echo 'Student created!'; } else { echo 'Student already exists!'; }"

echo.
echo ========================================
echo  DONE! Login credentials:
echo ========================================
echo.
echo ADMIN:
echo   Email: admin@megalearning.com
echo   Pass:  password
echo.
echo TEACHER:
echo   Email: teacher@megalearning.com
echo   Pass:  password
echo.
echo STUDENT:
echo   Email: student@megalearning.com
echo   Pass:  password
echo.
echo Login URL: http://127.0.0.1:8000/login
echo.
pause
