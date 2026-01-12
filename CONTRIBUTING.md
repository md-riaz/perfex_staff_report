# Contributing to Staff Report Module

Thank you for your interest in contributing to the Staff Report module for Perfex CRM!

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- Perfex CRM version
- PHP version
- Browser (if UI related)

### Suggesting Enhancements

For feature requests:
- Describe the feature and use case
- Explain how it benefits users
- Provide examples if applicable

### Code Contributions

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Follow coding standards**
   - Use Perfex CRM coding conventions
   - Follow PSR-2 for PHP code
   - Add comments for complex logic
   - Keep functions focused and single-purpose

4. **Test your changes**
   - Test with different permission levels
   - Verify filters work correctly
   - Check export functionality
   - Test with large datasets
   - Verify mobile responsiveness

5. **Commit your changes**
   ```bash
   git commit -m "Add feature: description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**
   - Describe what changed and why
   - Reference any related issues
   - Include screenshots for UI changes

## Coding Guidelines

### PHP Standards

- Use CodeIgniter 3 conventions
- Follow Perfex CRM patterns
- Type hint when possible
- Validate all inputs
- Use Query Builder (no raw SQL)
- Add docblocks to functions

**Example**:
```php
/**
 * Get lead count for specific staff and status
 * @param int $staff_id Staff member ID
 * @param int $status_id Lead status ID
 * @param array $filters Additional filters
 * @return int Lead count
 */
public function get_lead_count($staff_id, $status_id, $filters = [])
{
    // Implementation
}
```

### JavaScript Standards

- Use jQuery (included in Perfex)
- Camel case for functions
- Add comments for complex logic
- Handle errors gracefully
- Use AJAX for data loading

**Example**:
```javascript
/**
 * Apply filters and reload report data
 */
function applyFilters() {
    // Implementation
}
```

### CSS Standards

- Use Bootstrap classes when possible
- Prefix custom classes with `staff-report-`
- Mobile-first responsive design
- Comment sections

**Example**:
```css
/* Staff Report Table Styles */
.staff-report-table {
    /* Styles */
}
```

## File Structure

When adding new files:

```
staff_report/
├── controllers/         # Controller files
├── models/             # Model files
├── views/              # View files
├── helpers/            # Helper functions
├── language/           # Translations
│   └── english/
│       └── staff_report_lang.php
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Images
└── docs/              # Additional documentation
```

## Testing Guidelines

### Manual Testing

1. **Permission Testing**
   - Test as admin with full access
   - Test as staff with view permission
   - Test as staff with view_own permission
   - Test without permission

2. **Filter Testing**
   - Test each filter individually
   - Test combinations of filters
   - Test with empty filters
   - Test date range edge cases

3. **Export Testing**
   - Export with no data
   - Export with large datasets
   - Verify CSV format is correct
   - Check all columns are included

4. **UI Testing**
   - Test on desktop browsers
   - Test on mobile devices
   - Test filter panel collapse/expand
   - Verify loading indicators work

### Database Testing

- Test with empty tables
- Test with large datasets (1000+ leads)
- Test with multiple lead statuses
- Test with custom fields

## Security Checklist

- [ ] All inputs are sanitized
- [ ] SQL injection is prevented
- [ ] XSS is prevented
- [ ] Permissions are checked
- [ ] CSRF protection is in place
- [ ] Error messages don't expose sensitive data

## Documentation

When adding features:

1. Update README.md if user-facing
2. Update DOCUMENTATION.md for technical details
3. Add inline code comments
4. Update language files if adding text
5. Add examples for complex features

## Version Guidelines

Follow semantic versioning (SEMVER):

- **Major** (1.0.0): Breaking changes
- **Minor** (1.1.0): New features, backwards compatible
- **Patch** (1.0.1): Bug fixes

## Questions?

If you have questions about contributing:
- Check existing documentation
- Review existing code for patterns
- Open an issue for clarification

## Code of Conduct

- Be respectful and constructive
- Focus on the issue, not the person
- Accept feedback graciously
- Help others learn and grow

Thank you for contributing! 🎉
