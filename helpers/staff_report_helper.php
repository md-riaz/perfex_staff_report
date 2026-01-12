<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check if current user has permission to access staff reports
 * @param string $permission_type
 * @return bool
 */
function staff_report_has_permission($permission_type = 'view')
{
    return has_permission('staff_report', '', $permission_type);
}

/**
 * Get staff report URL
 * @param string $route
 * @return string
 */
function staff_report_url($route = '')
{
    return admin_url('staff_report/' . $route);
}

/**
 * Format number for report display
 * @param mixed $number
 * @return string
 */
function staff_report_format_number($number)
{
    return number_format((float)$number, 0);
}

/**
 * Get color for status
 * @param array $status
 * @return string
 */
function staff_report_get_status_color($status)
{
    return isset($status['color']) && !empty($status['color']) ? $status['color'] : '#28B8DA';
}

/**
 * Sanitize report filters
 * @param array $filters
 * @return array
 */
function staff_report_sanitize_filters($filters)
{
    $sanitized = [];
    
    if (isset($filters['date_from']) && !empty($filters['date_from'])) {
        $sanitized['date_from'] = trim($filters['date_from']);
    }
    
    if (isset($filters['date_to']) && !empty($filters['date_to'])) {
        $sanitized['date_to'] = trim($filters['date_to']);
    }
    
    if (isset($filters['staff_id']) && !empty($filters['staff_id'])) {
        $sanitized['staff_id'] = is_array($filters['staff_id']) 
            ? array_map('intval', $filters['staff_id'])
            : (int)$filters['staff_id'];
    }
    
    if (isset($filters['source_id']) && !empty($filters['source_id'])) {
        $sanitized['source_id'] = is_array($filters['source_id'])
            ? array_map('intval', $filters['source_id'])
            : (int)$filters['source_id'];
    }
    
    if (isset($filters['status_id']) && !empty($filters['status_id'])) {
        $sanitized['status_id'] = is_array($filters['status_id'])
            ? array_map('intval', $filters['status_id'])
            : (int)$filters['status_id'];
    }
    
    if (isset($filters['custom_fields']) && is_array($filters['custom_fields'])) {
        $sanitized['custom_fields'] = [];
        foreach ($filters['custom_fields'] as $field_id => $value) {
            if (!empty($value)) {
                $sanitized['custom_fields'][(int)$field_id] = strip_tags($value);
            }
        }
    }
    
    return $sanitized;
}

/**
 * Get default date range for reports
 * @return array
 */
function staff_report_get_default_date_range()
{
    return [
        'date_from' => date('Y-m-01'), // First day of current month
        'date_to'   => date('Y-m-d'),  // Today
    ];
}

/**
 * Check if user can view all staff data
 * @return bool
 */
function staff_report_can_view_all()
{
    return has_permission('staff_report', '', 'view');
}

/**
 * Check if user can only view own data
 * @return bool
 */
function staff_report_can_view_own()
{
    return has_permission('staff_report', '', 'view_own') && !staff_report_can_view_all();
}
