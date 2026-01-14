# Quick Fix: MySQL Driver Not Found

## Problem
```
could not find driver (Connection: mysql)
```

## Solution

### Step 1: Enable MySQL Extensions in php.ini

1. **Locate your php.ini file**:
   - Path: `C:\xampp\php\php.ini`

2. **Open php.ini in a text editor** (as Administrator)

3. **Find and uncomment these lines** (remove the semicolon `;` at the start):

   **Before:**
   ```ini
   ;extension=pdo_mysql
   ;extension=mysqli
   ```

   **After:**
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```

4. **Save the file**

### Step 2: Restart Apache

1. Open **XAMPP Control Panel**
2. Click **Stop** for Apache
3. Wait a few seconds
4. Click **Start** for Apache

### Step 3: Verify PHP Extensions

Run this command to verify the extensions are loaded:

```bash
php -m | findstr mysql
```

You should see:
```
mysqli
pdo_mysql
```

### Step 4: Run the Migration

```bash
cd C:\xampp\htdocs\HF
php artisan migrate
```

You should see:
```
Migration table created successfully.
Migrating: 2026_01_14_000000_add_office_in_charge_upline_fields
Migrated:  2026_01_14_000000_add_office_in_charge_upline_fields
```

## Alternative: Check if MySQL is Running

If the above doesn't work, make sure MySQL is running in XAMPP:

1. Open XAMPP Control Panel
2. Check if MySQL shows "Running"
3. If not, click **Start** for MySQL

## Still Having Issues?

### Check Database Connection in .env

Open `C:\xampp\htdocs\HF\.env` and verify:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hf_database
DB_USERNAME=root
DB_PASSWORD=
```

### Clear Laravel Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Test Database Connection

```bash
php artisan tinker
```

Then type:
```php
DB::connection()->getPdo();
```

If successful, you'll see PDO connection details. If it fails, you'll see the error.

Type `exit` to quit tinker.
