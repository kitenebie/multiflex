# Attendance Log Seeder Documentation

## Overview
This seeder creates users with attendance logs showing 7-8 hours of attendance daily for testing and development purposes.

## What It Creates

### Users
The seeder creates 8 sample users with different roles:
- **Members**: John Doe, Jane Smith, David Brown, Tom Wilson
- **Coaches**: Mike Johnson, Lisa Davis  
- **Instructors**: Sarah Williams, Emma Garcia

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
| Sarah Williams | sarah.williams@example.com | instructor |
| David Brown | david.brown@example.com | member |
| Lisa Davis | lisa.davis@example.com | coach |
| Tom Wilson | tom.wilson@example.com | member |
| Emma Garcia | emma.garcia@example.com | instructor |

## Files Modified/Created

1. **database/seeders/AttendanceLogSeeder.php** - Main seeder class
2. **database/seeders/DatabaseSeeder.php** - Updated to call AttendanceLogSeeder
3. **database/migrations/2025_12_16_145938_add_total_salary_to_payslips_table.php** - Added total_salary column
4. **app/Models/Payslip.php** - Updated to include total_salary in calculations

## Total Salary Column
Added `total_salary` column to payslips table that represents gross pay (basic salary + allowances + overtime) before deductions.

## Database Schema Impact
- ✅ Users table: No changes (uses existing structure)
- ✅ Attendance_logs table: No changes (uses existing structure)  
- ✅ Payslips table: Added `total_salary` column (decimal, 10, 2)

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