# Staff Report Module - Technical Documentation

## Overview

The Staff Report module is a comprehensive Perfex CRM extension that provides detailed reporting on staff lead performance with dynamic status columns and advanced filtering capabilities.

## Architecture

### MVC Pattern

The module follows Perfex CRM's standard Model-View-Controller architecture:

```
staff_report/
├── controllers/
│   └── Staff_report.php      # Handles HTTP requests and responses
├── models/
│   └── Staff_report_model.php # Database operations and business logic
├── views/
│   └── report.php             # User interface
└── helpers/
    └── staff_report_helper.php # Utility functions
```

## Core Components

### 1. Main Module File (`staff_report.php`)

**Purpose**: Module initialization and registration

**Key Functions**:
- `staff_report_module_init_menu_items()`: Registers admin menu items
- `staff_report_module_activation_hook()`: Handles module activation
- Registers language files and permissions

**Hooks Used**:
- `admin_init`: Initialize menu items
- `register_activation_hook`: Setup during activation

### 2. Controller (`Staff_report.php`)

**Methods**:

#### `index()`
- Displays the main report interface
- Loads necessary data (staff, statuses, sources, custom fields)
- Checks permissions before rendering

#### `get_report_data()`
- AJAX endpoint for fetching report data
- Applies filters and permissions
- Returns JSON response with status columns and counts

#### `export()`
- Generates CSV export of report data
- Applies same filters as main report
- Returns downloadable file

### 3. Model (`Staff_report_model.php`)

**Methods**:

#### `get_staff_lead_report($filters = [])`
Returns:
```php
[
    'statuses' => [
        ['id' => 1, 'name' => 'New', 'color' => '#28B8DA'],
        // ... more statuses
    ],
    'data' => [
        [
            'staff_id' => 1,
            'staff_name' => 'John Doe',
            'status_counts' => [1 => 5, 2 => 10, ...],
            'total' => 15
        ],
        // ... more staff
        [
            'staff_id' => null,
            'staff_name' => 'Total',
            'status_counts' => [...],
            'total' => 150
        ]
    ]
]
```

**Query Optimization**:
- Uses GROUP BY for efficient counting
- Single query with joins for custom fields
- Pre-initializes status counts to avoid missing columns

### 4. View (`report.php`)

**Sections**:

1. **Filters Panel**: Collapsible filter section with:
   - Date range picker
   - Staff selector (multi-select capable)
   - Source dropdown
   - Status dropdown
   - Custom fields (dynamic based on CRM configuration)

2. **Report Table**: Dynamic table with:
   - Column headers from lead statuses
   - Rows for each staff member
   - Total row at bottom
   - Color-coded status headers

**JavaScript Functions**:

- `applyFilters()`: Fetches filtered data via AJAX
- `renderReport(data)`: Dynamically builds table from JSON
- `resetFilters()`: Clears all filters
- `exportReport()`: Triggers CSV download
- `escapeHtml(text)`: XSS prevention

### 5. Helper Functions (`staff_report_helper.php`)

Utility functions for:
- Permission checking
- URL generation
- Data sanitization
- Filter validation

## Database Schema

### Primary Tables Used

1. **`tblleads`**
   - `id`: Lead identifier
   - `assigned`: Staff member ID
   - `status`: Lead status ID
   - `source`: Lead source ID
   - `dateadded`: Creation timestamp

2. **`tblstaff`**
   - `staffid`: Staff identifier
   - `firstname`, `lastname`: Staff names
   - `active`: Active status

3. **`tblleads_status`**
   - `id`: Status identifier
   - `name`: Status name
   - `color`: Display color
   - `statusorder`: Sort order

4. **`tblleads_sources`**
   - `id`: Source identifier
   - `name`: Source name

5. **`tblcustomfieldsvalues`**
   - Custom field values for leads

### Optional Table

**`tblstaff_report_settings`**
```sql
CREATE TABLE `tblstaff_report_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Permissions System

### Permission Capabilities

1. **`staff_report.view`**
   - Full access to all staff reports
   - Can filter by any staff member
   - Can view global statistics

2. **`staff_report.view_own`**
   - Restricted to own lead statistics
   - Cannot view other staff data
   - Limited filter options

### Implementation

```php
// Controller level
if (!has_permission('staff_report', '', 'view') && 
    !has_permission('staff_report', '', 'view_own')) {
    access_denied('staff_report');
}

// Data level filtering
if (!has_permission('staff_report', '', 'view') && 
    has_permission('staff_report', '', 'view_own')) {
    $filters['staff_id'] = get_staff_user_id();
}
```

## Filter System

### Available Filters

1. **Date Range**
   - Format: YYYY-MM-DD
   - Applied to `dateadded` field
   - Inclusive range

2. **Staff Member**
   - Single or multiple selection
   - Filters `assigned` field
   - Automatically restricted for `view_own` permission

3. **Lead Source**
   - Single selection
   - Filters `source` field

4. **Lead Status**
   - Single selection
   - Filters `status` field
   - Does not affect column display

5. **Custom Fields**
   - Dynamic based on CRM configuration
   - Supports text, select, date types
   - Uses LIKE matching for flexibility

### Filter Sanitization

```php
function staff_report_sanitize_filters($filters)
{
    // Integer validation for IDs
    // String trimming for dates
    // XSS prevention for custom fields
    // Array support for multi-select
}
```

## Security Considerations

### Input Validation

1. **Controller Level**
   - Permission checks on every request
   - Input sanitization via helper functions
   - Type casting for numeric values

2. **Model Level**
   - Query Builder (prevents SQL injection)
   - Parameterized queries
   - Input validation

3. **View Level**
   - XSS prevention via `escapeHtml()`
   - CSRF tokens (Perfex built-in)
   - Content-Type headers

### Best Practices Implemented

- No direct SQL queries (uses Query Builder)
- All user input is sanitized
- Permission checks before data access
- Error messages don't expose system details
- Logging for debugging (not user-visible)

## Performance Optimization

### Query Optimization

1. **Single Query Approach**
   ```php
   // One query with GROUP BY instead of multiple queries
   SELECT assigned, status, COUNT(id) as count
   FROM tblleads
   GROUP BY assigned, status
   ```

2. **Indexed Fields**
   - Uses indexed columns: `assigned`, `status`, `source`, `dateadded`
   - Efficient JOIN operations

3. **Data Organization**
   - Post-processing in PHP for flexibility
   - Pre-initialized arrays to avoid missing keys

### Frontend Optimization

1. **AJAX Loading**
   - Async data fetching
   - Loading indicators
   - Non-blocking UI

2. **Responsive Design**
   - Bootstrap grid system
   - Mobile-friendly tables
   - Touch-scrolling support

## Internationalization

### Language Files

Location: `language/english/staff_report_lang.php`

**Key Translations**:
- UI labels
- Error messages
- Button text
- Filter labels

**Adding New Languages**:
1. Create `language/{locale}/staff_report_lang.php`
2. Copy keys from English file
3. Translate values
4. Module auto-loads based on user preference

## Extension Points

### Adding Custom Metrics

**Example**: Add "Conversion Rate" column

1. **Model**: Calculate conversion in `get_staff_lead_report()`
2. **Controller**: Pass data to view
3. **View**: Add column in `renderReport()`

### Adding New Filters

**Example**: Add "Industry" filter

1. **View**: Add filter input in filters panel
2. **Controller**: Accept new filter parameter
3. **Model**: Add WHERE clause for new filter
4. **Helper**: Add sanitization for new filter

### Customizing Export Format

Current: CSV export
To add Excel (.xlsx):

```php
// In controller export()
$this->load->library('excel');
// Use PHPExcel/PhpSpreadsheet library
```

## Testing Checklist

### Functional Testing

- [ ] Report loads without errors
- [ ] All filters work correctly
- [ ] Permissions are enforced
- [ ] Export generates valid file
- [ ] Custom fields display properly
- [ ] Total calculations are accurate

### Permission Testing

- [ ] Admin can view all reports
- [ ] Staff with `view_own` only see own data
- [ ] Staff without permission see access denied

### Performance Testing

- [ ] Report loads in < 2 seconds with 1000 leads
- [ ] Export completes for large datasets
- [ ] Multiple concurrent users don't cause issues

### Security Testing

- [ ] SQL injection attempts are blocked
- [ ] XSS attempts are prevented
- [ ] CSRF tokens are validated
- [ ] Unauthorized access is denied

## Troubleshooting

### Common Issues

1. **"Module not found" error**
   - Ensure folder is named `staff_report`
   - Check file permissions (755 for folders, 644 for files)

2. **"Access denied" message**
   - Verify user has appropriate permissions
   - Check role configuration in Setup → Roles

3. **Empty report**
   - Verify leads exist in database
   - Check date range filter
   - Ensure staff has assigned leads

4. **Export not working**
   - Check PHP `output_buffering` setting
   - Verify write permissions for temp folder
   - Check browser download settings

### Debug Mode

Enable Perfex CRM debug mode to see detailed errors:

```php
// In application/config/config.php
define('ENVIRONMENT', 'development');
```

## Maintenance

### Version Updates

1. Update version in `staff_report.php`
2. Add changes to README changelog
3. Update `module.json` version
4. Test upgrade path from previous version

### Database Migrations

For future versions requiring schema changes:

```php
// In install.php
if (!$CI->db->field_exists('new_field', db_prefix() . 'staff_report_settings')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff_report_settings` 
                    ADD `new_field` VARCHAR(255) NULL');
}
```

## API Reference

### Helper Functions

```php
// Permission checking
staff_report_has_permission('view')
staff_report_can_view_all()
staff_report_can_view_own()

// URL generation
staff_report_url('export')

// Data formatting
staff_report_format_number($number)
staff_report_sanitize_filters($filters)

// Utilities
staff_report_get_status_color($status)
staff_report_get_default_date_range()
```

### Model Methods

```php
// Get full report
$this->staff_report_model->get_staff_lead_report([
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31',
    'staff_id' => 1,
    'source_id' => 2,
    'status_id' => 3,
    'custom_fields' => [1 => 'value']
]);

// Get specific count
$this->staff_report_model->get_lead_count($staff_id, $status_id, $filters);
```

## Support

For issues, questions, or contributions:
- GitHub: https://github.com/md-riaz/perfex_staff_report
- Documentation: This file
- Perfex CRM Docs: https://help.perfexcrm.com/

## License

This module is provided as-is for use with Perfex CRM installations.
