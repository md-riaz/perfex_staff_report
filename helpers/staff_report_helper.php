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
    
    // Date filters
    if (isset($filters['date_from']) && !empty($filters['date_from'])) {
        $sanitized['date_from'] = trim($filters['date_from']);
    }
    if (isset($filters['date_to']) && !empty($filters['date_to'])) {
        $sanitized['date_to'] = trim($filters['date_to']);
    }
    if (isset($filters['date_assigned_from']) && !empty($filters['date_assigned_from'])) {
        $sanitized['date_assigned_from'] = trim($filters['date_assigned_from']);
    }
    if (isset($filters['date_assigned_to']) && !empty($filters['date_assigned_to'])) {
        $sanitized['date_assigned_to'] = trim($filters['date_assigned_to']);
    }
    if (isset($filters['last_contact_from']) && !empty($filters['last_contact_from'])) {
        $sanitized['last_contact_from'] = trim($filters['last_contact_from']);
    }
    if (isset($filters['last_contact_to']) && !empty($filters['last_contact_to'])) {
        $sanitized['last_contact_to'] = trim($filters['last_contact_to']);
    }
    
    // Staff and system filters
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
    
    // Lead field filters
    if (isset($filters['name']) && !empty($filters['name'])) {
        $sanitized['name'] = strip_tags($filters['name']);
    }
    if (isset($filters['email']) && !empty($filters['email'])) {
        $sanitized['email'] = filter_var($filters['email'], FILTER_SANITIZE_EMAIL);
    }
    if (isset($filters['phone']) && !empty($filters['phone'])) {
        $sanitized['phone'] = strip_tags($filters['phone']);
    }
    if (isset($filters['country']) && !empty($filters['country'])) {
        $sanitized['country'] = (int)$filters['country'];
    }
    if (isset($filters['city']) && !empty($filters['city'])) {
        $sanitized['city'] = strip_tags($filters['city']);
    }
    if (isset($filters['state']) && !empty($filters['state'])) {
        $sanitized['state'] = strip_tags($filters['state']);
    }
    if (isset($filters['zip']) && !empty($filters['zip'])) {
        $sanitized['zip'] = strip_tags($filters['zip']);
    }
    if (isset($filters['lead_value_from']) && $filters['lead_value_from'] !== '') {
        $sanitized['lead_value_from'] = (float)$filters['lead_value_from'];
    }
    if (isset($filters['lead_value_to']) && $filters['lead_value_to'] !== '') {
        $sanitized['lead_value_to'] = (float)$filters['lead_value_to'];
    }
    if (isset($filters['is_public']) && $filters['is_public'] !== '') {
        $sanitized['is_public'] = (int)$filters['is_public'];
    }
    if (isset($filters['lost']) && $filters['lost'] !== '') {
        $sanitized['lost'] = (int)$filters['lost'];
    }
    if (isset($filters['junk']) && $filters['junk'] !== '') {
        $sanitized['junk'] = (int)$filters['junk'];
    }
    
    // Custom fields
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
