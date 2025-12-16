# Attendance Log Seeder Documentation

## Overview
This seeder creates users with attendance logs showing 7-8 hours of attendance daily for testing and development purposes.

## What It Creates

### Users
The seeder creates 8 sample users with different roles:
- **Members**: John Doe, Jane Smith, David Brown, Tom Wilson
- **Coaches**: Mike Johnson, Lisa Davis  
- **Admins**: Sarah Williams, Emma Garcia

### Attendance Logs
- **Time Period**: Past 30 days (excluding weekends)
- **Work Schedule**: 7-8 hours daily with realistic variations:
  - 8:00 AM - 3:30 PM (7.5 hours)
  - 8:30 AM - 4:00 PM (7.5 hours)
  - 9:00 AM - 5:00 PM (8 hours)
  - 8:00 AM - 4:00 PM (8 hours)
  - 9:30 AM - 5:00 PM (7.5 hours)
  - 8:15 AM - 4:15 PM (8 hours)

### Status Variations
- **Present**: Normal work schedule (90% of days)
- **Late**: Arrive 15-30 minutes late (5% of days)
- **Left Early**: Leave 15-30 minutes early (5% of days)

## How to Use

### Run the Seeder
```bash
# Run only the attendance seeder
php artisan db:seed --class=AttendanceLogSeeder

# Run all seeders (including attendance)
php artisan db:seed

# Fresh migrate and seed
php artisan migrate:fresh --seed
```

### Individual User Credentials
All users have the password: `password`

| Name | Email | Role |
|------|-------|------|
| John Doe | john.doe@example.com | member |
| Jane Smith | jane.smith@example.com | member |
| Mike Johnson | mike.johnson@example.com | coach |
| Sarah Williams | sarah.williams@example.com | admin |
| David Brown | david.brown@example.com | member |
| Lisa Davis | lisa.davis@example.com | coach |
| Tom Wilson | tom.wilson@example.com | member |
| Emma Garcia | emma.garcia@example.com | admin |

## Files Modified/Created

1. **database/seeders/AttendanceLogSeeder.php** - Main seeder class
2. **database/seeders/DatabaseSeeder.php** - Updated to call AttendanceLogSeeder
3. **database/migrations/2025_12_16_145938_add_total_salary_to_payslips_table.php** - Added total_salary column
4. **app/Models/Payslip.php** - Updated to include total_salary in calculations
5. **app/Filament/Resources/Payslips/Pages/CreatePayslip.php** - Applied total_salary computation logic

## Total Salary Column
Added `total_salary` column to payslips table that represents gross pay (basic salary + allowances + overtime) before deductions.

## Total Salary Computation Logic
The `total_salary` field is calculated as:
```
total_salary = (basic_salary - attendance_deductions) + allowances + overtime_pay
```

This represents the total earnings before any mandatory deductions (SSS, PhilHealth, PAG-IBIG, tax).

## Database Schema Impact
- ✅ Users table: No changes (uses existing structure)
- ✅ Attendance_logs table: No changes (uses existing structure)  
- ✅ Payslips table: Added `total_salary` column (decimal, 10, 2)

## Important: Role Constraint Fix
The users table has an ENUM constraint for the `role` column with only these allowed values:
- `admin`
- `coach` 
- `member`

The seeder has been updated to only use these valid roles. Original attempt to use `instructor` role failed with "Data truncated for column 'role'" error.

## Testing
To verify the seeder worked correctly:
```bash
# Check created users
php artisan tinker
User::count() // Should return 8+

# Check attendance logs
AttendanceLog::count() // Should return ~160+ (8 users × ~20 working days)

# View sample attendance data
AttendanceLog::with('user')->take(5)->get();

# Test total_salary calculation
Payslip::with('employee')->take(3)->get();
```

## Implementation Details

### AttendanceLogSeeder
- Creates 8 diverse users with different roles (using only valid ENUM values)
- Generates realistic attendance patterns for 30 working days
- Skips weekends automatically
- Includes status variations (present, late, left_early)

### Payslip Integration
- **Model Level**: `app/Models/Payslip.php` - Boot method calculates total_salary
- **Controller Level**: `CreatePayslip.php` - Manual calculation during payslip creation
- Both approaches ensure consistent total_salary computation

### Migration
- `total_salary` column added with proper decimal precision (10, 2)
- Default value of 0 for existing records
- Positioned after `basic_salary` column for logical grouping

## Troubleshooting
If you encounter "Data truncated for column 'role'" error:
1. Ensure the `role` column in users table accepts: 'admin', 'coach', or 'member'
2. Check database migration: `2025_11_29_062325_add_role_and_status_to_users_table.php`
3. Verify ENUM values match the seeder data