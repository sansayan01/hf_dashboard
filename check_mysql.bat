@echo off
echo ========================================
echo MySQL Service Status Checker
echo ========================================
echo.

echo Checking if MySQL is running...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL is running
) else (
    echo [ERROR] MySQL is NOT running!
    echo.
    echo Please start MySQL from XAMPP Control Panel
    pause
    exit /b 1
)

echo.
echo Checking MySQL connection...
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SELECT 1;" 2>NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL connection successful
) else (
    echo [ERROR] Cannot connect to MySQL
    echo.
    echo Please check:
    echo 1. MySQL is started in XAMPP Control Panel
    echo 2. Port 3306 is not blocked
    echo 3. MySQL configuration is correct
)

echo.
echo ========================================
echo Checking for locked tables...
echo ========================================
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SHOW OPEN TABLES WHERE In_use > 0;" 2>NUL

echo.
pause
