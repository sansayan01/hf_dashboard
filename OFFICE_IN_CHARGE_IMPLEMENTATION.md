# Office In-Charge Upline-Based Access Control Implementation

## Overview
This implementation allows Super Admin to create Office In-Charge accounts that are tied to a specific person (upline) and have access to all the permissions and downline of that upline, except they cannot delete their upline.

## What Has Been Implemented

### 1. Database Migration
**File**: `database/migrations/2026_01_14_000000_add_office_in_charge_upline_fields.php`

Added two new fields to the `users` table:
- `upline_id`: Foreign key to track which user this Office In-Charge represents
- `upline_designation`: Stores the designation of the upline (super_admin, hs, dm, bm, rm)

### 2. User Model Updates
**File**: `app/Models/User.php`

**Changes Made**:
- Added `upline_id` and `upline_designation` to fillable fields
- Added `upline()` relationship method
- Updated `getAllDownline()` method:
  - Office In-Charge with upline now sees the same downline as their upline
  - This gives them access to the exact same tree structure
- Updated `canAccess()` method:
  - Office In-Charge cannot access their upline (prevents deletion)
  - Office In-Charge cannot access Super Admin
  - Office In-Charge can access everyone else in their upline's tree

### 3. UserController Updates
**File**: `app/Http/Controllers/UserController.php`

**create() method**:
- Added `$potentialUplines` variable for Super Admin
- Passes potential uplines grouped by designation to the view

**store() method**:
- Added validation rules for `upline_designation` and `upline_id` (required when creating Office In-Charge)
- Added logic to save upline information when creating Office In-Charge
- Validates that the selected upline matches the upline designation

### 4. Create User View Updates
**File**: `resources/views/users/create.blade.php`

**UI Changes**:
- Added "Office In-Charge Upline Selection" section (only visible to Super Admin)
- Shows when "Office In-Charge" designation is selected
- Two new dropdowns:
  1. **Upline's Designation**: Select from Super Admin, HS, DM, BM, RM
  2. **Select Upline Person**: Populated based on selected designation

**JavaScript Changes**:
- Added dynamic show/hide logic for upline section
- Populates upline person dropdown based on selected upline designation
- Makes fields required when Office In-Charge is selected

## How It Works

### Example Scenario (As You Described)
1. **Super Admin (Sayan)** wants to create an Office In-Charge for **Amit (DM)**
2. During account creation:
   - Designation: Office In-Charge
   - Upline's Designation: DM
   - Upline Person: Amit
3. The created Office In-Charge will:
   - ✅ See all of Amit's downline (BMs, RMs, ROs under Amit)
   - ✅ Have all permissions that Amit has
   - ✅ Can manage, approve, edit users in Amit's tree
   - ❌ **Cannot delete Amit** (their upline)
   - ❌ Cannot access Super Admin

## Database Setup Required

### Issue
The migration failed because the MySQL PDO driver is not enabled in your PHP installation.

### Solution
1. **Enable MySQL Extension in php.ini**:
   - Open `C:\xampp\php\php.ini`
   - Find the line `;extension=pdo_mysql`
   - Remove the semicolon: `extension=pdo_mysql`
   - Find the line `;extension=mysqli`
   - Remove the semicolon: `extension=mysqli`
   - Save the file

2. **Restart Apache**:
   - Stop and start Apache in XAMPP Control Panel

3. **Run Migration**:
   ```bash
   php artisan migrate
   ```

## Testing the Implementation

### Test Case 1: Create Office In-Charge for a DM
1. Login as Super Admin
2. Go to "Add New Member"
3. Select Designation: "Office In-Charge"
4. The upline section should appear
5. Select Upline's Designation: "District Manager (DM)"
6. Select the specific DM from the dropdown
7. Fill in other details and submit
8. The Office In-Charge should be created with access to that DM's entire downline

### Test Case 2: Verify Access Control
1. Login as the newly created Office In-Charge
2. Navigate to "My Team"
3. You should see all users under the selected DM
4. Try to view the DM's profile - should work
5. Try to delete the DM - should be blocked

### Test Case 3: Verify Permissions
1. As Office In-Charge, try to:
   - Create users under the DM's tree ✅
   - Approve users in the DM's tree ✅
   - Edit users in the DM's tree ✅
   - Delete users in the DM's tree ✅
   - Delete the DM (upline) ❌ Should fail

## Additional Notes

### Security Considerations
- Only Super Admin can create Office In-Charge accounts
- Office In-Charge cannot create other Office In-Charge accounts
- Office In-Charge cannot access or modify Super Admin
- Office In-Charge is restricted from deleting their upline

### Future Enhancements
You might want to consider:
1. Adding an expiry date for Office In-Charge access (already in DB schema)
2. Adding audit logs for Office In-Charge actions
3. Adding a dashboard view showing which Office In-Charge represents which upline
4. Email notifications when Office In-Charge is created

## Files Modified

1. ✅ `database/migrations/2026_01_14_000000_add_office_in_charge_upline_fields.php` (NEW)
2. ✅ `app/Models/User.php`
3. ✅ `app/Http/Controllers/UserController.php`
4. ✅ `resources/views/users/create.blade.php`

## Next Steps

1. Fix the MySQL driver issue (see Database Setup Required section)
2. Run the migration
3. Test the functionality
4. If you encounter any issues, check the Laravel logs at `storage/logs/laravel.log`
