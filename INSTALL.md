# Staff Report Module - Installation Guide

## Prerequisites

- Perfex CRM version 2.3.0 or higher
- PHP 7.2 or higher
- MySQL/MariaDB database
- Web server (Apache/Nginx)

## Installation Steps

### Method 1: Direct Upload (Recommended)

1. **Download the Module**
   - Download or clone this repository
   - Ensure the folder is named `staff_report`

2. **Upload to Perfex CRM**
   ```
   /path/to/perfex/modules/staff_report/
   ```
   
   Your directory structure should look like:
   ```
   perfex_crm/
   ├── modules/
   │   └── staff_report/
   │       ├── staff_report.php
   │       ├── install.php
   │       ├── controllers/
   │       ├── models/
   │       ├── views/
   │       └── ...
   ```

3. **Set Permissions**
   ```bash
   chmod 755 modules/staff_report
   chmod 644 modules/staff_report/*.php
   ```

4. **Activate the Module**
   - Login to Perfex CRM as Administrator
   - Navigate to **Setup → Modules**
   - Find "Staff Report" in the list
   - Click **Activate**

5. **Configure Permissions**
   - Go to **Setup → Roles**
   - Select a role to configure
   - Under "Staff Report" section, set permissions:
     - ✅ View Staff Report (for full access)
     - ✅ View Own Staff Report (for restricted access)
   - Save changes

### Method 2: Git Clone

```bash
cd /path/to/perfex/modules/
git clone https://github.com/md-riaz/perfex_staff_report.git staff_report
```

Then follow steps 3-5 from Method 1.

## Post-Installation Configuration

### 1. Verify Installation

After activation, you should see:
- New menu item "Staff Lead Report" in the admin sidebar
- Under Setup → Roles, "Staff Report" permission section

### 2. Test Access

- Login as a user with "View Staff Report" permission
- Click "Staff Lead Report" in the sidebar
- You should see the report interface

### 3. Configure Permissions by Role

**Administrator** (Recommended settings):
- ✅ View Staff Report
- ✅ View Own Staff Report

**Staff Manager** (Recommended settings):
- ✅ View Staff Report
- ❌ View Own Staff Report

**Regular Staff** (Recommended settings):
- ❌ View Staff Report
- ✅ View Own Staff Report

**Sales Representative** (Recommended settings):
- ❌ View Staff Report
- ✅ View Own Staff Report

## Verification Checklist

After installation, verify:

- [ ] Module appears in Setup → Modules as "Active"
- [ ] "Staff Lead Report" menu item visible in admin sidebar
- [ ] Report page loads without errors
- [ ] Can apply filters and generate report
- [ ] Export to CSV works
- [ ] Permissions are properly enforced
- [ ] No errors in PHP error logs

## Troubleshooting

### Module Not Appearing

**Problem**: Module doesn't show up in Setup → Modules

**Solutions**:
1. Check folder name is exactly `staff_report`
2. Verify file permissions (755 for directories, 644 for files)
3. Clear Perfex cache: Delete `application/cache/` contents
4. Check PHP error logs for issues

### Access Denied Error

**Problem**: "Access Denied" when clicking Staff Lead Report

**Solutions**:
1. Go to Setup → Roles
2. Find your role
3. Enable "View Staff Report" or "View Own Staff Report"
4. Logout and login again

### Report Not Loading

**Problem**: Report page shows loading spinner indefinitely

**Solutions**:
1. Check browser console for JavaScript errors
2. Verify AJAX endpoint is accessible
3. Check PHP error logs
4. Ensure database has leads data
5. Test with different browsers

### Export Not Working

**Problem**: Export button does nothing or shows error

**Solutions**:
1. Check PHP `output_buffering` setting
2. Verify write permissions for temp folder
3. Check browser download settings
4. Look for errors in browser console

### Empty Report

**Problem**: Report shows "No data" or empty table

**Solutions**:
1. Verify leads exist in database
2. Check if leads have assigned staff members
3. Adjust date range filters
4. Verify lead statuses are configured
5. Check permissions (ensure you can see the leads)

### Custom Fields Not Showing

**Problem**: Custom field filters don't appear

**Solutions**:
1. Go to Setup → Custom Fields
2. Ensure custom fields are created for "Leads"
3. Set custom fields as "Active"
4. Refresh the report page

## Database Verification

To verify the module tables were created:

```sql
-- Check if settings table exists
SHOW TABLES LIKE 'tblstaff_report_settings';

-- Verify permissions were added
SELECT * FROM tbloptions WHERE name LIKE 'staff_report_%';
```

Expected output:
- `tblstaff_report_settings` table should exist
- Options `staff_report_view` and `staff_report_view_own` should exist

## Upgrading

If upgrading from a previous version:

1. Backup your database
2. Backup the existing module folder
3. Deactivate the old version
4. Replace the module folder with new version
5. Reactivate the module
6. Test thoroughly

## Uninstallation

To completely remove the module:

1. **Deactivate Module**
   - Go to Setup → Modules
   - Click "Deactivate" next to Staff Report

2. **Delete Files**
   ```bash
   rm -rf /path/to/perfex/modules/staff_report/
   ```

3. **Clean Database** (Optional)
   ```sql
   DROP TABLE IF EXISTS tblstaff_report_settings;
   DELETE FROM tbloptions WHERE name LIKE 'staff_report_%';
   ```

Note: Deactivation automatically runs `uninstall.php` which cleans up database tables and options.

## Getting Help

If you encounter issues:

1. **Check Documentation**
   - README.md - Overview and features
   - DOCUMENTATION.md - Technical details
   - SECURITY.md - Security review

2. **Review Logs**
   - PHP error logs
   - Browser console logs
   - Perfex CRM debug logs

3. **Common Issues**
   - Check this guide's Troubleshooting section
   - Verify all prerequisites are met
   - Ensure Perfex CRM is up to date

4. **Get Support**
   - GitHub Issues: https://github.com/md-riaz/perfex_staff_report/issues
   - Provide: Perfex version, PHP version, error messages, steps to reproduce

## Next Steps

After successful installation:

1. Read the [User Guide](README.md) to learn about features
2. Configure permissions for your team
3. Train staff on using the report
4. Set up regular reporting schedules
5. Consider customizations for your needs

## System Requirements Check

Run this in your Perfex CRM environment:

```php
<?php
// Check PHP version
echo "PHP Version: " . phpversion() . "\n";
echo "Required: 7.2+\n\n";

// Check required extensions
$required = ['mysqli', 'json', 'mbstring'];
foreach ($required as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? 'OK' : 'MISSING') . "\n";
}
?>
```

All checks should show OK or version >= 7.2.

---

**Installation Complete!** 🎉

Your Staff Report module is now ready to use. Access it from the admin sidebar under "Staff Lead Report".
