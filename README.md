# Perfex CRM - Staff Lead Report Module

A comprehensive Perfex CRM module that extends the Reports functionality to provide detailed staff lead status reporting with dynamic columns and advanced filtering capabilities.

## Features

- **Dynamic Status Columns**: Automatically generates columns for each custom lead status in your Perfex CRM
- **Comprehensive Filtering**: 
  - Date range selection
  - Staff member filter
  - Lead source filter
  - Lead status filter
  - Custom fields support
- **Permission-Based Access**: 
  - View all staff reports (global permission)
  - View own reports only (restricted permission)
- **Export Functionality**: Export reports to Excel for further analysis
- **Real-time Data**: AJAX-based report generation with loading indicators
- **Responsive Design**: Mobile-friendly interface with Bootstrap styling
- **Total Calculations**: Automatic calculation of totals per staff and per status

## Installation

1. Download the module files
2. Extract the `staff_report` folder to your Perfex CRM modules directory:
   ```
   /modules/staff_report/
   ```
3. Navigate to **Setup → Modules** in your Perfex CRM admin panel
4. Find "Staff Report" in the modules list
5. Click **Activate**

## Usage

### Accessing the Report

1. After activation, a new menu item "Staff Lead Report" will appear in the admin sidebar
2. Click on it to access the report interface

### Generating Reports

1. **Select Filters** (optional):
   - **Date Range**: Filter leads by creation date
   - **Staff Member**: View specific staff member's performance
   - **Lead Source**: Filter by lead source
   - **Lead Status**: Focus on specific lead statuses
   - **Custom Fields**: Use any custom fields you've defined for leads

2. **Click "Apply Filters"** to generate the report

3. **View Results**:
   - Each row represents a staff member
   - Each column shows lead count for a specific status
   - The last column shows total leads per staff
   - The last row shows totals for each status

4. **Export**: Click "Export to Excel" to download the report

### Permissions

Configure permissions under **Setup → Roles**:

- **View Staff Report**: Full access to all staff reports
- **View Own Staff Report**: Users can only see their own lead statistics

## Module Structure

```
staff_report/
├── staff_report.php              # Main module file with hooks
├── install.php                    # Installation script
├── uninstall.php                  # Uninstallation script
├── controllers/
│   └── Staff_report.php           # Main controller
├── models/
│   └── Staff_report_model.php     # Data access layer
├── views/
│   └── report.php                 # Report interface
└── language/
    └── english/
        └── staff_report_lang.php  # English translations
```

## Technical Details

### MVC Architecture

The module follows Perfex CRM's MVC pattern:

- **Model** (`Staff_report_model`): Handles all database queries with support for complex filtering
- **View** (`report.php`): Responsive UI with AJAX-based data loading
- **Controller** (`Staff_report`): Manages request routing and permission checks

### Database Integration

- Uses existing Perfex tables (no additional tables for core functionality)
- Queries: `leads`, `staff`, `leads_status`, `leads_source`, `customfieldsvalues`
- Optional settings table for future enhancements

### Hooks & Integration

- Integrates with Perfex's permission system
- Uses standard Perfex hooks (`admin_init`, module activation)
- Leverages existing Perfex models (leads_model, staff_model)

### Security

- Permission-based access control
- SQL injection prevention via CodeIgniter Query Builder
- XSS protection in views
- AJAX requests validation

## Compatibility

- **Minimum Perfex Version**: 2.3.*
- **PHP Version**: 7.2 or higher
- **Database**: MySQL/MariaDB

## Support & Customization

For customization or support:
- Review the code comments for implementation details
- Extend the model to add additional metrics
- Customize the view for different report layouts
- Add additional filters as needed

## License

This module is provided as-is for use with Perfex CRM.

## Changelog

### Version 1.0.0
- Initial release
- Dynamic lead status columns
- Comprehensive filtering (date, staff, source, status, custom fields)
- Permission-based access control
- Excel export functionality
- Responsive design